<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use App\Models\LogAktivitas;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class AgendaController extends Controller
{
    // ============================================================
    // INDEX — Daftar Agenda (paginasi)
    // ============================================================
    public function index()
    {
        $agendas = Agenda::orderBy('tanggal', 'desc')->paginate(10);

        return view('admin.agenda.index', compact('agendas'));
    }

    // ============================================================
    // CREATE — Form Tambah Agenda
    // ============================================================
    public function create()
    {
        return view('admin.agenda.create');
    }

    // ============================================================
    // STORE — Simpan Agenda Baru
    // ============================================================
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'judul'     => 'required|string|max:255',
                'deskripsi' => 'nullable|string|max:5000',
                'tanggal'   => 'required|date',
                'waktu'     => 'nullable|string|max:50',
                'tempat'    => 'nullable|string|max:255',
                'is_active' => 'nullable|boolean',
            ]);

            // 🔒 Explicit assign (bukan mass assignment)
            $agenda = new Agenda();
            $agenda->judul     = $validated['judul'];
            $agenda->deskripsi = $validated['deskripsi'] ?? null;
            $agenda->tanggal   = $validated['tanggal'];
            $agenda->waktu     = $validated['waktu'] ?? null;
            $agenda->tempat    = $validated['tempat'] ?? null;
            $agenda->is_active = $request->has('is_active') ? 1 : 0;
            $agenda->user_id   = (int) Auth::id();
            $agenda->save();

            $this->logAktivitas(
                $request,
                'Tambah Agenda',
                "Menambahkan agenda '{$agenda->judul}' pada tanggal {$agenda->tanggal}"
            );

            return redirect()
                ->route('admin.agenda.index')
                ->with('success', 'Agenda berhasil ditambahkan!');

        } catch (ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();

        } catch (\Exception $e) {
            Log::error('Store agenda error', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);

            return back()
                ->with('error', 'Gagal menambahkan agenda. Silakan coba lagi.')
                ->withInput();
        }
    }

    // ============================================================
    // EDIT — Form Edit Agenda
    // ============================================================
    public function edit($id)
    {
        if (!$this->isValidId($id)) {
            abort(404);
        }

        $agenda = Agenda::findOrFail((int) $id);

        return view('admin.agenda.edit', compact('agenda'));
    }

    // ============================================================
    // UPDATE — Update Agenda
    // ============================================================
    public function update(Request $request, $id)
    {
        try {
            $agenda = Agenda::findOrFail($id);

            $validated = $request->validate([
                'judul'     => 'required|string|max:255',
                'deskripsi' => 'nullable|string|max:5000',
                'tanggal'   => 'required|date',
                'waktu'     => 'nullable|string|max:50',
                'tempat'    => 'nullable|string|max:255',
                'is_active' => 'nullable|boolean',
            ]);

            // 🔒 Explicit assign
            $agenda->judul     = $validated['judul'];
            $agenda->deskripsi = $validated['deskripsi'] ?? null;
            $agenda->tanggal   = $validated['tanggal'];
            $agenda->waktu     = $validated['waktu'] ?? null;
            $agenda->tempat    = $validated['tempat'] ?? null;
            $agenda->is_active = $request->has('is_active') ? 1 : 0;
            $agenda->save();

            $this->logAktivitas(
                $request,
                'Update Agenda',
                "Mengupdate agenda '{$agenda->judul}'"
            );

            return redirect()
                ->route('admin.agenda.index')
                ->with('success', 'Agenda berhasil diupdate!');

        } catch (ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();

        } catch (\Exception $e) {
            Log::error('Update agenda error', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'id'      => $id,
            ]);

            return back()
                ->with('error', 'Gagal mengupdate agenda. Silakan coba lagi.')
                ->withInput();
        }
    }

    // ============================================================
    // DESTROY — Hapus Agenda
    // ============================================================
    /**
     * Menangani 2 skenario:
     * 1. Request AJAX/fetch → kembalikan JSON
     * 2. Request form biasa → redirect dengan flash message
     */
    public function destroy($id)
    {
        try {
            $agenda = Agenda::findOrFail($id);
            $judul  = $agenda->judul;

            $agenda->delete();

            $this->logAktivitas(
                request(),
                'Hapus Agenda',
                "Menghapus agenda '{$judul}'"
            );

            // Response JSON untuk AJAX, redirect untuk form
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Agenda berhasil dihapus!',
                ]);
            }

            return redirect()
                ->route('admin.agenda.index')
                ->with('success', 'Agenda berhasil dihapus!');

        } catch (\Exception $e) {
            Log::error('Delete agenda error', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'id'      => $id,
            ]);

            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus agenda. Silakan coba lagi.',
                ], 500);
            }

            return back()->with('error', 'Gagal menghapus agenda. Silakan coba lagi.');
        }
    }

    // ============================================================
    // TOGGLE ACTIVE — Aktif/Nonaktif Agenda
    // ============================================================
    public function toggleActive($id): JsonResponse
    {
        try {
            $agenda = Agenda::findOrFail($id);

            $agenda->is_active = !$agenda->is_active;
            $agenda->save();

            $status = $agenda->is_active ? 'diaktifkan' : 'dinonaktifkan';

            $this->logAktivitas(
                request(),
                'Toggle Agenda',
                "Agenda '{$agenda->judul}' {$status}"
            );

            return response()->json([
                'success'   => true,
                'is_active' => $agenda->is_active,
                'message'   => "Agenda berhasil {$status}",
            ]);

        } catch (\Exception $e) {
            Log::error('Toggle agenda error', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'id'      => $id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status. Silakan coba lagi.',
            ], 500);
        }
    }

    // ============================================================
    // PRIVATE HELPERS
    // ============================================================

    /**
     * Validasi format ID (angka positif).
     */
    private function isValidId($id): bool
    {
        return is_numeric($id) && (int) $id > 0;
    }

    /**
     * Helper log aktivitas — supaya tidak duplikasi.
     */
    private function logAktivitas(Request $request, string $aktivitas, string $deskripsi): void
    {
        LogAktivitas::create([
            'user_id'    => (int) Auth::id(),
            'aktivitas'  => $aktivitas,
            'deskripsi'  => $deskripsi,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
    }
}
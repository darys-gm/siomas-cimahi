<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AgendaController extends Controller
{
    public function index()
    {
        $agendas = Agenda::orderBy('tanggal', 'desc')->paginate(10);
        return view('admin.agenda.index', compact('agendas'));
    }

    public function create()
    {
        return view('admin.agenda.create');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'judul' => 'required|string|max:255',
                'deskripsi' => 'nullable|string',
                'tanggal' => 'required|date',
                'waktu' => 'nullable|string',
                'tempat' => 'nullable|string',
                'is_active' => 'nullable|boolean',
            ]);

            $agenda = Agenda::create([
                'judul' => $validated['judul'],
                'deskripsi' => $validated['deskripsi'] ?? null,
                'tanggal' => Carbon::parse($validated['tanggal'])->format('Y-m-d'),
                'waktu' => $validated['waktu'] ?? null,
                'tempat' => $validated['tempat'] ?? null,
                'is_active' => $request->has('is_active') ? 1 : 0,
                'user_id' => auth()->id(),
            ]);

            LogAktivitas::create([
                'user_id' => auth()->id(),
                'aktivitas' => 'Tambah Agenda',
                'deskripsi' => "Menambahkan agenda '{$agenda->judul}' pada tanggal {$agenda->tanggal}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            return redirect()->route('admin.agenda.index')
                ->with('success', 'Agenda berhasil ditambahkan!');

        } catch (\Exception $e) {
            \Log::error('Error saving agenda: ' . $e->getMessage());
            return back()->with('error', 'Gagal menambahkan agenda: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit($id)
    {
        $agenda = Agenda::findOrFail($id);
        return view('admin.agenda.edit', compact('agenda'));
    }

    public function update(Request $request, $id)
    {
        try {
            $agenda = Agenda::findOrFail($id);

            $validated = $request->validate([
                'judul' => 'required|string|max:255',
                'deskripsi' => 'nullable|string',
                'tanggal' => 'required|date',
                'waktu' => 'nullable|string',
                'tempat' => 'nullable|string',
                'is_active' => 'nullable|boolean',
            ]);

            $agenda->update([
                'judul' => $validated['judul'],
                'deskripsi' => $validated['deskripsi'] ?? null,
                'tanggal' => Carbon::parse($validated['tanggal'])->format('Y-m-d'),
                'waktu' => $validated['waktu'] ?? null,
                'tempat' => $validated['tempat'] ?? null,
                'is_active' => $request->has('is_active') ? 1 : 0,
            ]);

            LogAktivitas::create([
                'user_id' => auth()->id(),
                'aktivitas' => 'Update Agenda',
                'deskripsi' => "Mengupdate agenda '{$agenda->judul}'",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            return redirect()->route('admin.agenda.index')
                ->with('success', 'Agenda berhasil diupdate!');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengupdate agenda: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $agenda = Agenda::findOrFail($id);
            $judul = $agenda->judul;

            $agenda->delete();

            LogAktivitas::create([
                'user_id' => auth()->id(),
                'aktivitas' => 'Hapus Agenda',
                'deskripsi' => "Menghapus agenda '{$judul}'",
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);

            // Return JSON response untuk AJAX request
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Agenda berhasil dihapus!'
                ]);
            }

            return redirect()->route('admin.agenda.index')
                ->with('success', 'Agenda berhasil dihapus!');

        } catch (\Exception $e) {
            \Log::error('Error deleting agenda: ' . $e->getMessage());
            
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus agenda: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Gagal menghapus agenda: ' . $e->getMessage());
        }
    }

    public function toggleActive($id)
    {
        try {
            $agenda = Agenda::findOrFail($id);
            $agenda->is_active = !$agenda->is_active;
            $agenda->save();

            $status = $agenda->is_active ? 'diaktifkan' : 'dinonaktifkan';

            LogAktivitas::create([
                'user_id' => auth()->id(),
                'aktivitas' => 'Toggle Agenda',
                'deskripsi' => "Agenda '{$agenda->judul}' {$status}",
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);

            return response()->json([
                'success' => true,
                'message' => "Agenda berhasil {$status}",
                'is_active' => $agenda->is_active
            ]);

        } catch (\Exception $e) {
            \Log::error('Error toggling agenda: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status: ' . $e->getMessage()
            ], 500);
        }
    }
}
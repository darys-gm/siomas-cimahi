<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class PasswordController extends Controller
{
    public function index()
    {
        return view('admin.change-password');
    }

    public function update(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        // Ambil user yang sedang login dengan cara yang lebih aman
        $user = User::find(Auth::id());

        if (!$user) {
            return back()->with('error', 'User tidak ditemukan.');
        }

        // Cek password saat ini
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini salah.']);
        }

        // Update password
        $user->password = Hash::make($request->new_password);
        
        // Simpan perubahan
        if ($user->save()) {
            // Catat aktivitas ke log
            try {
                LogAktivitas::create([
                    'user_id' => $user->id,
                    'aktivitas' => 'Ubah Password',
                    'deskripsi' => 'Admin mengubah password',
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent()
                ]);
            } catch (\Exception $e) {
                // Jika log gagal, tetap lanjutkan
            }

            return redirect()->route('admin.dashboard')->with('success', 'Password berhasil diubah.');
        }

        return back()->with('error', 'Gagal mengubah password.');
    }
}
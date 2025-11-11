<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Menampilkan daftar pengguna.
     */
    public function index(Request $request)
    {
        // Query dasar untuk mendapatkan user, kecuali user yang sedang login
        $query = User::where('id', '!=', auth()->id());

        // Logika pencarian
        if ($search = $request->get('search')) {
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'LIKE', "%{$search}%")
                  ->orWhere('username', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('role', 'LIKE', "%{$search}%");
            });
        }

        // Pagination
        $users = $query->orderBy('id', 'desc')->paginate(10);

        // Kirim data ke view
        return view('users.index', compact('users', 'search'));
    }

    /**
     * Menyimpan pengguna baru.
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nip' => 'nullable|string|max:255',
            'jabatan' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|string|in:admin,tata usaha,arsiparis,pejabat,kepegawaian',
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:8|confirmed',
            'status' => 'required|string|in:aktif,nonaktif',
        ]);

        // Simpan data ke database
        User::create([
            'nama_lengkap' => $request->nama_lengkap,
            'nip' => $request->nip,
            'jabatan' => $request->jabatan,
            'email' => $request->email,
            'role' => $request->role,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'status' => $request->status,
        ]);

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    /**
     * Memperbarui pengguna yang ada.
     */
    public function update(Request $request, User $user)
    {
        // Validasi input
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nip' => 'nullable|string|max:255',
            'jabatan' => 'nullable|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'role' => 'required|string|in:admin,tata usaha,arsiparis,pejabat,kepegawaian',
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed', // Password opsional saat update
            'status' => 'required|string|in:aktif,nonaktif',
        ]);

        // Siapkan data untuk update
        $data = $request->only(['nama_lengkap', 'nip', 'jabatan', 'email', 'role', 'username', 'status']);

        // Jika password diisi, hash dan tambahkan ke data
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Update data user
        $user->update($data);

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil diperbarui.');
    }

    /**
     * Menghapus pengguna.
     */
    public function destroy(User $user)
    {
        // Cegah penghapusan diri sendiri
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'Pengguna berhasil dihapus.');
    }

    /**
     * Mengubah status pengguna (aktif/nonaktif).
     */
    public function toggleStatus(User $user)
    {
        // Cegah perubahan status diri sendiri
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')->with('error', 'Anda tidak dapat mengubah status akun Anda sendiri.');
        }
        
        $user->status = ($user->status === 'aktif') ? 'nonaktif' : 'aktif';
        $user->save();

        // Jika permintaan AJAX, kembalikan respons JSON
        if (request()->ajax()) {
            $user->status = $user->status === 'aktif' ? 'nonaktif' : 'aktif';
            $user->save();
            return response()->json(['success' => true, 'status' => $user->status]);
        }

        return redirect()->route('users.index')->with('success', 'Status pengguna berhasil diperbarui.');
    }

    /**
     * Menduplikasi pengguna.
     */
    public function duplicate(User $user)
    {
        // Buat duplikat user
        $duplicate = $user->replicate();
        $duplicate->nama_lengkap .= ' (Copy)';
        $duplicate->username = $user->username . '_copy' . now()->timestamp; // Buat username unik
        $duplicate->status = 'nonaktif'; // Status default untuk duplikat
        $duplicate->email = 'copy_' . $user->email; // Buat email unik
        $duplicate->save();

        {
            $newUser = $user->replicate();
            $newUser->username = $user->username . '_copy';
            $newUser->email = $user->email . '_copy';
            $newUser->save();

            return response()->json(['success' => true, 'message' => 'User duplicated']);
        }

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil diduplikasi.');
    }

    
     /**
     * Helper function statis untuk membuat inisial.
     */
    public static function getInitials($name)
    {
        $words = explode(' ', $name);
        $initials = '';
        foreach (array_slice($words, 0, 2) as $word) {
            $initials .= strtoupper(substr($word, 0, 1));
        }
        return $initials ?: '?';
    }
}
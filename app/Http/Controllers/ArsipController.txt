<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ArsipAktif;
use App\Models\ArsipInaktif;
use App\Models\ArsipVital;
use App\Models\ArsipAlihmedia;
use App\Models\UserActivity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArsipController extends Controller
{
    // Fungsi untuk generate nomor arsip aktif
    public function generateNomorArsipAktif()
    {
        $user = Auth::user();
        $kodePetugas = $user->kode_petugas ?? 'RM';
        $tahun = date('Y');
        
        $count = ArsipAktif::whereYear('created_at', $tahun)->count() + 1;
        $nomorUrut = str_pad($count, 4, '0', STR_PAD_LEFT);
        
        return "{$kodePetugas}/AKTIF/{$tahun}/{$nomorUrut}";
    }
    
    // Fungsi untuk generate nomor arsip inaktif
    public function generateNomorArsipInaktif()
    {
        $user = Auth::user();
        $kodePetugas = $user->kode_petugas ?? 'RM';
        $tahun = date('Y');
        
        $count = ArsipInaktif::whereYear('created_at', $tahun)->count() + 1;
        $nomorUrut = str_pad($count, 4, '0', STR_PAD_LEFT);
        
        return "{$kodePetugas}/INAKTIF/{$tahun}/{$nomorUrut}";
    }
    
    // Fungsi untuk generate nomor arsip vital
    public function generateNomorArsipVital()
    {
        $user = Auth::user();
        $kodePetugas = $user->kode_petugas ?? 'RM';
        $tahun = date('Y');
        
        $count = ArsipVital::whereYear('created_at', $tahun)->count() + 1;
        $nomorUrut = str_pad($count, 4, '0', STR_PAD_LEFT);
        
        return "{$kodePetugas}/VITAL/{$tahun}/{$nomorUrut}";
    }
    
    // Fungsi untuk generate nomor arsip alihmedia
    public function generateNomorArsipAlihmedia()
    {
        $user = Auth::user();
        $kodePetugas = $user->kode_petugas ?? 'RM';
        $tahun = date('Y');
        
        $count = ArsipAlihmedia::whereYear('created_at', $tahun)->count() + 1;
        $nomorUrut = str_pad($count, 4, '0', STR_PAD_LEFT);
        
        return "{$kodePetugas}/ALIHMEDIA/{$tahun}/{$nomorUrut}";
    }
    
    // View untuk registrasi arsip aktif
    public function createArsipAktif()
    {
        $user = Auth::user();
        
        if (empty($user->kode_petugas)) {
            return redirect()->route('dashboard')->with('error', 'Silakan lengkapi profil terlebih dahulu sebelum registrasi arsip.');
        }
        
        $nomorArsip = $this->generateNomorArsipAktif();
        $lastEntry = ArsipAktif::orderBy('id', 'desc')->first();
        
        return view('arsip.aktif.create', compact('nomorArsip', 'lastEntry'));
    }
    
    // Simpan arsip aktif
    public function storeArsipAktif(Request $request)
    {
        $request->validate([
            'nomor_arsip' => 'required|unique:arsip_aktif,nomor_arsip',
            'kode_ka' => 'required',
            'uraian_isi' => 'required',
            'berkas' => 'required',
            'tanggal' => 'required',
            'lokasi_simpan' => 'required',
            'file' => 'nullable|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);
        
        $data = $request->except('file');
        $data['user_id'] = Auth::id();
        
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/arsip_aktif', $fileName);
            $data['file'] = $fileName;
        }
        
        ArsipAktif::create($data);
        
        // Log aktivitas
        UserActivity::create([
            'user_id' => Auth::id(),
            'activity' => 'create',
            'module' => 'arsip_aktif',
            'description' => 'User menambahkan arsip aktif dengan nomor ' . $request->nomor_arsip,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);
        
        return redirect()->route('arsip.aktif.create')->with('status', 'sukses');
    }
    
    // View untuk registrasi arsip inaktif
    public function createArsipInaktif()
    {
        $user = Auth::user();
        
        if (empty($user->kode_petugas)) {
            return redirect()->route('dashboard')->with('error', 'Silakan lengkapi profil terlebih dahulu sebelum registrasi arsip.');
        }
        
        $nomorArsip = $this->generateNomorArsipInaktif();
        $lastEntry = ArsipInaktif::orderBy('id', 'desc')->first();
        
        return view('arsip.inaktif.create', compact('nomorArsip', 'lastEntry'));
    }
    
    // Simpan arsip inaktif
    public function storeArsipInaktif(Request $request)
    {
        $request->validate([
            'nomor_arsip' => 'required|unique:arsip_inaktif,nomor_arsip',
            'kode_ka' => 'required',
            'uraian_isi' => 'required',
            'tahun' => 'required',
            'volume' => 'required',
            'keterangan' => 'required',
            'file' => 'nullable|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);
        
        $data = $request->except('file');
        $data['user_id'] = Auth::id();
        
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/arsip_inaktif', $fileName);
            $data['file'] = $fileName;
        }
        
        ArsipInaktif::create($data);
        
        // Log aktivitas
        UserActivity::create([
            'user_id' => Auth::id(),
            'activity' => 'create',
            'module' => 'arsip_inaktif',
            'description' => 'User menambahkan arsip inaktif dengan nomor ' . $request->nomor_arsip,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);
        
        return redirect()->route('arsip.inaktif.create')->with('status', 'sukses');
    }
    
    // View untuk registrasi arsip vital
    public function createArsipVital()
    {
        $user = Auth::user();
        
        if (empty($user->kode_petugas)) {
            return redirect()->route('dashboard')->with('error', 'Silakan lengkapi profil terlebih dahulu sebelum registrasi arsip.');
        }
        
        $nomorArsip = $this->generateNomorArsipVital();
        $lastEntry = ArsipVital::orderBy('id', 'desc')->first();
        
        return view('arsip.vital.create', compact('nomorArsip', 'lastEntry'));
    }
    
    // Simpan arsip vital
    public function storeArsipVital(Request $request)
    {
        $request->validate([
            'nomor_arsip' => 'required|unique:arsip_vital,nomor_arsip',
            'nama_instansi' => 'required',
            'jenis_arsip' => 'required',
            'unit_kerja' => 'required',
            'kurun_waktu' => 'required',
            'media' => 'required',
            'jumlah' => 'required',
            'jangka_simpan' => 'required',
            'lokasi_simpan' => 'required',
            'metode_perlindungan' => 'required',
            'keterangan' => 'required'
        ]);
        
        $data = $request->all();
        $data['user_id'] = Auth::id();
        
        ArsipVital::create($data);
        
        // Log aktivitas
        UserActivity::create([
            'user_id' => Auth::id(),
            'activity' => 'create',
            'module' => 'arsip_vital',
            'description' => 'User menambahkan arsip vital dengan nomor ' . $request->nomor_arsip,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);
        
        return redirect()->route('arsip.vital.create')->with('status', 'sukses');
    }
    
    // View untuk registrasi arsip alihmedia
    public function createArsipAlihmedia()
    {
        $user = Auth::user();
        
        if (empty($user->kode_petugas)) {
            return redirect()->route('dashboard')->with('error', 'Silakan lengkapi profil terlebih dahulu sebelum registrasi arsip.');
        }
        
        $nomorArsip = $this->generateNomorArsipAlihmedia();
        $lastEntry = ArsipAlihmedia::orderBy('id', 'desc')->first();
        
        return view('arsip.alihmedia.create', compact('nomorArsip', 'lastEntry'));
    }
    
    // Simpan arsip alihmedia
    public function storeArsipAlihmedia(Request $request)
    {
        $request->validate([
            'nomor_arsip' => 'required|unique:arsip_alihmedia,nomor_arsip',
            'organisasi' => 'required',
            'unit_pengolah' => 'required',
            'jenis_arsip' => 'required',
            'kurun_waktu' => 'required',
            'media_semula' => 'required',
            'media_menjadi' => 'required',
            'jumlah' => 'required',
            'alat' => 'required',
            'waktu' => 'required|date',
            'file' => 'nullable|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);
        
        $data = $request->except('file');
        $data['user_id'] = Auth::id();
        
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/arsip_alihmedia', $fileName);
            $data['file'] = $fileName;
        }
        
        ArsipAlihmedia::create($data);
        
        // Log aktivitas
        UserActivity::create([
            'user_id' => Auth::id(),
            'activity' => 'create',
            'module' => 'arsip_alihmedia',
            'description' => 'User menambahkan arsip alihmedia dengan nomor ' . $request->nomor_arsip,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);
        
        return redirect()->route('arsip.alihmedia.create')->with('status', 'sukses');
    }
    
    // API untuk cek nomor arsip aktif
    public function checkNomorArsipAktif(Request $request)
    {
        $exists = ArsipAktif::where('nomor_arsip', $request->nomor)->exists();
        return response()->json(['exists' => $exists]);
    }
    
    // API untuk cek nomor arsip inaktif
    public function checkNomorArsipInaktif(Request $request)
    {
        $exists = ArsipInaktif::where('nomor_arsip', $request->nomor)->exists();
        return response()->json(['exists' => $exists]);
    }
    
    // API untuk cek nomor arsip vital
    public function checkNomorArsipVital(Request $request)
    {
        $exists = ArsipVital::where('nomor_arsip', $request->nomor)->exists();
        return response()->json(['exists' => $exists]);
    }
    
    // API untuk cek nomor arsip alihmedia
    public function checkNomorArsipAlihmedia(Request $request)
    {
        $exists = ArsipAlihmedia::where('nomor_arsip', $request->nomor)->exists();
        return response()->json(['exists' => $exists]);
    }
}
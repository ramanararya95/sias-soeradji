<?php

// app/Http/Controllers/BeritaAcaraPemindahanController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BeritaAcaraPemindahan;

class BeritaAcaraPemindahanController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $beritaAcara = BeritaAcaraPemindahan::with('user')
            ->when($user->role !== 'admin', function ($query) use ($user) {
                return $query->where('user_id', $user->id);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('berita_acara.pemindahan.index', compact('beritaAcara'));
    }
    
    public function create()
    {
        return view('berita_acara.pemindahan.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'nomor' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'lokasi_asal' => 'required|string',
            'lokasi_tujuan' => 'required|string',
            'keterangan' => 'nullable|string',
        ]);
        
        $beritaAcara = BeritaAcaraPemindahan::create([
            'user_id' => auth()->id(),
            'nomor' => $request->nomor,
            'tanggal' => $request->tanggal,
            'lokasi_asal' => $request->lokasi_asal,
            'lokasi_tujuan' => $request->lokasi_tujuan,
            'keterangan' => $request->keterangan,
            'status' => 'draft',
        ]);
        
        return redirect()
            ->route('berita_acara.pemindahan.index')
            ->with('success', 'Berita Acara Pemindahan berhasil dibuat!');
    }
    
    public function show($id)
    {
        $beritaAcara = BeritaAcaraPemindahan::with('user')->findOrFail($id);
        
        // Check permission
        if (auth()->user()->role !== 'admin' && $beritaAcara->user_id !== auth()->id()) {
            abort(403);
        }
        
        return view('berita_acara.pemindahan.show', compact('beritaAcara'));
    }
    
    public function edit($id)
    {
        $beritaAcara = BeritaAcaraPemindahan::findOrFail($id);
        
        // Check permission
        if (auth()->user()->role !== 'admin' && $beritaAcara->user_id !== auth()->id()) {
            abort(403);
        }
        
        return view('berita_acara.pemindahan.edit', compact('beritaAcara'));
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'nomor' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'lokasi_asal' => 'required|string',
            'lokasi_tujuan' => 'required|string',
            'keterangan' => 'nullable|string',
        ]);
        
        $beritaAcara = BeritaAcaraPemindahan::findOrFail($id);
        
        // Check permission
        if (auth()->user()->role !== 'admin' && $beritaAcara->user_id !== auth()->id()) {
            abort(403);
        }
        
        $beritaAcara->update([
            'nomor' => $request->nomor,
            'tanggal' => $request->tanggal,
            'lokasi_asal' => $request->lokasi_asal,
            'lokasi_tujuan' => $request->lokasi_tujuan,
            'keterangan' => $request->keterangan,
        ]);
        
        return redirect()
            ->route('berita_acara.pemindahan.index')
            ->with('success', 'Berita Acara Pemindahan berhasil diperbarui!');
    }
    
    public function destroy($id)
    {
        $beritaAcara = BeritaAcaraPemindahan::findOrFail($id);
        
        // Check permission
        if (auth()->user()->role !== 'admin' && $beritaAcara->user_id !== auth()->id()) {
            abort(403);
        }
        
        $beritaAcara->delete();
        
        return redirect()
            ->route('berita_acara.pemindahan.index')
            ->with('success', 'Berita Acara Pemindahan berhasil dihapus!');
    }
}
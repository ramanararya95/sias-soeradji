<?php

// app/Http/Controllers/BeritaAcaraPemusnahanController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BeritaAcaraPemusnahan;

class BeritaAcaraPemusnahanController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $beritaAcara = BeritaAcaraPemusnahan::with('user')
            ->when($user->role !== 'admin', function ($query) use ($user) {
                return $query->where('user_id', $user->id);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('berita_acara.pemusnahan.index', compact('beritaAcara'));
    }
    
    public function create()
    {
        return view('berita_acara.pemusnahan.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'nomor' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'lokasi' => 'required|string',
            'keterangan' => 'nullable|string',
        ]);
        
        $beritaAcara = BeritaAcaraPemusnahan::create([
            'user_id' => auth()->id(),
            'nomor' => $request->nomor,
            'tanggal' => $request->tanggal,
            'lokasi' => $request->lokasi,
            'keterangan' => $request->keterangan,
            'status' => 'draft',
        ]);
        
        return redirect()
            ->route('berita_acara.pemusnahan.index')
            ->with('success', 'Berita Acara Pemusnahan berhasil dibuat!');
    }
    
    public function show($id)
    {
        $beritaAcara = BeritaAcaraPemusnahan::with('user')->findOrFail($id);
        
        // Check permission
        if (auth()->user()->role !== 'admin' && $beritaAcara->user_id !== auth()->id()) {
            abort(403);
        }
        
        return view('berita_acara.pemusnahan.show', compact('beritaAcara'));
    }
    
    public function edit($id)
    {
        $beritaAcara = BeritaAcaraPemusnahan::findOrFail($id);
        
        // Check permission
        if (auth()->user()->role !== 'admin' && $beritaAcara->user_id !== auth()->id()) {
            abort(403);
        }
        
        return view('berita_acara.pemusnahan.edit', compact('beritaAcara'));
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'nomor' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'lokasi' => 'required|string',
            'keterangan' => 'nullable|string',
        ]);
        
        $beritaAcara = BeritaAcaraPemusnahan::findOrFail($id);
        
        // Check permission
        if (auth()->user()->role !== 'admin' && $beritaAcara->user_id !== auth()->id()) {
            abort(403);
        }
        
        $beritaAcara->update([
            'nomor' => $request->nomor,
            'tanggal' => $request->tanggal,
            'lokasi' => $request->lokasi,
            'keterangan' => $request->keterangan,
        ]);
        
        return redirect()
            ->route('berita_acara.pemusnahan.index')
            ->with('success', 'Berita Acara Pemusnahan berhasil diperbarui!');
    }
    
    public function destroy($id)
    {
        $beritaAcara = BeritaAcaraPemusnahan::findOrFail($id);
        
        // Check permission
        if (auth()->user()->role !== 'admin' && $beritaAcara->user_id !== auth()->id()) {
            abort(403);
        }
        
        $beritaAcara->delete();
        
        return redirect()
            ->route('berita_acara.pemusnahan.index')
            ->with('success', 'Berita Acara Pemusnahan berhasil dihapus!');
    }
}
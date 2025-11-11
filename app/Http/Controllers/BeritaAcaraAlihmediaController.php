<?php

// app/Http/Controllers/BeritaAcaraAlihmediaController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BeritaAcaraAlihmedia;

class BeritaAcaraAlihmediaController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $beritaAcara = BeritaAcaraAlihmedia::with('user')
            ->when($user->role !== 'admin', function ($query) use ($user) {
                return $query->where('user_id', $user->id);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('berita_acara.alihmedia.index', compact('beritaAcara'));
    }
    
    public function create()
    {
        return view('berita_acara.alihmedia.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'nomor' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'media_asal' => 'required|string',
            'media_tujuan' => 'required|string',
            'keterangan' => 'nullable|string',
        ]);
        
        $beritaAcara = BeritaAcaraAlihmedia::create([
            'user_id' => auth()->id(),
            'nomor' => $request->nomor,
            'tanggal' => $request->tanggal,
            'media_asal' => $request->media_asal,
            'media_tujuan' => $request->media_tujuan,
            'keterangan' => $request->keterangan,
            'status' => 'draft',
        ]);
        
        return redirect()
            ->route('berita_acara.alihmedia.index')
            ->with('success', 'Berita Acara Alih Media berhasil dibuat!');
    }
    
    public function show($id)
    {
        $beritaAcara = BeritaAcaraAlihmedia::with('user')->findOrFail($id);
        
        // Check permission
        if (auth()->user()->role !== 'admin' && $beritaAcara->user_id !== auth()->id()) {
            abort(403);
        }
        
        return view('berita_acara.alihmedia.show', compact('beritaAcara'));
    }
    
    public function edit($id)
    {
        $beritaAcara = BeritaAcaraAlihmedia::findOrFail($id);
        
        // Check permission
        if (auth()->user()->role !== 'admin' && $beritaAcara->user_id !== auth()->id()) {
            abort(403);
        }
        
        return view('berita_acara.alihmedia.edit', compact('beritaAcara'));
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'nomor' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'media_asal' => 'required|string',
            'media_tujuan' => 'required|string',
            'keterangan' => 'nullable|string',
        ]);
        
        $beritaAcara = BeritaAcaraAlihmedia::findOrFail($id);
        
        // Check permission
        if (auth()->user()->role !== 'admin' && $beritaAcara->user_id !== auth()->id()) {
            abort(403);
        }
        
        $beritaAcara->update([
            'nomor' => $request->nomor,
            'tanggal' => $request->tanggal,
            'media_asal' => $request->media_asal,
            'media_tujuan' => $request->media_tujuan,
            'keterangan' => $request->keterangan,
        ]);
        
        return redirect()
            ->route('berita_acara.alihmedia.index')
            ->with('success', 'Berita Acara Alih Media berhasil diperbarui!');
    }
    
    public function destroy($id)
    {
        $beritaAcara = BeritaAcaraAlihmedia::findOrFail($id);
        
        // Check permission
        if (auth()->user()->role !== 'admin' && $beritaAcara->user_id !== auth()->id()) {
            abort(403);
        }
        
        $beritaAcara->delete();
        
        return redirect()
            ->route('berita_acara.alihmedia.index')
            ->with('success', 'Berita Acara Alih Media berhasil dihapus!');
    }
}
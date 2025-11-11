@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-xl mx-auto">
        <div class="bg-white p-6 rounded shadow-md">
            <h2 class="text-lg font-semibold text-slate-700 mb-4">
                <i class="fas fa-cogs text-blue-600 mr-1"></i> Pengaturan Nomor Arsip
            </h2>

            @if(session('success'))
                <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.pengaturan.nomor.update') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">Panjang Nomor Urut</label>
                    <input type="number" name="panjang" value="{{ $pengaturan->panjang_nomor_urut }}" min="2" max="10" required
                           class="w-32 px-3 py-2 border border-slate-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
                    @error('panjang')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                <div class="pt-2">
                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow">
                        <i class="fas fa-save mr-2"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
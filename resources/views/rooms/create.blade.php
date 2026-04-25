@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 600px;">
    <div style="margin: 2rem 0;">
        <a href="{{ route('rooms.index') }}" style="color: #94a3b8; text-decoration: none;">← Kembali</a>
    </div>

    <div class="card">
        <h2 style="font-family: 'Space Grotesk', sans-serif; margin-bottom: 1.5rem;">🎵 Buat Focus Room</h2>

        <form action="{{ route('rooms.store') }}" method="POST">
            @csrf

            <div style="margin-bottom: 1.2rem;">
                <label style="display: block; color: #94a3b8; font-size: 0.85rem; margin-bottom: 0.5rem;">NAMA ROOM</label>
                <input 
                    type="text" 
                    name="name" 
                    placeholder="cth: Study with me 📚"
                    value="{{ old('name') }}"
                    style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.15); color: white; padding: 0.75rem 1rem; border-radius: 10px; font-size: 1rem; outline: none;"
                >
                @error('name')
                    <div style="color: #f87171; font-size: 0.8rem; margin-top: 0.3rem;">{{ $message }}</div>
                @enderror
            </div>

            <div style="margin-bottom: 1.2rem;">
                <label style="display: block; color: #94a3b8; font-size: 0.85rem; margin-bottom: 0.5rem;">TIPE MUSIK</label>
                <select 
                    name="music_type"
                    style="width: 100%; background: #1a1a2e; border: 1px solid rgba(255,255,255,0.15); color: white; padding: 0.75rem 1rem; border-radius: 10px; font-size: 1rem; outline: none;"
                >
                    <option value="lofi">🎵 Lo-fi Hip Hop</option>
                    <option value="jazz">🎷 Jazz</option>
                    <option value="nature">🌿 Nature Sounds</option>
                </select>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; color: #94a3b8; font-size: 0.85rem; margin-bottom: 0.5rem;">YOUTUBE URL (opsional)</label>
                <input 
                    type="url" 
                    name="youtube_url" 
                    placeholder="https://www.youtube.com/watch?v=..."
                    value="{{ old('youtube_url') }}"
                    style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.15); color: white; padding: 0.75rem 1rem; border-radius: 10px; font-size: 1rem; outline: none;"
                >
                @error('youtube_url')
                    <div style="color: #f87171; font-size: 0.8rem; margin-top: 0.3rem;">{{ $message }}</div>
                @enderror
                <div style="color: #64748b; font-size: 0.8rem; margin-top: 0.3rem;">
                    💡 Tip: Cari "lofi hip hop" di YouTube, copy URL-nya
                </div>
            </div>

            <button type="submit" class="btn-primary" style="width: 100%; padding: 0.9rem; font-size: 1rem;">
                ✨ Buat Room
            </button>
        </form>
    </div>
</div>
@endsection
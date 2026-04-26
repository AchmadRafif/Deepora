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
                    style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.15); color: white; padding: 0.75rem 1rem; border-radius: 10px; font-size: 1rem; outline: none;">
                @error('name')
                <div style="color: #f87171; font-size: 0.8rem; margin-top: 0.3rem;">{{ $message }}</div>
                @enderror
            </div>

            <div style="margin-bottom: 1.2rem;">
                <label style="display: block; color: #94a3b8; font-size: 0.85rem; margin-bottom: 0.5rem;">TIPE MUSIK</label>
                <select
                    name="music_type"
                    id="music_type"
                    onchange="updatePlaylist()"
                    style="width: 100%; background: #1a1a2e; border: 1px solid rgba(255,255,255,0.15); color: white; padding: 0.75rem 1rem; border-radius: 10px; font-size: 1rem; outline: none;">
                    <option value="lofi">🎵 Lo-fi Hip Hop</option>
                    <option value="jazz">🎷 Jazz</option>
                    <option value="nature">🌿 Nature Sounds</option>
                    <option value="classical">🎻 Classical</option>
                    <option value="custom">🔗 Custom URL</option>
                </select>
            </div>

            {{-- Preview playlist --}}
            <div id="playlist-preview" style="margin-bottom: 1.2rem; background: rgba(124,58,237,0.1); border: 1px solid rgba(124,58,237,0.3); border-radius: 10px; padding: 1rem;">
                <div style="font-size: 0.8rem; color: #94a3b8; margin-bottom: 0.5rem;">PLAYLIST YANG AKAN DIPUTAR:</div>
                <div id="playlist-name" style="font-weight: 600; color: #a78bfa;">🎵 Lofi Hip Hop Radio - Beats to Relax/Study</div>
            </div>

            {{-- Custom URL (hidden by default) --}}
            <div id="custom-url-wrapper" style="display: none; margin-bottom: 1.2rem;">
                <label style="display: block; color: #94a3b8; font-size: 0.85rem; margin-bottom: 0.5rem;">YOUTUBE URL</label>
                <input
                    type="text"
                    name="youtube_url"
                    id="youtube_url"
                    placeholder="https://www.youtube.com/watch?v=..."
                    value="{{ old('youtube_url') }}"
                    style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.15); color: white; padding: 0.75rem 1rem; border-radius: 10px; font-size: 1rem; outline: none;">
                @error('youtube_url')
                <div style="color: #f87171; font-size: 0.8rem; margin-top: 0.3rem;">{{ $message }}</div>
                @enderror
            </div>

            {{-- Hidden field untuk URL playlist bawaan --}}
            <input type="hidden" name="preset_url" id="preset_url" value="https://www.youtube.com/watch?v=jfKfPfyJRdk">

            <button type="submit" class="btn-primary" style="width: 100%; padding: 0.9rem; font-size: 1rem;">
                ✨ Buat Room
            </button>
        </form>
    </div>
</div>

<script>
    var playlists = {
        'lofi': {
            name: '🎵 Lofi Hip Hop Radio - Beats to Relax/Study',
            url: 'https://www.youtube.com/watch?v=jfKfPfyJRdk'
        },
        'jazz': {
            name: '🎷 Jazz Cafe - Relaxing Jazz Music',
            url: 'https://www.youtube.com/watch?v=Dx5qFachd3A'
        },
        'nature': {
            name: '🌿 Relaxing Nature Sounds - Forest & Rain',
            url: 'https://www.youtube.com/watch?v=yajJ_QVIKwU'
        },
        'classical': {
            name: '🎻 Classical Music for Studying - Mozart',
            url: 'https://www.youtube.com/watch?v=mGQLXRTl3Z0'
        },
        'custom': {
            name: '🔗 Custom URL',
            url: ''
        }
    };

    function updatePlaylist() {
        var type = document.getElementById('music_type').value;
        var playlist = playlists[type];
        var customWrapper = document.getElementById('custom-url-wrapper');
        var presetUrl = document.getElementById('preset_url');
        var playlistName = document.getElementById('playlist-name');
        var previewBox = document.getElementById('playlist-preview');

        if (type === 'custom') {
            customWrapper.style.display = 'block';
            previewBox.style.display = 'none';
            presetUrl.value = '';
        } else {
            customWrapper.style.display = 'none';
            previewBox.style.display = 'block';
            presetUrl.value = playlist.url;
            playlistName.textContent = playlist.name;
        }
    }
</script>
@endsection
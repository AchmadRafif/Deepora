@extends('layouts.app')

@section('content')
<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin: 2rem 0;">
        <h1 style="font-family: 'Space Grotesk', sans-serif; font-size: 1.8rem;">🎵 Focus Rooms</h1>
        <a href="{{ route('rooms.create') }}" class="btn-primary">+ Buat Room</a>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem;">
        @forelse($rooms as $room)
        <div class="card" style="position: relative; overflow: hidden;">
            <div style="position: absolute; top: 0; left: 0; right: 0; height: 3px; background: linear-gradient(135deg, #7C3AED, #EC4899);"></div>
            <div style="font-size: 0.75rem; color: #7C3AED; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.5rem;">
                {{ $room->music_type }}
            </div>
            <div style="font-size: 1.2rem; font-weight: 600; margin-bottom: 0.25rem;">{{ $room->name }}</div>
            <div style="color: #94a3b8; font-size: 0.85rem; margin-bottom: 1rem;">oleh {{ $room->creator->name }}</div>
            <a href="{{ route('rooms.show', $room) }}" class="btn-primary" style="font-size: 0.85rem; padding: 0.5rem 1.2rem;">Join Room →</a>
        </div>
        @empty
        <div style="grid-column: 1/-1; text-align: center; padding: 3rem; color: #94a3b8;">
            <div style="font-size: 3rem; margin-bottom: 1rem;">🎵</div>
            <p>Belum ada room. Jadilah yang pertama!</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
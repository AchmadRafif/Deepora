@extends('layouts.app')

@section('content')
<div class="container">
    <div style="margin: 2rem 0;">
        <a href="{{ route('rooms.index') }}" style="color: #94a3b8; text-decoration: none;">← Kembali</a>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 400px; gap: 2rem;">
        <!-- Left: Music Player + Timer -->
        <div>
            <div class="card" style="margin-bottom: 1.5rem;">
                <h2 style="margin-bottom: 1.5rem; font-family: 'Space Grotesk', sans-serif;">🎵 {{ $room->name }}</h2>

                <!-- YouTube Embed -->
                @if($room->youtube_url)
                <div style="position: relative; padding-bottom: 56.25%; margin-bottom: 1.5rem; border-radius: 12px; overflow: hidden;">
                    @php
                    $url = $room->youtube_url;
                    // Handle semua format URL YouTube
                    preg_match('/(?:youtube\.be\/|youtube\.com\/(?:watch\?v=|embed\/|v\/))([a-zA-Z0-9_-]{11})/', $url, $matches);
                    $videoId = $matches[1] ?? null;
                    @endphp

                    @if($videoId)
                    <iframe
                        src="https://www.youtube.com/embed/{{ $videoId }}?autoplay=1&mute=1"
                        style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;"
                        frameborder="0"
                        allow="autoplay; encrypted-media"
                        allowfullscreen>
                    </iframe>
                    @else
                    <div style="background: rgba(124,58,237,0.1); border-radius: 12px; padding: 2rem; text-align: center; color: #94a3b8;">
                        ⚠️ URL YouTube tidak valid — {{ $url }}
                    </div>
                    @endif
                </div>
                @else
                <div style="background: rgba(124,58,237,0.1); border-radius: 12px; padding: 2rem; text-align: center; margin-bottom: 1.5rem; color: #94a3b8;">
                    🎵 Lo-fi vibes playing...
                </div>
                @endif
            </div>

            <!-- Timer in Room -->
            <div class="card" style="text-align: center; padding: 2rem;">
                <div id="room-timer" style="font-size: 5rem; font-weight: 700; font-family: 'Space Grotesk', sans-serif; background: linear-gradient(135deg, #7C3AED, #EC4899); background-clip: text; -webkit-text-fill-color: transparent;">
                    25:00
                </div>
                <div style="display: flex; gap: 1rem; justify-content: center; margin-top: 1.5rem;">
                    <button onclick="startRoomTimer()" class="btn-primary">▶ Focus!</button>
                    <button onclick="pauseRoomTimer()" style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: white; padding: 0.6rem 1.5rem; border-radius: 50px; cursor: pointer;">⏸</button>
                </div>
                <div id="room-xp-notif" style="display:none; margin-top: 1rem; color: #10b981; font-weight: 600;"></div>
            </div>
        </div>

        <!-- Right: Room Info -->
        <div>
            <div class="card">
                <div style="font-size: 0.85rem; color: #94a3b8; margin-bottom: 1rem; text-transform: uppercase; letter-spacing: 1px;">Info Room</div>
                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: #94a3b8;">Tipe Musik</span>
                        <span style="text-transform: capitalize;">{{ $room->music_type }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: #94a3b8;">Creator</span>
                        <span>{{ $room->creator->name }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@php
    $url = trim($room->youtube_url ?? '');
    $videoId = null;
    
    // Coba ambil langsung pakai parse_url
    $parsed = parse_url($url);
    $host = $parsed['host'] ?? '';
    $path = $parsed['path'] ?? '';
    
    if (str_contains($host, 'youtu.be')) {
        // youtu.be/VIDEO_ID
        $videoId = trim($path, '/');
        $videoId = substr($videoId, 0, 11);
    } elseif (str_contains($host, 'youtube.com')) {
        if (str_contains($url, 'watch?v=')) {
            parse_str($parsed['query'] ?? '', $query);
            $videoId = $query['v'] ?? null;
        } elseif (str_contains($path, 'embed/')) {
            $videoId = substr(explode('embed/', $path)[1], 0, 11);
        }
    }
@endphp

<script>
    var roomSeconds = 25 * 60;
    var roomInterval = null;
    var roomRunning = false;
    var roomId = parseInt("{{ $room->id }}");
    var pomodoroRoute = "{{ route('pomodoro.complete') }}";
    var csrfToken = "{{ csrf_token() }}";

    function updateRoomTimer() {
        var m = Math.floor(roomSeconds / 60);
        var s = roomSeconds % 60;
        document.getElementById('room-timer').textContent =
            String(m).padStart(2, '0') + ':' + String(s).padStart(2, '0');
    }

    function startRoomTimer() {
        if (roomRunning) return;
        roomRunning = true;
        roomInterval = setInterval(function() {
            roomSeconds--;
            updateRoomTimer();
            if (roomSeconds <= 0) {
                clearInterval(roomInterval);
                roomRunning = false;
                completeRoomSession();
            }
        }, 1000);
    }

    function pauseRoomTimer() {
        clearInterval(roomInterval);
        roomRunning = false;
    }

    function completeRoomSession() {
        fetch(pomodoroRoute, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    duration: 25,
                    room_id: roomId
                })
            })
            .then(function(r) {
                return r.json();
            })
            .then(function(data) {
                var n = document.getElementById('room-xp-notif');
                n.textContent = '🎉 +' + data.xp_earned + ' XP! ' + data.badge;
                n.style.display = 'block';
            });
    }
</script>
@endsection
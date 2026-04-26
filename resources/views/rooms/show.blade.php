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
        </div> {{-- Tutup kolom KIRI --}}

        {{-- KANAN: Info Room + Chat --}}
        <div>
            {{-- Info Room --}}
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

            <!-- Chat Box -->
            <div class="card" style="margin-top: 1.5rem; display: flex; flex-direction: column; height: 400px;">
                <div style="font-size: 0.85rem; color: #94a3b8; margin-bottom: 1rem; text-transform: uppercase; letter-spacing: 1px;">
                    Room Chat
                </div>

                {{-- Messages --}}
                <div id="chat-messages" style="flex: 1; overflow-y: auto; display: flex; flex-direction: column; gap: 0.75rem; margin-bottom: 1rem; padding-right: 0.25rem;">
                    <div style="text-align: center; color: #64748b; font-size: 0.8rem;">Memuat chat...</div>
                </div>

                {{-- Input --}}
                <div style="display: flex; gap: 0.5rem;">
                    <input
                        type="text"
                        id="chat-input"
                        placeholder="Ketik pesan..."
                        maxlength="200"
                        onkeydown="if(event.key==='Enter') sendChat()"
                        style="flex: 1; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.15); color: white; padding: 0.6rem 1rem; border-radius: 8px; outline: none; font-size: 0.9rem;">
                    <button onclick="sendChat()" class="btn-primary" style="padding: 0.6rem 1rem; font-size: 0.85rem;">
                        Kirim
                    </button>
                </div>
            </div>

        </div> {{-- Tutup kolom KANAN --}}
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
        var roomId = parseInt("{{ $room->id }}");
        var pomodoroRoute = "{{ route('pomodoro.complete') }}";
        var csrfToken = "{{ csrf_token() }}";

        // Key localStorage pakai roomId biar tiap room beda
        var KEY_END = 'deepora_room_' + roomId + '_end';
        var KEY_SECONDS = 'deepora_room_' + roomId + '_seconds';
        var KEY_RUNNING = 'deepora_room_' + roomId + '_running';

        var savedEnd = localStorage.getItem(KEY_END);
        var savedRunning = localStorage.getItem(KEY_RUNNING);
        var isRunning = savedRunning === 'true';

        function getRemainingSeconds() {
            if (!savedEnd) return 25 * 60;
            var end = parseInt(savedEnd);
            var now = Math.floor(Date.now() / 1000);
            var remaining = end - now;
            return remaining > 0 ? remaining : 0;
        }

        var roomSeconds = isRunning ? getRemainingSeconds() :
            (localStorage.getItem(KEY_SECONDS) ?
                parseInt(localStorage.getItem(KEY_SECONDS)) :
                25 * 60);

        var roomInterval = null;

        function updateRoomTimer() {
            var m = Math.floor(roomSeconds / 60);
            var s = roomSeconds % 60;
            document.getElementById('room-timer').textContent =
                String(m).padStart(2, '0') + ':' + String(s).padStart(2, '0');
        }

        function startRoomTimer() {
            if (isRunning) return;

            var endTime = Math.floor(Date.now() / 1000) + roomSeconds;
            localStorage.setItem(KEY_END, endTime);
            localStorage.setItem(KEY_RUNNING, 'true');

            isRunning = true;
            roomInterval = setInterval(tick, 1000);
        }

        function tick() {
            roomSeconds--;
            localStorage.setItem(KEY_SECONDS, roomSeconds);
            updateRoomTimer();

            if (roomSeconds <= 0) {
                clearInterval(roomInterval);
                isRunning = false;
                localStorage.removeItem(KEY_END);
                localStorage.removeItem(KEY_RUNNING);
                localStorage.removeItem(KEY_SECONDS);
                completeRoomSession();
            }
        }

        function pauseRoomTimer() {
            clearInterval(roomInterval);
            isRunning = false;
            localStorage.setItem(KEY_RUNNING, 'false');
            localStorage.setItem(KEY_SECONDS, roomSeconds);
            localStorage.removeItem(KEY_END);
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

        // Auto-resume kalau timer lagi jalan
        updateRoomTimer();
        if (isRunning) {
            if (roomSeconds <= 0) {
                roomSeconds = 0;
                updateRoomTimer();
                completeRoomSession();
            } else {
                roomInterval = setInterval(tick, 1000);
            }
        }

        // ===== CHAT =====
        var chatRoomId = roomId;
        var chatRoute = '/rooms/' + chatRoomId + '/chat';
        var currentUserId = parseInt("{{ $room->created_by }}"); // sementara pakai created_by
        var lastChatId = 0;

        var emojiMapChat = {
            'electric': '⚡',
            'fire': '🔥',
            'nature': '🌿',
            'diamond': '💎',
            'galaxy': '🌌',
            'crown': '👑'
        };

        function getAvatar(user) {
            var emojiMap = {
                'electric': '⚡',
                'fire': '🔥',
                'nature': '🌿',
                'diamond': '💎',
                'galaxy': '🌌',
                'crown': '👑'
            };
            var borderMap = {
                'default': 'none',
                'electric': '3px solid #f59e0b',
                'fire': '3px solid #ef4444',
                'nature': '3px solid #10b981',
                'diamond': '3px solid #06b6d4',
                'galaxy': '3px solid #8b5cf6',
                'crown': '3px solid #FFD700'
            };
            var glowMap = {
                'diamond': '0 0 12px rgba(6,182,212,0.6)',
                'galaxy': '0 0 12px rgba(139,92,246,0.6)',
                'crown': '0 0 15px rgba(255,215,0,0.7)'
            };

            var style = user.avatar_style || 'default';
            var emoji = emojiMap[style] || user.name.charAt(0).toUpperCase();
            var border = borderMap[style] || 'none';
            var glow = glowMap[style] || 'none';

            return '<div style="width:28px;height:28px;border-radius:50%;background:' +
                (user.avatar_color || '#7C3AED') +
                ';display:flex;align-items:center;justify-content:center;font-size:0.75rem;font-weight:700;flex-shrink:0;border:' +
                border + ';box-shadow:' + glow + ';">' +
                emoji + '</div>';
        }

        function loadChats() {
            fetch(chatRoute, {
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(function(r) {
                    return r.json();
                })
                .then(function(chats) {
                    var container = document.getElementById('chat-messages');

                    if (chats.length === 0) {
                        container.innerHTML = '<div style="text-align: center; color: #64748b; font-size: 0.8rem; margin-top: 2rem;">Belum ada chat. Mulai obrolan! 👋</div>';
                        return;
                    }

                    var html = '';
                    chats.forEach(function(chat) {
                        var isMe = chat.user_id === parseInt("{{ auth()->id() }}");
                        var time = new Date(chat.created_at).toLocaleTimeString('id-ID', {
                            hour: '2-digit',
                            minute: '2-digit'
                        });

                        if (isMe) {
                            html += '<div style="display: flex; flex-direction: column; align-items: flex-end; gap: 0.2rem;">' +
                                '<div style="font-size: 0.7rem; color: #64748b;">' + time + '</div>' +
                                '<div style="background: linear-gradient(135deg, #7C3AED, #EC4899); color: white; padding: 0.5rem 0.9rem; border-radius: 16px 16px 4px 16px; max-width: 85%; font-size: 0.88rem; word-break: break-word;">' +
                                escapeHtml(chat.message) + '</div>' +
                                '</div>';
                        } else {
                            html += '<div style="display: flex; gap: 0.5rem; align-items: flex-end;">' +
                                getAvatar(chat.user) +
                                '<div>' +
                                '<div style="font-size: 0.7rem; color: #94a3b8; margin-bottom: 0.2rem;">' + escapeHtml(chat.user.name) + ' · ' + time + '</div>' +
                                '<div style="background: rgba(255,255,255,0.08); color: #e2e8f0; padding: 0.5rem 0.9rem; border-radius: 16px 16px 16px 4px; max-width: 85%; font-size: 0.88rem; word-break: break-word;">' +
                                escapeHtml(chat.message) + '</div>' +
                                '</div>' +
                                '</div>';
                        }
                    });

                    container.innerHTML = html;
                    container.scrollTop = container.scrollHeight;
                });
        }

        function sendChat() {
            var input = document.getElementById('chat-input');
            var message = input.value.trim();
            if (!message) return;

            input.value = '';

            fetch(chatRoute, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        message: message
                    })
                })
                .then(function(r) {
                    return r.json();
                })
                .then(function() {
                    loadChats();
                });
        }

        function escapeHtml(text) {
            var div = document.createElement('div');
            div.appendChild(document.createTextNode(text));
            return div.innerHTML;
        }

        // Load chat pertama kali
        loadChats();

        // Polling setiap 3 detik
        setInterval(loadChats, 3000);
    </script>
    @endsection
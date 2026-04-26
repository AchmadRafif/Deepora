@extends('layouts.app')

@section('content')
<div class="container">
    <!-- User Stats -->
    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem; margin-top: 2rem;">
        <div class="card" style="text-align: center;">
            <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">
                @php $freshUser = auth()->user()->fresh(); @endphp
                <div style="width: 60px; height: 60px; border-radius: 50%; background: {{ $freshUser->avatar_color }}; margin: 0 auto 0.5rem; display: flex; align-items: center; justify-content: center; font-size: {{ $freshUser->avatar_style !== 'default' ? '1.5rem' : '1.3rem' }}; font-weight: 700;">
                    @php
                    $emojiMap = ['electric'=>'⚡','fire'=>'🔥','nature'=>'🌿','diamond'=>'💎','galaxy'=>'🌌','crown'=>'👑'];
                    @endphp
                    {{ $emojiMap[$freshUser->avatar_style] ?? strtoupper(substr($freshUser->name, 0, 1)) }}
                </div>
            </div>
            <div style="font-size: 1.2rem; font-weight: 600;">{{ auth()->user()->name }}</div>
            <div style="color: #94a3b8; font-size: 0.85rem;">{{ auth()->user()->badge ?? '🌱 Newbie' }}</div>
        </div>

        <div class="card" style="text-align: center;">
            <div style="font-size: 0.85rem; color: #94a3b8; margin-bottom: 0.5rem;">TOTAL XP</div>
            <div style="font-size: 2.5rem; font-weight: 700; background: linear-gradient(135deg, #7C3AED, #EC4899); background-clip: text; -webkit-text-fill-color: transparent;">
                {{ auth()->user()->xp }}
            </div>
            <div style="color: #94a3b8; font-size: 0.85rem;">Level {{ auth()->user()->level }}</div>
        </div>

        <div class="card" style="text-align: center;">
            <div style="font-size: 0.85rem; color: #94a3b8; margin-bottom: 0.5rem;">SESI MINGGU INI</div>
            <div style="font-size: 2.5rem; font-weight: 700; color: #10b981;">
                {{ auth()->user()->totalSessionsThisWeek() }}
            </div>
            <div style="color: #94a3b8; font-size: 0.85rem;">sesi selesai</div>
        </div>
    </div>

    <!-- Pomodoro Timer -->
    <div class="card" style="text-align: center; padding: 3rem; margin-bottom: 2rem;">
        <h2 style="font-family: 'Space Grotesk', sans-serif; margin-bottom: 0.5rem; font-size: 1.5rem;">⏱ Pomodoro Timer</h2>

        {{-- Session Counter --}}
        <div style="display: flex; justify-content: center; gap: 0.4rem; margin-bottom: 1.5rem;">
            <div id="session-1" style="width: 12px; height: 12px; border-radius: 50%; background: rgba(255,255,255,0.2);"></div>
            <div id="session-2" style="width: 12px; height: 12px; border-radius: 50%; background: rgba(255,255,255,0.2);"></div>
            <div id="session-3" style="width: 12px; height: 12px; border-radius: 50%; background: rgba(255,255,255,0.2);"></div>
            <div id="session-4" style="width: 12px; height: 12px; border-radius: 50%; background: rgba(255,255,255,0.2);"></div>
        </div>

        {{-- Mode Label --}}
        <div id="mode-label" style="font-size: 0.85rem; color: #94a3b8; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 0.5rem;">
            🎯 Focus
        </div>

        {{-- Timer Display --}}
        <div id="timer-display" style="font-size: 6rem; font-weight: 700; font-family: 'Space Grotesk', sans-serif; background: linear-gradient(135deg, #7C3AED, #EC4899); background-clip: text; -webkit-text-fill-color: transparent; line-height: 1; margin-bottom: 1.5rem;">
            25:00
        </div>

        {{-- Duration Selector --}}
        <div style="margin-bottom: 1.5rem;">
            <label style="color: #94a3b8; font-size: 0.85rem;">DURASI FOKUS:</label>
            <select id="timer-duration" style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: white; padding: 0.4rem 1rem; border-radius: 8px; margin-left: 0.5rem;">
                <option value="25">25 menit (Pomodoro)</option>
                <option value="50">50 menit (Deep Work)</option>
                <option value="15">15 menit (Short)</option>
            </select>
        </div>

        {{-- Buttons --}}
        <div style="display: flex; gap: 1rem; justify-content: center;">
            <button onclick="startTimer()" class="btn-primary" style="font-size: 1rem; padding: 0.8rem 2.5rem;">▶ Mulai</button>
            <button onclick="pauseTimer()" style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: white; padding: 0.8rem 2.5rem; border-radius: 50px; cursor: pointer; font-size: 1rem;">⏸ Pause</button>
            <button onclick="resetTimer()" style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: white; padding: 0.8rem 2rem; border-radius: 50px; cursor: pointer; font-size: 1rem;">↺ Reset</button>
        </div>

        <div id="xp-notification" style="display:none; margin-top: 1.5rem; padding: 1rem; background: rgba(16,185,129,0.2); border: 1px solid #10b981; border-radius: 12px; color: #10b981; font-weight: 600;"></div>
    </div>

    <!-- Quick Links -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
        <a href="{{ route('rooms.index') }}" class="card" style="text-decoration: none; color: inherit; display: block; transition: transform 0.2s;">
            <div style="font-size: 2rem; margin-bottom: 0.5rem;">🎵</div>
            <div style="font-size: 1.1rem; font-weight: 600; margin-bottom: 0.25rem;">Focus Rooms</div>
            <div style="color: #94a3b8; font-size: 0.9rem;">Belajar bareng temen sambil dengerin lo-fi</div>
        </a>

        <a href="{{ route('leaderboard') }}" class="card" style="text-decoration: none; color: inherit; display: block; transition: transform 0.2s;">
            <div style="font-size: 2rem; margin-bottom: 0.5rem;">🏆</div>
            <div style="font-size: 1.1rem; font-weight: 600; margin-bottom: 0.25rem;">Leaderboard</div>
            <div style="color: #94a3b8; font-size: 0.9rem;">Lihat siapa yang paling produktif minggu ini</div>
        </a>
    </div>
</div>

<script>
    // ===== STATE =====
    var FOCUS_DURATION = parseInt(localStorage.getItem('deepora_focus_duration') || '25');
    var BREAK_DURATION = 5;
    var LONG_BREAK_DURATION = 15;
    var MAX_SESSIONS = 4;

    var mode = localStorage.getItem('deepora_mode') || 'focus'; // 'focus' | 'break' | 'longbreak'
    var sessionCount = parseInt(localStorage.getItem('deepora_session_count') || '0');
    var isRunning = localStorage.getItem('deepora_timer_running') === 'true';
    var savedEnd = localStorage.getItem('deepora_timer_end');
    var timerInterval = null;

    function getDefaultSeconds() {
        if (mode === 'focus') return FOCUS_DURATION * 60;
        if (mode === 'break') return BREAK_DURATION * 60;
        return LONG_BREAK_DURATION * 60;
    }

    var totalSeconds = isRunning ?
        Math.max(0, parseInt(savedEnd || '0') - Math.floor(Date.now() / 1000)) :
        parseInt(localStorage.getItem('deepora_timer_seconds') || getDefaultSeconds());

    // ===== UI UPDATE =====
    function updateDisplay() {
        var m = Math.floor(totalSeconds / 60);
        var s = totalSeconds % 60;
        document.getElementById('timer-display').textContent =
            String(m).padStart(2, '0') + ':' + String(s).padStart(2, '0');
    }

    function updateModeLabel() {
        var label = document.getElementById('mode-label');
        var display = document.getElementById('timer-display');

        if (mode === 'focus') {
            label.textContent = '🎯 Focus';
            display.style.background = 'linear-gradient(135deg, #7C3AED, #EC4899)';
        } else if (mode === 'break') {
            label.textContent = '☕ Break';
            display.style.background = 'linear-gradient(135deg, #10b981, #06b6d4)';
        } else {
            label.textContent = '🛋 Long Break';
            display.style.background = 'linear-gradient(135deg, #3b82f6, #8b5cf6)';
        }
        display.style.webkitBackgroundClip = 'text';
        display.style.webkitTextFillColor = 'transparent';
    }

    function updateSessionDots() {
        for (var i = 1; i <= 4; i++) {
            var dot = document.getElementById('session-' + i);
            if (i <= sessionCount) {
                dot.style.background = 'linear-gradient(135deg, #7C3AED, #EC4899)';
            } else {
                dot.style.background = 'rgba(255,255,255,0.2)';
            }
        }
    }

    // ===== TIMER LOGIC =====
    function startTimer() {
        if (isRunning) return;

        FOCUS_DURATION = parseInt(document.getElementById('timer-duration').value);
        localStorage.setItem('deepora_focus_duration', FOCUS_DURATION);

        var endTime = Math.floor(Date.now() / 1000) + totalSeconds;
        localStorage.setItem('deepora_timer_end', endTime);
        localStorage.setItem('deepora_timer_running', 'true');
        localStorage.setItem('deepora_mode', mode);

        isRunning = true;
        timerInterval = setInterval(tick, 1000);
    }

    function tick() {
        totalSeconds--;
        localStorage.setItem('deepora_timer_seconds', totalSeconds);
        updateDisplay();

        if (totalSeconds <= 0) {
            clearInterval(timerInterval);
            isRunning = false;
            localStorage.setItem('deepora_timer_running', 'false');
            localStorage.removeItem('deepora_timer_end');
            onTimerComplete();
        }
    }

    function pauseTimer() {
        clearInterval(timerInterval);
        isRunning = false;
        localStorage.setItem('deepora_timer_running', 'false');
        localStorage.setItem('deepora_timer_seconds', totalSeconds);
        localStorage.removeItem('deepora_timer_end');
    }

    function resetTimer() {
        clearInterval(timerInterval);
        isRunning = false;
        mode = 'focus';
        sessionCount = 0;
        FOCUS_DURATION = parseInt(document.getElementById('timer-duration').value);
        totalSeconds = FOCUS_DURATION * 60;

        localStorage.removeItem('deepora_timer_end');
        localStorage.removeItem('deepora_timer_running');
        localStorage.removeItem('deepora_timer_seconds');
        localStorage.removeItem('deepora_mode');
        localStorage.setItem('deepora_session_count', '0');

        updateDisplay();
        updateModeLabel();
        updateSessionDots();
    }

    function onTimerComplete() {
        if (mode === 'focus') {
            // Selesai fokus — tambah session, kasih XP
            sessionCount++;
            if (sessionCount > MAX_SESSIONS) sessionCount = 0;
            localStorage.setItem('deepora_session_count', sessionCount);
            updateSessionDots();

            // Kirim XP ke server
            completeSession();

            // Switch ke break
            if (sessionCount % MAX_SESSIONS === 0) {
                mode = 'longbreak';
                totalSeconds = LONG_BREAK_DURATION * 60;
                showNotif('🎉 Sesi selesai! Waktunya Long Break 15 menit!', '#8b5cf6');
            } else {
                mode = 'break';
                totalSeconds = BREAK_DURATION * 60;
                showNotif('✅ Fokus selesai! Waktunya Break 5 menit!', '#10b981');
            }
        } else {
            // Selesai break — balik ke fokus
            mode = 'focus';
            totalSeconds = FOCUS_DURATION * 60;
            showNotif('🎯 Break selesai! Waktunya Fokus lagi!', '#7C3AED');
        }

        localStorage.setItem('deepora_mode', mode);
        localStorage.setItem('deepora_timer_seconds', totalSeconds);
        updateDisplay();
        updateModeLabel();

        // Auto-start sesi berikutnya
        setTimeout(function() {
            startTimer();
        }, 3000); // tunggu 3 detik baru auto-start
    }

    function showNotif(message, color) {
        var notif = document.getElementById('xp-notification');
        notif.innerHTML = message;
        notif.style.borderColor = color;
        notif.style.color = color;
        notif.style.background = color + '33';
        notif.style.display = 'block';
        setTimeout(function() {
            notif.style.display = 'none';
        }, 5000);
    }

    function completeSession() {
        fetch('{{ route("pomodoro.complete") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    duration: FOCUS_DURATION,
                    room_id: null
                })
            })
            .then(function(res) {
                return res.json();
            })
            .then(function(data) {
                showNotif('🎉 +' + data.xp_earned + ' XP! Total: ' + data.total_xp + ' XP | ' + data.badge, '#10b981');
            });
    }

    document.getElementById('timer-duration').addEventListener('change', function() {
        if (!isRunning && mode === 'focus') {
            FOCUS_DURATION = parseInt(this.value);
            totalSeconds = FOCUS_DURATION * 60;
            localStorage.setItem('deepora_timer_seconds', totalSeconds);
            updateDisplay();
        }
    });

    // ===== INIT =====
    updateDisplay();
    updateModeLabel();
    updateSessionDots();

    if (isRunning) {
        if (totalSeconds <= 0) {
            onTimerComplete();
        } else {
            timerInterval = setInterval(tick, 1000);
        }
    }
</script>
@endsection
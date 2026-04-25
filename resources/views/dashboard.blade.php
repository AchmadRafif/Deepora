@extends('layouts.app')

@section('content')
<div class="container">
    <!-- User Stats -->
    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem; margin-top: 2rem;">
        <div class="card" style="text-align: center;">
            <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">
                <div style="width: 60px; height: 60px; border-radius: 50%; background: #000; margin: 0 auto 0.5rem; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
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
        <h2 style="font-family: 'Space Grotesk', sans-serif; margin-bottom: 2rem; font-size: 1.5rem;">⏱ Pomodoro Timer</h2>
        
        <div id="timer-display" style="font-size: 6rem; font-weight: 700; font-family: 'Space Grotesk', sans-serif; background: linear-gradient(135deg, #7C3AED, #EC4899); background-clip: text; -webkit-text-fill-color: transparent; line-height: 1; margin-bottom: 1.5rem;">
            25:00
        </div>
        
        <div style="margin-bottom: 1.5rem;">
            <label style="color: #94a3b8; font-size: 0.85rem;">DURASI (menit):</label>
            <select id="timer-duration" style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: white; padding: 0.4rem 1rem; border-radius: 8px; margin-left: 0.5rem;">
                <option value="25">25 menit (Pomodoro)</option>
                <option value="50">50 menit (Deep Work)</option>
                <option value="15">15 menit (Short)</option>
            </select>
        </div>
        
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
let totalSeconds = 25 * 60;
let timerInterval = null;
let isRunning = false;
let currentDuration = 25;

function updateDisplay() {
    const minutes = Math.floor(totalSeconds / 60);
    const seconds = totalSeconds % 60;
    document.getElementById('timer-display').textContent = 
        String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0');
}

function startTimer() {
    if (isRunning) return;
    currentDuration = parseInt(document.getElementById('timer-duration').value);
    if (!timerInterval) {
        totalSeconds = currentDuration * 60;
    }
    isRunning = true;
    timerInterval = setInterval(() => {
        totalSeconds--;
        updateDisplay();
        if (totalSeconds <= 0) {
            clearInterval(timerInterval);
            timerInterval = null;
            isRunning = false;
            completeSession();
        }
    }, 1000);
}

function pauseTimer() {
    clearInterval(timerInterval);
    timerInterval = null;
    isRunning = false;
}

function resetTimer() {
    pauseTimer();
    currentDuration = parseInt(document.getElementById('timer-duration').value);
    totalSeconds = currentDuration * 60;
    updateDisplay();
}

document.getElementById('timer-duration').addEventListener('change', function() {
    if (!isRunning) {
        totalSeconds = parseInt(this.value) * 60;
        updateDisplay();
    }
});

function completeSession() {
    fetch('{{ route("pomodoro.complete") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ duration: currentDuration, room_id: null })
    })
    .then(res => res.json())
    .then(data => {
        const notif = document.getElementById('xp-notification');
        notif.innerHTML = `🎉 Sesi selesai! +${data.xp_earned} XP | Total: ${data.total_xp} XP | ${data.badge}`;
        notif.style.display = 'block';
        setTimeout(() => notif.style.display = 'none', 5000);
    });
}
</script>
@endsection
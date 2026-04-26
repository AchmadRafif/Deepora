@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 900px;">
    <h1 style="font-family: 'Space Grotesk', sans-serif; font-size: 1.8rem; margin: 2rem 0;">👤 Profile</h1>

    @if(session('success'))
    <div style="background: rgba(16,185,129,0.2); border: 1px solid #10b981; color: #10b981; padding: 1rem; border-radius: 10px; margin-bottom: 1.5rem;">
        {{ session('success') }}
    </div>
    @endif

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">

        {{-- Avatar & Info --}}
        <div class="card" style="text-align: center; padding: 2rem;">
            @php
            $emojiMapProfile = ['electric'=>'⚡','fire'=>'🔥','nature'=>'🌿','diamond'=>'💎','galaxy'=>'🌌','crown'=>'👑'];
            $profileEmoji = $emojiMapProfile[$user->avatar_style ?? ''] ?? strtoupper(substr($user->name, 0, 1));
            @endphp
            <div style="width: 80px; height: 80px; border-radius: 50%; background: {{ $user->avatar_color }}; margin: 0 auto 1rem; display: flex; align-items: center; justify-content: center; font-size: 2rem; font-weight: 700; border: 3px solid rgba(124,58,237,0.5);">
                {{ $profileEmoji }}
            </div>
            <div style="font-size: 1.3rem; font-weight: 700; margin-bottom: 0.25rem;">{{ $user->name }}</div>
            <div style="color: #a78bfa; margin-bottom: 0.25rem;">{{ $user->badge ?? '🌱 Newbie' }}</div>
            <div style="color: #94a3b8; font-size: 0.85rem;">{{ $user->school ?? 'Belum isi sekolah' }}</div>

            {{-- XP Progress Bar --}}
            <div style="margin-top: 1.5rem;">
                <div style="display: flex; justify-content: space-between; font-size: 0.8rem; color: #94a3b8; margin-bottom: 0.4rem;">
                    <span>Level {{ $user->level }}</span>
                    <span>{{ $xpProgress }}/100 XP</span>
                </div>
                <div style="background: rgba(255,255,255,0.1); border-radius: 50px; height: 8px; overflow: hidden;">
                    <div style="background: linear-gradient(135deg, #7C3AED, #EC4899); height: 100%; width: {{ $xpProgressPercent }}%; border-radius: 50px; transition: width 0.5s;"></div>
                </div>
                <div style="font-size: 0.75rem; color: #94a3b8; margin-top: 0.3rem;">
                    {{ 100 - $xpProgress }} XP lagi untuk Level {{ $user->level + 1 }}
                </div>
            </div>
        </div>

        {{-- Edit Profile --}}
        <div class="card">
            <div style="font-size: 0.85rem; color: #94a3b8; margin-bottom: 1rem; text-transform: uppercase; letter-spacing: 1px;">Edit Profil</div>

            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; color: #94a3b8; font-size: 0.8rem; margin-bottom: 0.4rem;">NAMA</label>
                    <input type="text" name="name" value="{{ $user->name }}"
                        style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.15); color: white; padding: 0.6rem 1rem; border-radius: 8px; outline: none;">
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; color: #94a3b8; font-size: 0.8rem; margin-bottom: 0.4rem;">SEKOLAH</label>
                    <input type="text" name="school" value="{{ $user->school }}" placeholder="SMA/SMK/Universitas..."
                        style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.15); color: white; padding: 0.6rem 1rem; border-radius: 8px; outline: none;">
                </div>

                <div style="margin-bottom: 1.2rem;">
                    <label style="display: block; color: #94a3b8; font-size: 0.8rem; margin-bottom: 0.4rem;">WARNA AVATAR</label>
                    <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                        @foreach(['#7C3AED', '#EC4899', '#10b981', '#3b82f6', '#f59e0b', '#ef4444', '#06b6d4', '#8b5cf6'] as $color)
                        <label style="cursor: pointer;">
                            <input type="radio" name="avatar_color" value="{{ $color }}"
                                {{ $user->avatar_color === $color ? 'checked' : '' }}
                                style="display: none;">
                            <div style="width: 30px; height: 30px; border-radius: 50%; background: {{ $color }}; border: 3px solid {{ $user->avatar_color === $color ? 'white' : 'transparent' }}; transition: border 0.2s;"
                                onclick="this.parentElement.querySelector('input').checked = true; document.querySelectorAll('.color-circle').forEach(el => el.style.border = '3px solid transparent'); this.style.border = '3px solid white';"
                                class="color-circle">
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- Avatar Style Unlock --}}
                <div style="margin-bottom: 1.2rem;">
                    <label style="display: block; color: #94a3b8; font-size: 0.8rem; margin-bottom: 0.75rem;">AVATAR STYLE (Level Unlock)</label>

                    {{-- Unlocked --}}
                    <div style="display: flex; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 0.75rem;">
                        @foreach($user->getUnlockedAvatars() as $avatar)
                        <label style="cursor: pointer; text-align: center;">
                            <input type="radio" name="avatar_style" value="{{ $avatar['id'] }}"
                                {{ $user->avatar_style === $avatar['id'] ? 'checked' : '' }}
                                style="display: none;">
                            <div onclick="selectAvatar('{{ $avatar['id'] }}')" id="avatar-{{ $avatar['id'] }}"
                                style="width: 50px; height: 50px; border-radius: 50%; background: {{ $user->avatar_color }}; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; border: {{ $avatar['border'] }}; {{ $avatar['glow'] ? 'box-shadow: 0 0 12px rgba(124,58,237,0.6);' : '' }} transition: all 0.2s; {{ $user->avatar_style === $avatar['id'] ? 'outline: 3px solid white; outline-offset: 2px;' : '' }}">
                                {{ $avatar['emoji'] ?? strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div style="font-size: 0.65rem; color: #94a3b8; margin-top: 0.3rem;">{{ $avatar['name'] }}</div>
                        </label>
                        @endforeach
                    </div>

                    {{-- Locked --}}
                    @if(count($user->getLockedAvatars()) > 0)
                    <div style="font-size: 0.75rem; color: #64748b; margin-bottom: 0.5rem;">🔒 Terkunci:</div>
                    <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                        @foreach($user->getLockedAvatars() as $locked)
                        <div style="text-align: center; opacity: 0.4;">
                            <div style="width: 50px; height: 50px; border-radius: 50%; background: #374151; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; filter: grayscale(1);">
                                {{ $locked['emoji'] }}
                            </div>
                            <div style="font-size: 0.65rem; color: #64748b; margin-top: 0.3rem;">Lv.{{ $locked['required_level'] }}</div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>

                <button type="submit" class="btn-primary" style="width: 100%; padding: 0.7rem;">
                    💾 Simpan
                </button>
            </form>
        </div>
    </div>

    {{-- Statistik --}}
    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
        <div class="card" style="text-align: center;">
            <div style="font-size: 0.75rem; color: #94a3b8; margin-bottom: 0.5rem; text-transform: uppercase;">Total Sesi</div>
            <div style="font-size: 2rem; font-weight: 700; color: #a78bfa;">{{ $totalSessions }}</div>
        </div>
        <div class="card" style="text-align: center;">
            <div style="font-size: 0.75rem; color: #94a3b8; margin-bottom: 0.5rem; text-transform: uppercase;">Total Jam</div>
            <div style="font-size: 2rem; font-weight: 700; color: #a78bfa;">{{ $totalHours }}</div>
        </div>
        <div class="card" style="text-align: center;">
            <div style="font-size: 0.75rem; color: #94a3b8; margin-bottom: 0.5rem; text-transform: uppercase;">Sesi Minggu Ini</div>
            <div style="font-size: 2rem; font-weight: 700; color: #a78bfa;">{{ $weeklySessions }}</div>
        </div>
        <div class="card" style="text-align: center;">
            <div style="font-size: 0.75rem; color: #94a3b8; margin-bottom: 0.5rem; text-transform: uppercase;">Menit Minggu Ini</div>
            <div style="font-size: 2rem; font-weight: 700; color: #a78bfa;">{{ $weeklyMinutes }}</div>
        </div>
    </div>

    {{-- Grafik 7 Hari --}}
    <div class="card">
        <div style="font-size: 0.85rem; color: #94a3b8; margin-bottom: 1.5rem; text-transform: uppercase; letter-spacing: 1px;">📊 Aktivitas 7 Hari Terakhir</div>
        <div style="display: flex; align-items: flex-end; gap: 0.5rem; height: 150px;">
            @php $maxMinutes = max(array_column($dailyStats, 'minutes') ?: [1]); @endphp
            @foreach($dailyStats as $stat)
            <div style="flex: 1; display: flex; flex-direction: column; align-items: center; gap: 0.4rem; height: 100%;">
                <div style="flex: 1; display: flex; align-items: flex-end; width: 100%;">
                    <div style="width: 100%; background: linear-gradient(135deg, #7C3AED, #EC4899); border-radius: 6px 6px 0 0; height: {{ $maxMinutes > 0 ? ($stat['minutes'] / $maxMinutes * 100) : 0 }}%; min-height: {{ $stat['minutes'] > 0 ? '4px' : '0' }}; transition: height 0.5s;"></div>
                </div>
                <div style="font-size: 0.7rem; color: #94a3b8;">{{ $stat['day'] }}</div>
                <div style="font-size: 0.65rem; color: #64748b;">{{ $stat['minutes'] }}m</div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

<script>
    function selectAvatar(id) {
        // Uncheck semua
        document.querySelectorAll('input[name="avatar_style"]').forEach(function(el) {
            el.checked = false;
        });
        // Check yang dipilih
        document.querySelector('input[name="avatar_style"][value="' + id + '"]').checked = true;

        // Update visual border
        document.querySelectorAll('[id^="avatar-"]').forEach(function(el) {
            el.style.outline = 'none';
        });
        document.getElementById('avatar-' + id).style.outline = '3px solid white';
        document.getElementById('avatar-' + id).style.outlineOffset = '2px';
    }
</script>
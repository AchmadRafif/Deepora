@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 800px;">
    <h1 style="font-family: 'Space Grotesk', sans-serif; font-size: 1.8rem; text-align: center; margin: 2rem 0;">🏆 Leaderboard</h1>

    <div class="card">
        @foreach($weekly as $index => $user)
        <div style="display: flex; align-items: center; padding: 1rem 0; border-bottom: 1px solid rgba(255,255,255,0.05); {{ $loop->last ? 'border-bottom: none' : '' }}">
            <div style="width: 40px; font-size: {{ $index < 3 ? '1.5rem' : '1rem' }}; color: {{ $index === 0 ? '#FFD700' : ($index === 1 ? '#C0C0C0' : ($index === 2 ? '#CD7F32' : '#94a3b8')) }}; font-weight: 700; text-align: center;">
                {{ $index < 3 ? ['🥇','🥈','🥉'][$index] : '#'.($index+1) }}
            </div>

            @php
            $emojiMapLB = ['electric'=>'⚡','fire'=>'🔥','nature'=>'🌿','diamond'=>'💎','galaxy'=>'🌌','crown'=>'👑'];
            $avatarEmoji = $emojiMapLB[$user->avatar_style ?? ''] ?? strtoupper(substr($user->name, 0, 1));
            @endphp
            <div style="width: 40px; height: 40px; border-radius: 50%; background: {{ $user->avatar_color }}; display: flex; align-items: center; justify-content: center; font-weight: 700; margin: 0 1rem; font-size: 1.1rem;">
                {{ $avatarEmoji }}
            </div>

            <div style="flex: 1;">
                <div style="font-weight: 600;">{{ $user->name }}</div>
                <div style="font-size: 0.8rem; color: #94a3b8;">{{ $user->badge ?? '🌱 Newbie' }}</div>
            </div>

            <div style="text-align: right;">
                <div style="font-weight: 700; background: linear-gradient(135deg, #7C3AED, #EC4899); background-clip: text; -webkit-text-fill-color: transparent;">{{ $user->xp }} XP</div>
                <div style="font-size: 0.8rem; color: #94a3b8;">Lv. {{ $user->level }}</div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
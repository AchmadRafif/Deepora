@php
    $style = $avatarStyle ?? 'default';
    $name = $avatarName ?? 'U';
    $color = $avatarColor ?? '#7C3AED';
    $size = $size ?? '40px';
    $fontSize = $fontSize ?? '1rem';

    $emojiMap = [
        'electric' => '⚡',
        'fire'     => '🔥',
        'nature'   => '🌿',
        'diamond'  => '💎',
        'galaxy'   => '🌌',
        'crown'    => '👑',
    ];

    $borderMap = [
        'default'  => '3px solid rgba(255,255,255,0.1)',
        'electric' => '3px solid #f59e0b',
        'fire'     => '3px solid #ef4444',
        'nature'   => '3px solid #10b981',
        'diamond'  => '3px solid #06b6d4',
        'galaxy'   => '3px solid #8b5cf6',
        'crown'    => '3px solid #FFD700',
    ];

    $glowMap = [
        'diamond' => '0 0 12px rgba(6,182,212,0.6)',
        'galaxy'  => '0 0 12px rgba(139,92,246,0.6)',
        'crown'   => '0 0 15px rgba(255,215,0,0.7)',
    ];

    $emoji = array_key_exists($style, $emojiMap) ? $emojiMap[$style] : strtoupper(substr($name, 0, 1));
    $border = $borderMap[$style] ?? '3px solid rgba(255,255,255,0.1)';
    $glow = $glowMap[$style] ?? 'none';
@endphp

<div style="
    width: {{ $size }};
    height: {{ $size }};
    border-radius: 50%;
    background: {{ $color }};
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: {{ $fontSize }};
    font-weight: 700;
    border: {{ $border }};
    box-shadow: {{ $glow }};
    flex-shrink: 0;
    line-height: 1;
">{{ $emoji }}</div>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deepora - Deep Work Hub</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Space+Grotesk:wght@400;500;700&display=swap');
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Inter', sans-serif;
            background: #0f0f1a;
            color: #e2e8f0;
            min-height: 100vh;
        }
        
        .navbar {
            background: rgba(15, 15, 26, 0.8);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(124, 58, 237, 0.2);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .logo {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, #7C3AED, #EC4899);
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .nav-links a {
            color: #94a3b8;
            text-decoration: none;
            margin-left: 1.5rem;
            font-size: 0.9rem;
            transition: color 0.2s;
        }
        
        .nav-links a:hover { color: #e2e8f0; }
        
        .btn-primary {
            background: linear-gradient(135deg, #7C3AED, #EC4899);
            border: none;
            color: white;
            padding: 0.6rem 1.5rem;
            border-radius: 50px;
            cursor: pointer;
            font-size: 0.9rem;
            font-weight: 500;
            text-decoration: none;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(124, 58, 237, 0.4);
        }
        
        .container { max-width: 1200px; margin: 0 auto; padding: 2rem; }
        
        .card {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 16px;
            padding: 1.5rem;
            backdrop-filter: blur(10px);
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="logo">✦ Deepora</div>
        <div class="nav-links">
            <a href="{{ route('dashboard') }}">Dashboard</a>
            <a href="{{ route('rooms.index') }}">Focus Rooms</a>
            <a href="{{ route('leaderboard') }}">Leaderboard</a>
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
        </div>
    </nav>
    
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none">
        @csrf
    </form>
    
    <main>
        @if(session('success'))
            <div style="background: rgba(16,185,129,0.2); border: 1px solid #10b981; color: #10b981; padding: 1rem 2rem; text-align: center;">
                {{ session('success') }}
            </div>
        @endif
        @yield('content')
    </main>
</body>
</html>
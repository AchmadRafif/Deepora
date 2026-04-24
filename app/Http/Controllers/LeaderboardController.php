<?php
namespace App\Http\Controllers;

use App\Models\User;

class LeaderboardController extends Controller
{
    public function index()
    {
        $weekly = User::orderByDesc('xp')
            ->select('name', 'xp', 'level', 'badge', 'avatar_color', 'school')
            ->take(20)
            ->get();

        return view('leaderboard', compact('weekly'));
    }
}
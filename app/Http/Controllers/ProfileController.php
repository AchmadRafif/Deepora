<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PomodoroSession;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Total statistik
        $totalSessions = PomodoroSession::where('user_id', $user->id)
            ->where('completed', true)
            ->count();

        $totalMinutes = PomodoroSession::where('user_id', $user->id)
            ->where('completed', true)
            ->sum('duration');

        $totalHours = round($totalMinutes / 60, 1);

        // Sesi minggu ini
        $weeklySessions = PomodoroSession::where('user_id', $user->id)
            ->where('completed', true)
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->count();

        $weeklyMinutes = PomodoroSession::where('user_id', $user->id)
            ->where('completed', true)
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->sum('duration');

        // Sesi 7 hari terakhir (untuk grafik)
        $dailyStats = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $minutes = PomodoroSession::where('user_id', $user->id)
                ->where('completed', true)
                ->whereDate('created_at', $date->toDateString())
                ->sum('duration');
            $dailyStats[] = [
                'day' => $date->format('D'),
                'minutes' => $minutes
            ];
        }

        // XP untuk level berikutnya
        $xpForNextLevel = ($user->level * 100);
        $xpProgress = $user->xp % 100;
        $xpProgressPercent = ($xpProgress / 100) * 100;

        return view('profile', compact(
            'user',
            'totalSessions',
            'totalMinutes',
            'totalHours',
            'weeklySessions',
            'weeklyMinutes',
            'dailyStats',
            'xpForNextLevel',
            'xpProgress',
            'xpProgressPercent'
        ));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'school' => 'nullable|string|max:100',
            'avatar_color' => 'required|string',
            'avatar_style' => 'nullable|string',
        ]);

        $user = $request->user();
        $user->name = $request->name;
        $user->school = $request->school;
        $user->avatar_color = $request->avatar_color;

        // Validasi avatar style sesuai level
        $unlockedIds = array_column($user->getUnlockedAvatars(), 'id');
        if (in_array($request->avatar_style, $unlockedIds)) {
            $user->avatar_style = $request->avatar_style;
        }

        $user->save();

        return redirect()->route('profile')->with('success', 'Profil berhasil diupdate!');
    }
}

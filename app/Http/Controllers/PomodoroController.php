<?php
namespace App\Http\Controllers;

use App\Models\PomodoroSession;
use Illuminate\Http\Request;

class PomodoroController extends Controller
{
    public function complete(Request $request)
    {
        $request->validate([
            'duration' => 'required|integer|min:1',
            'room_id' => 'nullable|exists:focus_rooms,id',
        ]);

        $xpEarned = $request->duration * 2; // 2 XP per menit

        PomodoroSession::create([
            'user_id' => auth()->id(),
            'room_id' => $request->room_id,
            'duration' => $request->duration,
            'completed' => true,
            'xp_earned' => $xpEarned,
        ]);

        // Update user XP
        $user = auth()->user();
        $user->xp += $xpEarned;
        $user->level = floor($user->xp / 100) + 1;

        // Assign badge
        $user->badge = $this->assignBadge($user->xp);
        $user->save();

        return response()->json([
            'success' => true,
            'xp_earned' => $xpEarned,
            'total_xp' => $user->xp,
            'level' => $user->level,
            'badge' => $user->badge,
        ]);
    }

    private function assignBadge(int $xp): string
    {
        return match(true) {
            $xp >= 1000 => '🏆 Study Legend',
            $xp >= 500  => '💎 Focus Master',
            $xp >= 200  => '🔥 Grind Mode',
            $xp >= 50   => '⚡ Rising Star',
            default     => '🌱 Newbie',
        };
    }
}
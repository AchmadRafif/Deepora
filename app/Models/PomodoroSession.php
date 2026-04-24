<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PomodoroSession extends Model
{
    protected $fillable = ['user_id', 'room_id', 'duration', 'completed', 'xp_earned'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FocusRoom extends Model
{
    protected $fillable = ['name', 'music_type', 'youtube_url', 'created_by', 'max_members', 'is_active'];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function sessions()
    {
        return $this->hasMany(PomodoroSession::class, 'room_id');
    }
}
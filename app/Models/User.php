<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected $fillable = [
        'name',
        'email',
        'password',
        'xp',
        'level',
        'avatar_color',
        'avatar_style',
        'badge',
        'school'
    ];

    public function sessions()
    {
        return $this->hasMany(PomodoroSession::class);
    }

    public function totalSessionsThisWeek()
    {
        return $this->sessions()
            ->where('completed', true)
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->count();
    }

    public function getUnlockedAvatars(): array
    {
        $level = $this->level;
        $avatars = [];

        // Level 1 - default
        $avatars[] = ['id' => 'default', 'name' => 'Default', 'emoji' => null, 'border' => 'none', 'glow' => false, 'required_level' => 1];

        // Level 2
        if ($level >= 2) {
            $avatars[] = ['id' => 'electric', 'name' => 'Electric', 'emoji' => '⚡', 'border' => '3px solid #f59e0b', 'glow' => false, 'required_level' => 2];
        }

        // Level 5
        if ($level >= 5) {
            $avatars[] = ['id' => 'nature', 'name' => 'Nature', 'emoji' => '🌿', 'border' => '3px solid #ef4444', 'glow' => false, 'required_level' => 5];
        }

        // Level 10
        if ($level >= 10) {
            $avatars[] = ['id' => 'Fire', 'name' => 'Fire', 'emoji' => '🔥', 'border' => '3px solid #10b981', 'glow' => false, 'required_level' => 10];
        }

        // Level 15
        if ($level >= 15) {
            $avatars[] = ['id' => 'diamond', 'name' => 'Diamond', 'emoji' => '💎', 'border' => '3px solid #06b6d4', 'glow' => true, 'required_level' => 15];
        }

        // Level 20
        if ($level >= 20) {
            $avatars[] = ['id' => 'galaxy', 'name' => 'Galaxy', 'emoji' => '🌌', 'border' => '3px solid #8b5cf6', 'glow' => true, 'required_level' => 20];
        }

        // Level 30
        if ($level >= 30) {
            $avatars[] = ['id' => 'crown', 'name' => 'Legendary', 'emoji' => '👑', 'border' => '3px solid #FFD700', 'glow' => true, 'required_level' => 30];
        }

        return $avatars;
    }

    public function getLockedAvatars(): array
    {
        $level = $this->level;
        $locked = [];

        if ($level < 2)  $locked[] = ['name' => 'Electric', 'emoji' => '⚡', 'required_level' => 2];
        if ($level < 5)  $locked[] = ['name' => 'Nature', 'emoji' => '🌿', 'required_level' => 5];
        if ($level < 10)  $locked[] = ['name' => 'Fire', 'emoji' => '🔥', 'required_level' => 10];
        if ($level < 15) $locked[] = ['name' => 'Diamond', 'emoji' => '💎', 'required_level' => 15];
        if ($level < 20) $locked[] = ['name' => 'Galaxy', 'emoji' => '🌌', 'required_level' => 20];
        if ($level < 30) $locked[] = ['name' => 'Legendary', 'emoji' => '👑', 'required_level' => 30];

        return $locked;
    }
}

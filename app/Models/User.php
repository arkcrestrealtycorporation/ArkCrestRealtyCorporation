<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'preferred_address',
        'position',
        'team_name',
        'employee_id',
        'date_hired',
        'email',
        'avatar',
        'role',
        'status',
        'password',
        'last_login_at',
        'security_question',
        'security_answer',
        'hidden_pages',
        'last_seen_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

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
            'last_login_at' => 'datetime',
            'date_hired' => 'date',
            'hidden_pages' => 'array',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function getAvatarUrlAttribute(): ?string
    {
        if (!$this->avatar) {
            return null;
        }

        // Already a full external URL.
        if (
            Str::startsWith($this->avatar, 'http://') ||
            Str::startsWith($this->avatar, 'https://')
        ) {
            return $this->avatar;
        }

        // Uploaded avatar from the configured filesystem.
        if (Str::startsWith($this->avatar, 'avatars/')) {
            return Storage::disk(config('filesystems.default'))
                ->url($this->avatar);
        }

        // Existing static image inside the public directory.
        return asset($this->avatar);
    }
}

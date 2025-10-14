<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'matric_no',
        'email',
        'password',
        'workplace',
        'role',
        'profile_picture',
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
        ];
    }

    public function logEntries()
    {

        return $this->hasMany(LogEntry::class);
    
    }

    
    public function projectEntries()
    {
        return $this->hasMany(ProjectEntry::class);
    }

    public function isSupervisor() { return $this->role === 'supervisor'; }
    public function isStudent() { return $this->role === 'student'; }
    public function isAdmin() { return $this->role === 'admin'; }
    
    public function getProfilePictureUrl()
    {
        if ($this->profile_picture && file_exists(public_path('storage/' . $this->profile_picture))) {
            return asset('storage/' . $this->profile_picture);
        }
        
        // Return default avatar based on role
        $defaultAvatars = [
            'admin' => 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=dc3545&color=ffffff&size=120',
            'supervisor' => 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=0d6efd&color=ffffff&size=120',
            'student' => 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=198754&color=ffffff&size=120',
        ];
        
        return $defaultAvatars[$this->role] ?? $defaultAvatars['student'];
    }


}

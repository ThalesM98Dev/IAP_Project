<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'role',
        'email',
        'password',
        'files_limit',
        'files_counter',
        'user_ip'

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_users')->orderByDesc('id');
    }

    public function ownedFiles()
    {
        return $this->hasMany(File::class, 'owner_id')->orderByDesc('id');
    }

    public function lockingFiles()
    {
        return $this->hasMany(File::class, 'locked_by')->orderByDesc('updated_at');
    }

    public function actions()
    {
        return $this->hasMany(FileHistory::class)->orderByDesc('id');
    }
}

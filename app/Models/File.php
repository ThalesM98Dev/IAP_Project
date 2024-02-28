<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class File extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];
    protected $fillable = [
        'name',
        'type',
        'owner_id',
        'locked',
        'link',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function locker()
    {
        return $this->belongsTo(User::class, 'locked_by');
    }

    public function history()
    {
        return $this->hasMany(FileHistory::class)->orderByDesc('created_at');
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'file_groups');
    }
}

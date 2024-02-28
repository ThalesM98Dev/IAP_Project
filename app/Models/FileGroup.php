<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileGroup extends Model
{
    use HasFactory;
     protected $guarded = ['id'];

      public function file(){
        return $this->belongsTo(File::class);
    }

    public function group(){
        return $this->belongsTo(Group::class);
    }
}

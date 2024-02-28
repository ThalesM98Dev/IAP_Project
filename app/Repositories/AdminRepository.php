<?php

namespace App\Repositories;


use App\Contracts\AdminInterface;
use App\Enum\Rules;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class AdminRepository implements AdminInterface
{
    public function all_users()
    {
        return Cache::remember('users', self::seconds, function () {
            return User::where('role', Rules::USER->value)->get();//sort by date!!
        });
    }
    public function setFilesLimit($user_id,$limit)
    {
        User::query()->find($user_id)->update([
            'files_limit' => $limit
        ]);
    }
}

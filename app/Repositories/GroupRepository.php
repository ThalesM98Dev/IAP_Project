<?php

namespace App\Repositories;

use App\Contracts\GroupInterface;
use App\Models\Group;
use App\Models\GroupUser;
use App\Models\User;
use http\Env\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class GroupRepository implements GroupInterface
{

    public function all()//all groups for admin
    {
        //with members count
        return Cache::remember('groups', self::seconds, function () {
            return Group::orderBy('created_at', 'asc')->with('owner')->get();
        });
    }

    public function store($data)//store group
    {
        return Group::query()->create(['name' => $data['name'], 'owner_id' => Auth::id()]);
    }

    public function show($group_id, $relations)//show group with members
    {
        if ($relations)
            return Cache::remember('group', self::seconds, function () use ($group_id) {
                return Group::query()->with(['members', 'owner'])->findOrFail($group_id);
            });
        else
            return Cache::remember('group', self::seconds, function () use ($group_id) {
                return Group::query()->findOrFail($group_id);
            });
    }

    public function update($group_id, $newName)//update group name
    {
        return Group::query()->findOrFail($group_id)
            ->update(['name' => $newName]);
    }

    public function delete($group_id)//delete group
    {
        return Group::query()->findOrFail($group_id)
            ->delete();
    }

    public function addUser($user_id, $group_id)
    {
        return GroupUser::query()->create(['user_id' => $user_id, 'group_id' => $group_id]);
    }

    public function removeUser($group_id, $user_id)
    {
        return GroupUser::query()->where('group_id', $group_id)
            ->where('user_id', $user_id)
            ->delete();
    }

    public function userGroups($user_id)//all groups that the user belongs to
    {
        return Cache::remember('user_groups', self::seconds, function () use ($user_id) {
                return User::query()->with(['groups'])->findOrFail($user_id);
        });
    }

}

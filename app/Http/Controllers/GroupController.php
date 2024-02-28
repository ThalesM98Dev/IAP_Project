<?php

namespace App\Http\Controllers;

use App\Contracts\GroupInterface;

use App\Http\Requests\AddGroupRequest;
use App\Http\Requests\GroupUserRequest;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Request as FacadeRequest;

class GroupController extends Controller
{
    protected GroupInterface $group;

    public function __construct(GroupInterface $groupinterface)
    {
        $this->group = $groupinterface;
    }

    public function create(AddGroupRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $group = $this->group->store($request->all());
            $this->group->addUser(Auth::id(), $group['id']);
            //logs
            $log = response()->json([$group], Response::HTTP_OK);
            Log::query()->create(['type' => 'Response', 'Log' => $log]);
            return redirect()->action([GroupController::class, 'userGroups']);
        });
    }

    public function show($group_id)//show group with members
    {
        $group = $this->group->show($group_id, 1);
        //logs
        $log = response()->json([$group], Response::HTTP_OK);
        Log::query()->create(['type' => 'Response', 'Log' => $log]);
        return view('Group/groupMembers', compact('group', 'group_id'));
    }

    public function update(AddGroupRequest $request)
    {
        auth()->user()->update($request->only(['name']));
        return redirect()->action([GroupController::class, 'userGroups']);
    }

    public function allGroups()//for admin
    {
        $all_groups = $this->group->all();
        //logs
        $log = response()->json([$all_groups], Response::HTTP_OK);
        Log::query()->create(['type' => 'Response', 'Log' => $log]);
        return view('Admin/all_groups', ['groups' => $all_groups]);
    }

    public function addUserPage($group_id)
    {
        $users = User::where('role', '!=', 1)
            ->where('id', '!=', Auth::id())
            ->whereNotIn('id', function ($query) use ($group_id) {
                $query->select('user_id')->from('group_users')->where('group_id', $group_id);
            })->get();
        return view('Group/addUsers', compact('users', 'group_id'));
    }

    public function addUser(GroupUserRequest $request)
    {
        $this->group->addUser($request['user_id'], $request['group_id']);
        //logs
        $log = response()->json(['true'], Response::HTTP_OK);
        Log::query()->create(['type' => 'Response', 'Log' => $log]);
        return redirect()->action([GroupController::class, 'show'], ['id' => $request['group_id']]);
    }

    public function userGroups()
    {
        $user = $this->group->userGroups(Auth::id());
        User::query()->find(Auth::id())->update([//register user IP with login
            'user_ip' => FacadeRequest::ip()
        ]);
        //logs
        $log = response()->json([$user], Response::HTTP_OK);
        Log::query()->create(['type' => 'Response', 'Log' => $log]);
        return view('User/all_user_groups', compact('user'));
    }

    public function removeUser(GroupUserRequest $request)
    {
        $this->group->removeUser($request['group_id'], $request['user_id']);
        //logs
        $log = response()->json(['true'], Response::HTTP_OK);
        Log::query()->create(['type' => 'Response', 'Log' => $log]);
        return redirect()->back();
    }

    public function leaveGroup(Request $request)
    {
        $request->validate(['group_id' => 'required|exists:groups,id']);
        $this->group->removeUser($request['group_id'], Auth::id());
        //logs
        $log = response()->json(['true'], Response::HTTP_OK);
        Log::query()->create(['type' => 'Response', 'Log' => $log]);
        return redirect()->action([GroupController::class, 'userGroups']);
    }

    public function removeGroup(Request $request)
    {
        $this->group->delete($request['group_id']);
        //logs
        $log = response()->json(['true'], Response::HTTP_OK);
        Log::query()->create(['type' => 'Response', 'Log' => $log]);
        return redirect()->back();
    }

    public function searchForGroup(Request $request)//search for a group
    {
        $search_text = $request->search_text;
        $user = $this->group->userGroups(Auth::id());
        $groups = $user->groups()
            ->where('name', 'LIKE', '%' . $search_text . '%')
            ->get();
        return view('search/groups', compact('groups'));
    }
}

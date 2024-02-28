<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\AdminInterface;
use App\Http\Controllers\Controller;
use App\Models\Log;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public $admin;

    function __construct(AdminInterface $interface)
    {
        $this->admin = $interface;
    }

    public function all_users()//all registered users
    {
        $all_users = $this->admin->all_users()->sortBy('created_at');
        return view('Admin/all_users', ['users' => $all_users]);
    }
    public function setFilesLimitPage(){
        $all_users = $this->admin->all_users()->sortBy('created_at');
        return view('Admin/set_files_limit', ['users' => $all_users]);

    }
    public function setFilesLimit(Request $request)//set files count limit for each user (default set is 100)
    {
        $user_id = $request['user_id'];
        $limit = $request['files_limit'];
        return DB::transaction(function () use ($user_id, $limit) {
            $new_limit = $this->admin->setFilesLimit($user_id, $limit);
            //logs
            $log = response()->json([$new_limit], Response::HTTP_OK);
            Log::query()->create(['type' => 'Response', 'Log' => $log]);
            return redirect()->back();
        });
    }
}

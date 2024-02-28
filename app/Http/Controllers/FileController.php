<?php

namespace App\Http\Controllers;

use App\Contracts\FIleHistoryInterface;
use App\Enum\FileActionsEnum;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\CheckOutRequest;
use App\Http\Requests\FileRequest;
use App\Models\File;
use App\Models\Group;
use App\Contracts\FileInterface;
use App\Contracts\GroupInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class FileController extends Controller
{
    protected GroupInterface $group;
    protected FileInterface $file;
    protected FIleHistoryInterface $history;

    public function __construct(GroupInterface $groupinterface, FileInterface $fileinterface, FIleHistoryInterface $FIleHistory)
    {
        $this->group = $groupinterface;
        $this->file = $fileinterface;
        $this->history = $FIleHistory;
        $this->middleware(['allFilesBlocked'], ['only' => ['checkIn']]);
        $this->middleware(['lockedByMe'], ['only' => ['checkOut']]);
        $this->middleware(['fileLocked'], ['only' => ['destroy']]);
    }

    public function addFilePage()
    {
        $groups = $this->group->userGroups(Auth::id())->groups;
        return view('File/addFile', compact('groups'));
    }

    public function store(FileRequest $request)
    {
        $request = $request->validated();
        $request['link'] = upload($request['file'], 'files');
        $request['owner_id'] = Auth::id();
        $request['created_at'] = now()->addHours(3);
        $request['updated_at'] = now()->addHours(3);
        if (Auth::user()->files_counter > Auth::user()->files_limit) {
            return response()->json(['You have reached your limit!']);
        }
        return DB::transaction(function () use ($request) {
            $file = $this->file->store($request);
            foreach ($request['groups_ids'] as $group_id) {
                $this->file->addFileToGroup($file['id'], $group_id);
                $this->history->createLog($file->id, FileActionsEnum::CREATE->value);
            }
            Auth::user()->increment('files_counter');
            //logs
            $log = response()->json([$file], Response::HTTP_OK);
            Log::query()->create(['type' => 'Response', 'Log' => $log]);
            return redirect()->action([GroupController::class, 'userGroups']);
        });
    }

    public function groupFiles($group_id)
    {
        $group = $this->file->groupFiles($group_id);
        $group_files = $this->file->groupFiles($group_id)->files->sortBy('created_at');
        //logs
        $log = response()->json([$group], Response::HTTP_OK);
        Log::query()->create(['type' => 'Response', 'Log' => $log]);
        return view('File/groupFiles', compact('group_files', 'group'));
    }

    public function show($id)//view one file
    {
        $file = $this->file->show($id, 1);
        //logs
        $log = response()->json([$file], Response::HTTP_OK);
        Log::query()->create(['type' => 'Response', 'Log' => $log]);
        return view('File/viewFile', compact('file', 'id'));
    }

    public function checkIn(Request $request)
    {
        $request->validate(['files' => 'required|array', 'files.*' => 'required|exists:files,id']);
        return DB::transaction(function () use ($request) {
            $files = [];
            foreach ($request['files'] as $file_id) {
                $files[] = $this->file->show($file_id, 0);
                $this->file->checkIn($file_id, Auth::id());
                $this->history->createLog($file_id, FileActionsEnum::LOCK->value);
            }
            //logs
            $log = response()->json([$files], Response::HTTP_OK);
            Log::query()->create(['type' => 'Response', 'Log' => $log]);
            return redirect()->back();
        });
    }

    public function checkOut(CheckOutRequest $request)
    {
        $request = $request->validated();
        return DB::transaction(function () use ($request) {
            $oldFile = $this->file->show($request['file_id'], 0);
            deleteFile($oldFile['link']);
            $link = upload($request['file'], 'files');
            $newFile = $this->file->checkOut($oldFile, $link);
            $this->history->createLog($oldFile->id, FileActionsEnum::UNLOCK->value);
            //logs
            $log = response()->json([$newFile], Response::HTTP_OK);
            Log::query()->create(['type' => 'Response', 'Log' => $log]);
            return redirect()->action([GroupController::class, 'userGroups']);
        });
    }

    public function changeFilePage($id)
    {
        $groups = Group::all();
        $file = File::find($id);
        return view('File/changeFile', compact('groups', 'file'));
    }

    public function destroy($id)
    {
        //dd($id);
        return DB::transaction(function () use ($id) {
            $file = $this->file->delete($id);
            $this->history->createLog($id, FileActionsEnum::DELETE->value);
            Auth::user()->decrement('files_counter');
            //Logs
            $log = response()->json([$file], Response::HTTP_OK);
            Log::query()->create(['type' => 'Response', 'Log' => $log]);
            return redirect()->back();//edited here
        });
    }

    public function searchForFile(Request $request)
    {
        $search_text = $request->search_text;
        $user = $this->group->userGroups(Auth::id());
        $user_groups = $user->groups;
        $files = collect();
        foreach ($user_groups as $group) {
            $group_files = $group->files()
                ->where('name', 'LIKE', '%' . $search_text . '%')
                ->orWhere('type', 'LIKE', '%' . $search_text . '%')
                ->get();
            foreach ($group_files as $file) {
                $file->group_name = $group->name;
            }
            $files = $files->concat($group_files);
        }
        //Logs
        $log = response()->json([$files], Response::HTTP_OK);
        Log::query()->create(['type' => 'Response', 'Log' => $log]);
        return view('search/files', compact('files'));
    }
}

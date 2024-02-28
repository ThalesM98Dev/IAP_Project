<?php

namespace App\Http\Controllers;

use App\Contracts\FIleHistoryInterface;
use App\Http\Requests\FileHistoryRequest;
use App\Models\File;
use App\Models\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response as FacadeResponse;
use Illuminate\Http\Response;

class FileHistoryController extends Controller
{
    protected FIleHistoryInterface $fHistory;

    public function __construct(FIleHistoryInterface $FIleHistory)
    {
        $this->fHistory = $FIleHistory;
    }

    public function history()
    {
        $history = $this->fHistory->history();
        //logs
        $log = response()->json([$history], Response::HTTP_OK);
        Log::query()->create(['type' => 'Response', 'Log' => $log]);
        return view('Admin/history', compact('history'));
    }

    public function historyByUser()
    {
        $history = $this->fHistory->historyByUser();
        //logs
        $log = response()->json([$history], Response::HTTP_OK);
        Log::query()->create(['type' => 'Response', 'Log' => $log]);
        return view('User/history_by_user', compact('history'));
    }

    public function historyByFile(FileHistoryRequest $request)
    {
        $history = $this->fHistory->historyByFile($request['file_id']);
        $file_id = $request['file_id'];
        //logs
        $log = response()->json([$history], Response::HTTP_OK);
        Log::query()->create(['type' => 'Response', 'Log' => $log]);
        return view('User/history_by_file', compact('history', 'file_id'));
    }

    public function choosingFilePage()
    {
        $files = File::query()->where('owner_id', Auth::id())->get();
        return view('User/choosing_file', compact('files'));
    }

    public function exportToTxt(FileHistoryRequest $request)//export history by file
    {
        $history = $this->fHistory->historyByFile($request['file_id']);
        $content = $this->fHistory->exportToTxt($request, $history);
        $file_name = "file_history.txt";
        $headers = [
            'Content-type' => 'text/plain',
            'Content-Disposition' => sprintf('attachment; filename="%s"', $file_name),
            'Content-Length' => strlen($content)
        ];
        //logs
        $log = response()->json([$content], Response::HTTP_OK);
        Log::query()->create(['type' => 'Response', 'Log' => $log]);

        return FacadeResponse::make($content, 200, $headers);
    }

    public function exportToTxtUser()//export history by user
    {
        $history = $this->fHistory->historyByUser();
        //$file=File::withTrashed()->find($item->file_id);
        $content = $this->fHistory->exportToTxtUser($history);
        $file_name = "file_history.txt";
        $headers = [
            'Content-type' => 'text/plain',
            'Content-Disposition' => sprintf('attachment; filename="%s"', $file_name),
            'Content-Length' => strlen($content)
        ];
        //logs
        $log = response()->json([$content], Response::HTTP_OK);
        Log::query()->create(['type' => 'Response', 'Log' => $log]);
        return FacadeResponse::make($content, 200, $headers);
    }
}

<?php

namespace App\Repositories;

use App\Contracts\FIleHistoryInterface;
use App\Models\File;
use App\Models\FileHistory;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class FileHistoryRepository implements FIleHistoryInterface
{
    public function history()
    {
        return FileHistory::query()->get();
    }

    public function historyByUser()
    {
        return Cache::remember('user_history', self::seconds, function () {
            return FileHistory::query()->where('editor_id', Auth::id())
                ->orderByDesc('created_at')->get();
        });

    }

    public function historyByFile($file_id)
    {
        return Cache::remember('file_history', self::seconds, function () use ($file_id) {
            return FileHistory::query()->where('file_id', $file_id)
                ->orderByDesc('created_at')->get();
        });
    }

    public function createLog($file_id, $action)
    {
        return FileHistory::query()->create([
            'action' => $action,
            'file_id' => $file_id,
            'editor_id' => Auth::id(),
        ]);
    }

    public function exportToTxt($request, $history)
    {
        $content = "Logs \n \nFile link  : " . File::find($request['file_id'])->link . "\n \n";
        foreach ($history as $item) {
            $content .= "Action : " . $item->action . "\t" . "|" . "\t" .
                "Editor : " . User::findOrFail($item->editor_id)->name . "\t" . "|" . "\t" .
                "Date : " . $item->created_at . "\n";
        }
        return $content;
    }

    public function exportToTxtUser($history)
    {
        $content = "Logs \n \nUser name  : " . Auth::user()->name . "\n \n";
        foreach ($history as $item) {
            //dd(File::withTrashed($item->file_id)->first()->link);
            $content .= "Action : " . $item->action . "\t" . "|" . "\t" .
                "Editor : " . User::find($item->editor_id)->name . "\t" . "|" . "\t" .
                "File link : " . File::withTrashed()->find($item->file_id)->link . "\t" . "|" . "\t" .
                "File name : " . File::withTrashed()->find($item->file_id)->name . "\t" . "|" . "\t" .
                "Date : " . $item->created_at . "\n \n";
        }
        return $content;
    }
}

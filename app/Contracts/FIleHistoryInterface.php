<?php

namespace App\Contracts;

use App\Models\FileHistory;

interface FIleHistoryInterface
{
    public function history();

    public function historyByUser();

    public function historyByFile($file_id);

    public function createLog($file_id, $action);

    public function exportToTxt($request, $history);

    public function exportToTxtUser($history);

    public const seconds = 1;
}

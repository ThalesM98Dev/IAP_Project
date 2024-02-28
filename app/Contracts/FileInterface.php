<?php

namespace App\Contracts;

use App\Models\File;

interface FileInterface
{

    public function store($data);

    public function addFileToGroup($file_id, $group_id);

    public function groupFiles($group_id);

    public function checkIn($file_id, $user_id);

    public function checkOut(File $file, $link);

    public function show($file_id, $relations);

    public function delete($file_id);
    public const seconds = 1;
}

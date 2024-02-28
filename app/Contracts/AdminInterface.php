<?php

namespace App\Contracts;

interface  AdminInterface
{
    public function all_users();

    public function setFilesLimit($user_id, $limit);

    public const seconds = 1;


}


<?php

namespace App\Contracts;

interface GroupInterface
{
    public function all();

    public function store($data);

    public function show($group_id, $relations);

    public function update($group_id,$newName);

    public function delete($group_id);

    public function addUser($user_id, $group_id);

    public function removeUser($group_id, $user_id);

    public function userGroups($user_id);

//    public function groupMembers($group_id);

    public const seconds = 1;


}

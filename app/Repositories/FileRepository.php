<?php

namespace App\Repositories;

use App\Contracts\FileInterface;
use App\Models\File;
use App\Models\FileGroup;
use App\Models\Group;
use Illuminate\Support\Facades\Cache;

class FileRepository implements FileInterface
{
    public function store($data)
    {
        return File::query()->create($data);
    }

    public function addFileToGroup($file_id, $group_id)
    {
        return FileGroup::query()
            ->create([
                'file_id' => $file_id, 'group_id' => $group_id
                , 'created_at' => now()->addHours(3), 'updated_at' => now()->addHours(3)
            ]);
    }

    public function groupFiles($group_id)
    {
        return Cache::remember('group', self::seconds, function () use ($group_id) {
            return Group::query()->with(['files'])->findOrFail($group_id);
        });
    }

    public function checkIn($file_id, $user_id)
    {
        return File::query()->where('id', $file_id)->update([
            'locked' => true,
            'locked_by' => $user_id,
        ]);
    }

    public function checkOut(File $file, $link)
    {
        return $file->update([
            'locked' => false,
            'locked_by' => null,
            'link' => $link
        ]);
    }

    public function show($file_id, $relations)
    {
        if ($relations)
            return Cache::remember('file', self::seconds, function () use ($file_id) {
                return File::query()->with(['owner', 'locker'])->findOrFail($file_id);
            });
        else
            return Cache::remember('file', self::seconds, function () use ($file_id) {
                return File::query()->findOrFail($file_id);
            });
    }

    public function delete($file_id)
    {
        $file = File::query()->findOrFail($file_id);
        return $file->delete();
    }
}

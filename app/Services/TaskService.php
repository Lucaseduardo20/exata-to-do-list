<?php

namespace App\Services;

use App\Models\Task;

class TaskService
{
    public function list(string $user_id)
    {
        return Task::query()->where('user_id', $user_id)->get();
    }
}

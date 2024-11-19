<?php

namespace App\Services;

use App\Data\TaskData;
use App\Enums\TaskStatusEnum;
use App\Models\Task;
use Illuminate\Support\Collection;

class TaskService
{
    public function list(string $user_id): Collection
    {
        return TaskData::toCollection(Task::query()->where('user_id', $user_id)->get());
    }

    public function create(array $data): TaskData
    {

        $newTask = new Task();
        $newTask->user_id = auth()->user()->id;
        $newTask->title = $data['title'];
        $newTask->description = $data['description'];
        $newTask->status = TaskStatusEnum::PENDING;
        $newTask->save();

        return TaskData::fromTask($newTask);

    }
}

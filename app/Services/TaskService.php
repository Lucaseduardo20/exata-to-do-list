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
        $newTask->status = TaskStatusEnum::PENDING->value;
        $newTask->save();

        return TaskData::fromTask($newTask);

    }

    public function edit(array $data): TaskData
    {
        $task = Task::find($data['id']);
        $task->title = $data['title'];
        $task->description = $data['description'];
        $task->save();

        return TaskData::fromTask($task);
    }

    public function done(int $task_id): TaskData
    {
        $task = Task::find($task_id);
        $task->status = TaskStatusEnum::DONE->value;
        $task->save();

        return TaskData::toRequest($task);
    }

    public function deleteTask(int $task_id): void
    {
        $task = Task::find($task_id);
        $task->delete();
    }
}

<?php

namespace App\Data;

use App\Enums\TaskStatusEnum;
use App\Models\Task;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class TaskData extends Data
{

    public function __construct(
        public int $id,
        public string $title,
        public string $description,
        public string $status,
        public null|string $user_name

    )
    {}


    public static function fromTask(Task $task): self
    {
        return new self (
            id: $task->id,
            title: $task->title,
            description: $task->description,
            status: TaskStatusEnum::from($task->status)->formatted(),
            user_name: null
        );
    }

    public static function toRequestAdmin(Task $task): self
    {
        return new self (
            id: $task->id,
            title: $task->title,
            description: $task->description,
            status: TaskStatusEnum::from($task->status)->formatted(),
            user_name: $task->user->name
        );
    }

    public static function toCollection($tasks)
    {
        return $tasks->map(function (Task $task) {
            return self::fromTask($task);
        });
    }

    public static function toCollectionAdmin($tasks)
    {
        return $tasks->map(function (Task $task) {
            return self::toRequestAdmin($task);
        });
    }
}

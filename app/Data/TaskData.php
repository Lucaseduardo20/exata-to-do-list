<?php

namespace App\Data;

use App\Enums\TaskStatusEnum;
use App\Models\Task;
use Spatie\LaravelData\Data;

class TaskData extends Data
{

    public function __construct(
        public int $id,
        public string $title,
        public string $description,
        public TaskStatusEnum $status
    )
    {}


    public static function fromTask(Task $task)
    {
        return new self (
            id: $task->id,
            title: $task->title,
            description: $task->description,
            status: TaskStatusEnum::from($task->status)
        );
    }

    public static function toCollection($tasks)
    {
        return $tasks->map(function (Task $task) {
            return self::fromTask($task);
        });
    }
}

<?php

namespace Database\Factories;

use App\Enums\RoleEnum;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Task;

class TaskFactory extends Factory
{

    /**
     * @inheritDoc
     */
    protected $model = Task::class;

    public function definition()
    {
        return [
            'user_id' => User::query()->where('role', RoleEnum::USER->value)->first()->id,
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'status' => $this->faker->randomElement(['pending', 'in-progress', 'done']),
        ];
    }
}

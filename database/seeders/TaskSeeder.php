<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;

class TaskSeeder extends Seeder
{
    protected $model = Task::class;

    public function run()
    {
        Task::factory( 10)->create();
    }
}

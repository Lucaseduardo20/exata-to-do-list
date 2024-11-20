<?php

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Task;

class TasksTest extends TestCase
{

    use RefreshDatabase;
    public function test_create_task(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
        ]);

        $this->actingAs($user);
        $response = $this->post('/tasks/create', [
            'title' => 'Teste feature de criar tarefa',
            'description' => 'Esse teste está testando se a tarefa está criando de fato e se a request retorna status code 201'
        ]);

        $hasTask = Task::query()->where('user_id', $user->id)->exists();
        $response->assertStatus(201);
        $this->assertTrue($hasTask);
    }

    public function test_list_tasks(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
        ]);

        $tasks = Task::factory(10)->for($user)->create();

        $retrievedTasks = Task::query()
            ->whereHas('user', fn ($query) => $query->where('id', $user->id))
            ->get();

        $this->assertEquals($tasks->pluck('id')->sort()->values(), $retrievedTasks->pluck('id')->sort()->values());
    }
}

<?php

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Task;

class TasksTest extends TestCase
{
    use RefreshDatabase;

    public User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'email' => 'test@example.com',
        ]);

        $this->actingAs($this->user);
    }

    public function test_create_task(): void
    {
        $response = $this->post('/tasks/create', [
            'title' => 'Teste feature de criar tarefa',
            'description' => 'Esse teste está testando se a tarefa está criando de fato e se a request retorna status code 201'
        ]);

        $hasTask = Task::query()->where('user_id', $this->user->id)->exists();
        $response->assertStatus(201);
        $this->assertTrue($hasTask);
    }

    public function test_list_tasks(): void
    {
        $tasks = Task::factory(10)->for($this->user)->create();

        $retrievedTasks = Task::query()
            ->whereHas('user', fn ($query) => $query->where('id', $this->user->id))
            ->get();

        $response = $this->get('/tasks/' . $this->user->id);
        $response->assertStatus(200);
        $this->assertEquals($tasks->pluck('id')->sort()->values(), $retrievedTasks->pluck('id')->sort()->values());
    }

    public function test_update_task(): void
    {
        $task = Task::factory()->for($this->user)->create();

        $response = $this->put('/tasks/update', [
            'id' => $task->id,
            'title' => 'Novo título só para teste',
            'description' => 'Nova descrição só para teste.',
        ]);

        $response->assertStatus(201);
        $response->assertJson([
            'message' => 'Tarefa editada com sucesso!'
        ]);
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Novo título só para teste',
            'description' => 'Nova descrição só para teste.',
        ]);

    }
}

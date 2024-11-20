<?php

namespace Tests\Feature;

use App\Enums\RoleEnum;
use App\Models\Task;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected User $admin;


    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'role' => RoleEnum::USER->value,
        ]);

        $this->actingAs($this->user);

        $this->admin = User::factory()->create([
            'role' => RoleEnum::ADMIN->value,
        ]);
    }

    public function test_admin_can_list_users()
    {
        User::factory()->count(5)->create();

        $response = $this->actingAs($this->admin)->getJson(route('list-users'));

        $response->assertOk();
        $response->assertJsonCount(7, 'users');
    }

    public function test_admin_can_list_tasks()
    {
        Task::factory()->count(3)->create();

        $response = $this->actingAs($this->admin)->getJson(route('list-tasks'));

        $response->assertOk();
        $response->assertJsonCount(3, 'tasks');
    }

    public function test_admin_can_delete_user()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($this->admin)
            ->postJson(route('delete-user'), ['id' => $user->id]);

        $response->assertOk()->assertJson(['message' => 'Usuário deletado com sucesso!']);
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_admin_can_update_user()
    {
        $user = User::factory()->create();

        $updateData = [
            'id' => $user->id,
            'email' => 'updated@example.com',
            'name' => 'Updated Name',
        ];

        $response = $this->actingAs($this->admin)
            ->postJson(route('update-user'), $updateData);

        $response->assertOk()->assertJson(['message' => 'Usuário atualizado com sucesso!']);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => $updateData['email'],
            'name' => $updateData['name'],
        ]);
    }

    public function test_admin_can_promote_user_to_admin()
    {
        $user = User::factory()->create(['role' => RoleEnum::USER->value]);

        $response = $this->actingAs($this->admin)
            ->postJson(route('promote-user'), ['id' => $user->id]);

        $response->assertOk()->assertJson(['message' => 'Usuário promovido com sucesso!']);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'role' => RoleEnum::ADMIN->value,
        ]);
    }
}

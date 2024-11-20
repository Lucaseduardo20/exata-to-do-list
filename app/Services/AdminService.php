<?php

namespace App\Services;

use App\Data\TaskData;
use App\Data\UserData;
use App\Enums\RoleEnum;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Collection;

class AdminService
{
    public function list_users(): Collection
    {
        return UserData::toCollection(User::all());
    }

    public function list_tasks(): Collection
    {
        Return TaskData::toCollectionAdmin(Task::all());
    }

    public function delete_user(int $user_id)
    {
        return User::find($user_id)->delete();
    }

    public function edit_user(array $data): UserData
    {
        $user = User::find($data['id']);
        $user->email = $data['email'];
        $user->name = $data['name'];

        $user->save();

        return UserData::from($user);
    }

    public function promote_user(int $user_id): void
    {
        $user = User::find($user_id);
        $user->role = RoleEnum::ADMIN->value;

        $user->save();
    }
}

<?php

namespace App\Data;

use Illuminate\Support\Facades\Hash;
use Spatie\LaravelData\Data;
use App\Models\User;

class UserData extends Data
{
    public function __construct(
        public int $id,
        public string $name,
        public string $email,
        public string $role,
        public string $f_role
    )
    {}

    public static function fromUser(User $user)
    {
        return new self (
            id: $user->id,
            name: $user->name,
            email: $user->email,
            role: $user->role,
            f_role: $user->f_role
        );
    }

    public static function toCollection($users)
    {
        return $users->map(function (User $user) {
            return self::from($user);
        });
    }
}

<?php

namespace App\Data;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;
use Spatie\LaravelData\Attributes\Validation\Exists;
use App\Models\User;

class LoginData extends Data
{
    public function __construct(
        public string $email,
        public string $password
    ) {}

    public static function rules(ValidationContext $context): array
    {
        return [
            'email' => ['required', 'email', new Exists(User::class)],
            'password' => ['required', 'min:6'],
        ];
    }
}

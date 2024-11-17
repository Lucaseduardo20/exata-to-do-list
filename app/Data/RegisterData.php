<?php

namespace App\Data;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class RegisterData extends Data
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
    ) {}

    public static function rules(ValidationContext $context): array
    {
        return [
            'name' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'min:6'],
        ];
    }
}

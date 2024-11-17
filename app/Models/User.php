<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Data\LoginData;
use App\Data\RegisterData;
use App\Enums\RoleEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function login (LoginData $credentials): Response | RedirectResponse
    {
        if (!Auth::attempt($credentials->toArray())) {
            return back()->withErrors([
                'others' => 'Credenciais inválidas.'
            ])->setStatusCode(403);
        }
        return Inertia::render('Login', [
            'user' => $this
        ]);
    }

    public function register(RegisterData $data): Response
    {
        $this->name = $data->name;
        $this->email = $data->email;
        $this->password = Hash::make($data->password);
        $this->role = RoleEnum::USER;
        $this->save();

        return Inertia::render('Auth/Login', [
            'message' => 'Usuário criado com sucesso!'
        ]);
    }
}

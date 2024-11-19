<?php

namespace App\Http\Controllers\Auth;

use App\Data\LoginData;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Data\RegisterData;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function store(LoginData $credentials)
    {
        return (new User())->login($credentials);
    }

    public function index(RegisterData $data)
    {
        return (new User())->register($data);
    }

    public function destroy()
    {
        return Auth::user()->logout();
    }
}

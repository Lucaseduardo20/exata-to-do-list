<?php

namespace App\Http\Controllers\Auth;

use App\Data\LoginData;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function store(LoginData $credentials)
    {
        return (new User())->login($credentials);
    }
}

<?php

namespace App\Http\Controllers;

use App\Services\TaskService;
use Illuminate\Http\Request;

class TasksController extends Controller
{
    public function __construct(private readonly TaskService $service)
    {
    }

    public function show(Request $request)
    {
        return $this->service->list($request->user_id);
    }
}

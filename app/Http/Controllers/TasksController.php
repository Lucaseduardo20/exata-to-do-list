<?php

namespace App\Http\Controllers;

use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class TasksController extends Controller
{
    public function __construct(private readonly TaskService $service)
    {
    }

    public function show(Request $request): Collection
    {
        return $this->service->list($request->user_id);
    }

    public function store(Request $request): JsonResponse
    {
        $this->service->create($request->all());

        return response()->json([
            'message' => 'Tarefa criada com sucesso!',
        ], 201);
    }

    public function update(Request $request): JsonResponse
    {
        $this->service->edit($request->all());

        return response()->json([
            'message' => 'Tarefa editada com sucesso!',
        ], 201);
    }
}

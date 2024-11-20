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

    public function show(Request $request): JsonResponse
    {
        $tasks = $this->service->list($request->user_id);

        return response()->json([
            'tasks' => $tasks,
        ], 200);
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

    public function doneTask(Request $request): JsonResponse
    {
        $this->service->done($request->get('id'));

        return response()->json([
            'message' => 'Tarefa concluída com sucesso!',
        ], 201);
    }

    public function deleteTask(Request $request): JsonResponse
    {
        $this->service->deleteTask($request->get('id'));

        return response()->json([
            'message' => 'Tarefa deletada com sucesso!',
        ], 200);
    }
}

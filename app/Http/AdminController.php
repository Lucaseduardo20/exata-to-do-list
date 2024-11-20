<?php

namespace App\Http;

use App\Data\UserData;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\AdminService;

class AdminController extends Controller
{


    public function __construct(private readonly AdminService $service)
    {
    }

    public function listUsers(): JsonResponse
    {
        $users = $this->service->list_users();

        return response()->json([
            'users' => $users
        ], 200);
    }

    public function listTasks(): JsonResponse
    {
        $tasks = $this->service->list_tasks();

        return response()->json([
            'tasks' => $tasks
        ], 200);
    }

    public function deleteUser(Request $request): JsonResponse
    {
        $this->service->delete_user($request->get('id'));

        return response()->json([
            'message' => 'Usuário deletado com sucesso!'
        ], 200);
    }

    public function updateUser(Request $request): JsonResponse
    {
        $this->service->edit_user($request->all());

        return response()->json([
            'message' => 'Usuário atualizado com sucesso!'
        ], 200);
    }

    public function promoteUser(Request $request)
    {
        $this->service->promote_user($request->get('id'));

        return response()->json([
            'message' => 'Usuário promovido com sucesso!'
        ], 200);
    }

}

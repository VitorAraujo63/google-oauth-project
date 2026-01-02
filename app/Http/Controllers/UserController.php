<?php

namespace App\Http\Controllers;

use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['search']);

        $users = $this->userRepository->getList($filters);

        return response()->json($users);
    }
}

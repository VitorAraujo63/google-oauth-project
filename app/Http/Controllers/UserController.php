<?php

namespace App\Http\Controllers;

use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\UserResource;
use App\Models\User;

class UserController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index(Request $request)
    {
        $query = User::query();

        if ($request->has('search')) {
            $search = $request->input('search');
            $numbersOnly = preg_replace('/\D/', '', $search);

            $query->where(function($q) use ($search, $numbersOnly) {

                if (!empty($numbersOnly)) {

                    $safeCpfPrefix = substr($numbersOnly, 0, 4);

                    $q->orWhere('cpf', 'like', "{$safeCpfPrefix}%");
                }

                if (!ctype_digit(str_replace(['.', '-'], '', $search))) {
                    $q->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
                }
            });
        }

        $users = $query->orderBy('id', 'asc')->paginate(10);

        return UserResource::collection($users);
    }
}

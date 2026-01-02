<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\CompleteRegistrationRequest;

class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    // Vue chama essa rota para saber para onde mandar o usuário
    public function getGoogleAuthUrl(): JsonResponse
    {
        $url = $this->authService->getGoogleAuthUrl();
        return response()->json(['url' => $url]);
    }

    // Google devolve o usuário para cá
    public function handleGoogleCallback(): RedirectResponse
    {
        try {
            $data = $this->authService->handleGoogleCallback();

            $token = $data['token'];

            $frontendUrl = config('app.frontend_url') . '/auth/callback';

            return redirect("{$frontendUrl}?token={$token}");

        } catch (\Exception $e) {
            // Se der erro, manda pro login do front com erro
            return redirect(config('app.frontend_url') . '/login?error=auth_failed');
        }
    }

    public function completeRegistration(CompleteRegistrationRequest $request): JsonResponse
    {
        $user = $this->authService->completeRegistration(
            $request->user(),
            $request->validated()
        );

        return response()->json([
            'message' => 'Registration completed successfully',
            'user' => $user
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

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

            // Redireciona para o Front-end passando o token na URL
            // O Vue vai ler ?token=XYZ, salvar no localStorage e limpar a URL
            $frontendUrl = config('app.frontend_url') . '/auth/callback';

            return redirect("{$frontendUrl}?token={$token}");

        } catch (\Exception $e) {
            // Se der erro, manda pro login do front com erro
            return redirect(config('app.frontend_url') . '/login?error=auth_failed');
        }
    }
}

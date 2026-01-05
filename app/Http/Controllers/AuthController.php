<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\CompleteRegistrationRequest;
use App\Http\Resources\UserResource;
use App\Jobs\SendWelcomeEmailJob;

class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function getGoogleAuthUrl(): JsonResponse
    {
        $url = $this->authService->getGoogleAuthUrl();
        return response()->json(['url' => $url]);
    }

    public function handleGoogleCallback(): RedirectResponse
    {
        try {
            $data = $this->authService->handleGoogleCallback();

            $token = $data['token'];

            $frontendUrl = config('app.frontend_url') . '/auth/callback';

            return redirect("{$frontendUrl}?token={$token}");

        } catch (\Exception $e) {
            return redirect(config('app.frontend_url') . '/login?error=auth_failed');
        }
    }

    public function completeRegistration(CompleteRegistrationRequest $request): JsonResponse
    {
        $user = $this->authService->completeRegistration(
            $request->user(),
            $validatedData = $request->validated()
        );

        $user->update($validatedData);
        SendWelcomeEmailJob::dispatch($user);

        return response()->json([
            'message' => 'Cadastro concluído',
            'user' => new UserResource($user)
        ]);
    }
}

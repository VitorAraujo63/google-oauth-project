<?php

namespace App\Services;

use App\Jobs\SendWelcomeEmailJob;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Laravel\Socialite\Facades\Socialite;

class AuthService
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getGoogleAuthUrl(): string
    {
        return Socialite::driver('google')
            ->stateless()
            ->scopes(['email', 'profile', 'https://www.googleapis.com/auth/gmail.send'])
            ->with(['access_type' => 'offline', 'prompt' => 'consent'])
            ->redirect()
            ->getTargetUrl();
    }

    public function handleGoogleCallback(): array
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        $data = [
            'name' => $googleUser->getName(),
            'email' => $googleUser->getEmail(),
            'google_id' => $googleUser->getId(),
            'avatar' => $googleUser->getAvatar(),
            'google_token' => $googleUser->token,
            'google_refresh_token' => $googleUser->refreshToken,
        ];

        $user = $this->userRepository->updateOrCreateGoogleUser($data);

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token
        ];
    }

    /**
     * Recebe o usuário atual e os dados do formulário (CPF/Data)
     * e delega para o repositório salvar.
     */
    public function completeRegistration(User $user, array $data): User
    {

        $updatedUser = $this->userRepository->completeRegistration($user->id, $data);

        SendWelcomeEmailJob::dispatch($updatedUser);

        return $updatedUser;
    }
}

<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;

class EloquentUserRepository implements UserRepositoryInterface
{
    public function findByGoogleId(string $googleId)
    {
        return User::where('google_id', $googleId)->first();
    }

    public function updateOrCreateGoogleUser(array $data): User
    {
        return User::updateOrCreate(
            ['email' => $data['email']],
            [
                'name' => $data['name'],
                'google_id' => $data['google_id'],
                'avatar' => $data['avatar'],
                'google_token' => $data['google_token'],
                'google_refresh_token' => $data['google_refresh_token'] ?? null,
            ]
        );
    }

    public function completeRegistration(int $id, array $data): \App\Models\User
    {
        $user = User::find($id);

        if (! $user) {
            throw new \Exception('Usuário não encontrado.');
        }

        $user->update([
            'cpf' => $data['cpf'],
            'birth_date' => $data['birth_date'],
        ]);

        return $user;
    }

    public function getList(array $filters)
    {
        $query = User::query();

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $numbersOnly = preg_replace('/\D/', '', $search);

            $query->where(function ($q) use ($search, $numbersOnly) {
                if (! empty($numbersOnly) && strlen($numbersOnly) <= 4) {
                    $q->orWhere('cpf', 'like', "{$numbersOnly}%");
                }

                $q->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('id', 'asc')->paginate(10);
    }
}

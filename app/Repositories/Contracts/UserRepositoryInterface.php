<?php

namespace App\Repositories\Contracts;

interface UserRepositoryInterface
{
    public function findByGoogleId(string $googleId);
    public function updateOrCreateGoogleUser(array $data);
    public function completeRegistration(int $id, array $data);
    public function getList(array $filters);
}

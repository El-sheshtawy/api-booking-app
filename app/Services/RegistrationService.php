<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegistrationService
{
    public function createNewUser(array $userData)
    {
        $user = User::create([
            'name' => $userData['name'],
            'email' => $userData['email'],
            'password' => Hash::make($userData['password']),
        ]);

        $user->assignRole((int) $userData['role_id']);

        return $user;
    }

    public function createUserToken(User $user): string
    {
        return $user->createToken('client')->plainTextToken;
    }
}

<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;

class UserRepository implements UserRepositoryInterface
{

    public function createUser(array $userDetails)
    {
        return User::create($userDetails);
    }

    public function addBalance($balance, $user_id){
        $user = User::where('id', $user_id)->firstOrFail();

        return $user->update(['balance' => $user->balance + $balance]);
    }
}
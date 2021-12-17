<?php

namespace App\Interfaces;

interface UserRepositoryInterface 
{
    public function createUser(array $details);
    public function addBalance($balance, $user_id);
}
<?php

namespace App\Interfaces;

interface PromotionCodeRepositoryInterface 
{
    public function createCode(array $details);
    public function getCodes();
    public function getCode($id);
    public function getCodeByCode($code);
    public function assign($code_id, $user_id);
    public function checkIfCodeExists($code);

}
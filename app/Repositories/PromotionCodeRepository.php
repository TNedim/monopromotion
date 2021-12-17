<?php

namespace App\Repositories;

use App\Interfaces\PromotionCodeRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Models\PromotionCode;
use App\Models\UsedPromotionCode;

class PromotionCodeRepository implements PromotionCodeRepositoryInterface
{

    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository){
        $this->userRepository = $userRepository;
    }

    public function createCode(array $details)
    {
        return PromotionCode::create($details);
    }
    
    public function getCodes(){
        return PromotionCode::with('users')->get();
    }

    public function getCode($id){
        return PromotionCode::where('id', $id)->with('users')->first();
    }

    public function getCodeByCode($code){
        return PromotionCode::where('code', $code)->with('users')->first();
    }

    public function assign($code_id, $user_id){
        UsedPromotionCode::create([
            'user_id' => $user_id,
            'promotion_code_id' => $code_id,
        ]);
        $code = PromotionCode::where('id', $code_id)->first();
        return $this->userRepository->addBalance($code->amount, $user_id);
    }

    public function checkIfCodeExists($code){
        return !!PromotionCode::where('code', $code)->count();
    }
}
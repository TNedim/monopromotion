<?php

namespace App\Http\Controllers;

use App\Interfaces\PromotionCodeRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PromotionCodeController extends Controller
{
    private PromotionCodeRepositoryInterface $promotionCodeRepository;

    public function __construct(PromotionCodeRepositoryInterface $promotionCodeRepository)
    {
        $this->middleware('auth:api');
        $this->promotionCodeRepository = $promotionCodeRepository;
    }

    public function createCode(Request $request){
        $validator = Validator::make($request->all(), [
            'start_date' => 'required|date_format:Y-m-d H:i',
            'end_date' => 'required|date_format:Y-m-d H:i',
            'amount' => 'required|numeric',
            'quota' => 'required|integer'
        ]);
        if ($validator->fails()) return response()->json($validator->errors(), 422);

        do{
            $code = Str::random(12);
        }while($this->promotionCodeRepository->checkIfCodeExists($code));
        
        return response()->json($this->promotionCodeRepository->createCode(array_merge($request->all(), ['code' => $code])));
    }

    public function viewCodes(){
        return response()->json($this->promotionCodeRepository->getCodes());
    }

    public function viewCode($id){
        return response()->json($this->promotionCodeRepository->getCode($id));
    }

    public function assign(Request $request){
        $validator = Validator::make($request->all(), [
            'code' => 'required'
        ]);
        if ($validator->fails()) return response()->json($validator->errors(), 422);

        if(!$this->promotionCodeRepository->checkIfCodeExists($request->code)){
            return response()->json(['message' => 'Promotion Code Not Found']);
        }

        $code = $this->promotionCodeRepository->getCodeByCode($request->code);
        if($code->users->contains('id', auth()->id())){
            return response()->json(['message' => 'You have already used this code!']);
        }

        if($code->is_date_expired){
            return response()->json(['message' => 'This code is expired at '. $code->end_date .'!']);
        }

        if($code->is_date_started){
            return response()->json(['message' => 'This code will be available at '. $code->start_date .'!']);
        }

        if($code->is_quota_full){
            return response()->json(['message' => 'The quota is fully used for this code!']);
        }

        $this->promotionCodeRepository->assign($code->id, auth()->id());
        return response()->json(['message' => 'Code appied successfully, your balance is updated accordingly!']);
    }

}

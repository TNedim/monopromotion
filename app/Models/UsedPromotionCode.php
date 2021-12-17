<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsedPromotionCode extends Model
{
    protected $fillable = ['promotion_code_id', 'user_id'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function code(){
        return $this->belongsTo(PromotionCode::class);
    }
}

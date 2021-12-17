<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromotionCode extends Model
{

    protected $fillable = [
        'code',
        'start_date',
        'end_date',
        'quota',
        'amount'
    ];

    public function users(){
        return $this->hasManyThrough(User::class, UsedPromotionCode::class,'promotion_code_id','id','id','user_id');
    }

    public function getIsQuotaFullAttribute(){
        return $this->users()->count() >= $this->quota;
    }

    public function getIsDateExpiredAttribute(){
        $now = date('Y-m-d H:i');
        return strtotime($this->end_date) < strtotime($now); 
    }

    public function getIsDateStartedAttribute(){
        $now = date('Y-m-d H:i');
        return strtotime($this->start_date) > strtotime($now); 
    }

}

<?php
namespace app\api\model;

class StoreOwner extends BaseModel{

    protected $field = ['id','owner_name','owner_tel','owner_identity_f','owner_identity_r','store_order_id','car_id','car_number_id','count','order_no_sub','order_status','takeover','takeover_tel','spare_status','reason','status','create_time'];
    protected $autoWriteTimestamp = true;
    protected $updateTime = false;

    public function carDetails(){
        return $this->belongsTo(CarDetails::class,'car_id','id');
    }

    public function carNumber(){
        return $this->belongsTo(CarNumber::class,'car_number_id','id');
    }

}
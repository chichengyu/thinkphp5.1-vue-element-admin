<?php
namespace app\api\model;

class CarAttr extends BaseModel{

    protected $field = ['id','car_id','attr'];

    public function CarDetails(){
        return $this->belongsTo(CarDetails::class,'car_id','id');
    }

    public function getAttrAttr($value){
        return json_decode($value);
    }
}
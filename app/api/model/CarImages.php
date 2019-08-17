<?php
namespace app\api\model;

class CarImages extends BaseModel{

    protected $field = ['id','car_id','image','type','create_time','update_time'];

    protected $autoWriteTimestamp = 'datetime';

    public function carDetails(){
        return $this->belongsTo(CarDetails::class,'car_id','id');
    }
}


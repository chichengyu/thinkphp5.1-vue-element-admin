<?php
namespace app\api\model;

class CarBrand extends BaseModel{

    protected $field = ['id','name','logo_url','letter','is_hot','create_time'];

    public function CarDetails(){
        return $this->hasMany(CarDetails::class,'brand_id','id');
    }
}
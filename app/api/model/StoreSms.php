<?php
namespace app\api\model;

class StoreSms extends BaseModel{

    protected $field = ['id','store_order_id','store_id','is_del','created_time'];
    protected $autoWriteTimestamp = true;
    protected $updateTime = false;
}
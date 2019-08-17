<?php
namespace app\api\model;

class StoreLogistics extends BaseModel{

    protected $field = ['id','company','linkman','linkman_tel','vin','scheduled_time','status','create_time','update_time','delete_time'];
    protected $autoWriteTimestamp = true;
}
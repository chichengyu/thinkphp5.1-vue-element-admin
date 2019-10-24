<?php
namespace app\api\model;

class IdentityCard extends BaseModel
{
    protected $field = ['id','loan_id','name','id_number','province','city','district','detailed_address','issuing_authority','is_valid','begin_date','over_date','creation_time'];

}


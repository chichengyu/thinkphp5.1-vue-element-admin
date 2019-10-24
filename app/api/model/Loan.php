<?php
namespace app\api\model;

class Loan extends BaseModel
{
    protected $field = ['id','administrators_id','name','id_number','tel','account_payee','email','identity_front','identity_reverse','bank_card_front','bank_card_reverse','driving_license','marriage_state','source_province','source_city','product_id','borrowing_balance','contract_amount','periods','purpose','contract_date','loan_date','loan_bank','odd_numbers','remarks','is_weiqi','status','creation_time'];

    public function Administrators(){
        return $this->hasOne(Administrators::class,'id','administrators_id');
    }

    public function BasicInformation()
    {
        return $this->hasOne(BasicInformation::class,'loan_id','id');
    }

    public function IdentityCard()
    {
        return $this->hasOne(IdentityCard::class,'loan_id','id');
    }

    public function ClientRelation()
    {
        return $this->hasMany(ClientRelation::class,'loan_id','id');
    }

    public function CarInformation()
    {
        return $this->hasOne(CarInformation::class,'loan_id','id');
    }

    public function JobInformation()
    {
        return $this->hasOne(JobInformation::class,'loan_id','id');
    }

    public function Images()
    {
        return $this->hasMany(Images::class,'loan_id','id');
    }

    public function SourceCity()
    {
        return $this->hasOne(Address::class,'id','source_city');
    }
    public static function pages($page,$size,$where=[],$order='asc'){
        $data = self::with('Administrators,BasicInformation,IdentityCard,ClientRelation,CarInformation,JobInformation,Images,SourceCity')
            ->where($where)
            ->order('creation_time','desc')
            ->paginate($size,true,['page'=>$page]);
        $total = self::where($where)->count('id');
        return [
            'data'  => $data,
            'total' => $total
        ];
    }
}


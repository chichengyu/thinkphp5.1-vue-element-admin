<?php
namespace app\api\model;


class CarDetails extends BaseModel{

    protected $field = ['id','name','brand_id','years','month','cartype_id','classify','bsx','displacement','engine','country_id','level','carstyle','fuel','air','series','price_zdj','price_ptj','price_mdj','shou','yue','price_jxs','remarks','thumbnail','lightspot','print','status','is_rec','create_time','stime','xtime'];

    protected $autoWriteTimestamp = 'datetime';
    protected $updateTime = false;

    public function carImages(){
        return $this->hasMany(CarImages::class,'car_id','id');
    }

    public function carBrand(){
        return $this->belongsTo(CarBrand::class,'brand_id','id');
    }

    public function carType(){
        return $this->belongsTo(CarType::class,'cartype_id','id');
    }

    public function carAttr(){
        return $this->hasOne(CarAttr::class,'car_id','id');
    }

    public function carStock(){
        return $this->hasMany(CarNumber::class,'car_id','id');
    }


    public function getlightspotAttr($value,$data){
        return htmlspecialchars_decode($value);
    }


    /**
     * 分页查询
     */
    public static function pages($page,$size,$where=[]){
        $data = self::distinct(true)
            ->field('cd.*,cy.name type_name,cb.name brand_name,ca.attr car_attr,SUM(cu.car_number) car_number,group_concat(concat(cu.car_color,"-",cu.id,"-",cu.car_number)) car_color')
            ->alias('cd')
            ->with('carImages')
            ->join('car_type cy','cy.id=cd.cartype_id')
            ->join('car_brand cb','cb.id=cd.brand_id')
            ->join('car_attr ca','ca.car_id=cd.id','LEFT')
            ->join('car_number cu','cu.car_id=cd.id','LEFT')
            ->where($where)
            ->group('cd.id')
            ->order('cd.id asc')
            ->paginate($size,true,['page'=>$page]);
//        $data = self::with('carImages,carBrand,carType,carAttr')
//            ->where($where)
//            ->order('id asc')
//            ->paginate($size,true,['page'=>$page]);
        $total = self::distinct(true)
            ->field('cd.*,cy.name type_name,cb.name brand_name,ca.attr car_attr')
            ->alias('cd')
            ->with('carImages')
            ->join('car_type cy','cy.id=cd.cartype_id')
            ->join('car_brand cb','cb.id=cd.brand_id')
            ->join('car_attr ca','ca.car_id=cd.id','LEFT')
            ->where($where)
            ->order('cd.id asc')
            ->count('cd.id');
        $data = $data->toArray()['data'];
        if (count($data)){
            foreach ($data as $k=>&$v){
                $v['price_jxs'] && ($v['price_jxs'] = price($v['price_jxs'],false));
                $v['price_mdj'] && ($v['price_mdj'] = price($v['price_mdj'],false));
                $v['price_ptj'] && ($v['price_ptj'] = price($v['price_ptj'],false));
                $v['price_zdj'] && ($v['price_zdj'] = price($v['price_zdj'],false));
                $v['shou'] && ($v['shou'] = price($v['shou'],false));
                $v['yue'] && ($v['yue'] = price($v['yue'],false));
                $v['car_attr'] && ($v['car_attr'] = json_decode($v['car_attr']));
            }
        }
        return [
            'data'  => $data,
            'total' => $total
        ];
    }

    /**
     *  导出excel
     */
    public static function export($where){
        $data = self::distinct(true)
            ->field('cd.id,cd.name,cd.years,cd.month,cd.classify,cd.price_zdj,cd.price_mdj,cd.price_jxs,cd.remarks,cd.status,cd.create_time,cy.name type_name,cb.name brand_name,SUM(cu.car_number) car_number,group_concat(cu.car_color) car_color')
            ->alias('cd')
            //->with('carImages')
            ->join('car_type cy','cy.id=cd.cartype_id')
            ->join('car_brand cb','cb.id=cd.brand_id')
            //->join('car_attr ca','ca.car_id=cd.id','LEFT')
            ->join('car_number cu','cu.car_id=cd.id','LEFT')
            ->where($where)
            ->group('cd.id')
            ->order('cd.id asc')
            ->select();
        $classify = ['商品车','运损车','换代新车','库存车','采集车','活动车'];
        $status = ['待上架','上架','下架'];
        if (isset($data) && ($data = $data->toArray()) && count($data)){
            foreach ($data as $k=>&$v){
                $month = '';
                if ($v['month'])$month = ' / '. ($v['month'] < 10 ? '0'.$v['month']:$v['month']);
                $v['years'] = $v['years'].$month;
                $v['classify'] = $classify[$v['classify'] - 1];
                $v['status'] = $status[$v['status'] - 1];
            }
        }
        $Excel['fileName']="车亿百车源信息-".date('Y年m月d日-His',time());
        $Excel['cellName']=['A','B','C','D','E','F','G','H','I','J','K','L','M','N'];
        $Excel['H'] = ['A'=>10,'B'=>12,'C'=>14,'D'=>24,'E'=>12,'F'=>12,'G'=>14,'H'=>10,'I'=>14,'J'=>14,'K'=>14,'L'=>14,'M'=>10,'N'=>20];
        //$Excel['V'] = ['1'=>40,'2'=>23];
        $Excel['sheetTitle']="车亿百车源信息({$where[0][2][0]} / {$where[0][2][1]})~";
        $Excel['xlsCell']=[
            ['id','序号'],
            ['brand_name','品牌'],
            ['type_name','车型'],
            ['name','车辆名称'],
            ['car_color','车身颜色'],
            ['classify','车辆类型'],
            ['years','生产年份'],
            ['car_number','数量'],
            ['price_zdj','指导价（万) '],
            ['price_mdj','门店价（万) '],
            ['price_jxs','经销商价'],
            ['remarks','备注'],
            ['status','状态'],
            ['create_time','创建时间'],
        ];
        return [$Excel,$data];
    }
}
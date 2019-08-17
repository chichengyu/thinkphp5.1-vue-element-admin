<?php
namespace app\api\controller\v1;

use app\api\model\CarDetails as CarDetailsModel;
use app\api\model\CarBrand as CarBrandModel;
use app\api\model\CarType as CarTypeModel;
use app\api\model\CarCountry as CarCountryModel;

class CarDetails extends Base{

    /** get
     * 车辆列表
     */
    public function lst(){
        $page = input('get.page',1);
        $limit = input('get.limit',10);
        $keywords = input('get.keywords');
        $brand = input('get.brand');
        $classify = input('get.classify');
        $price = input('get.price');
        $date = input('get.date');
        $where = [];
        if (isset($keywords)){
            $where[] = ['cd.name','like',["%$keywords%"]];
        }
        if (isset($brand)){
            $where[] = ['cb.id','=',$brand];
        }
        if (isset($classify)){
            $where[] = ['cd.classify','=',$classify];
        }
        if (isset($price)){
            if (strpos($price,',') !== false){
                $price = explode(',',$price);
                $where[] = ['cd.price_zdj',['>',$price[0]],['<',$price[1]]];
            }else{
                $where[] = ['cd.price_zdj','>',$price];
            }
        }
        if (isset($date)){
            $date = explode(',',$date);
            $where[] = ['cd.create_time','between time',[$date[0],$date[1]]];
        }
        $data = CarDetailsModel::pages($page,$limit,$where);
        $catBrands = CarBrandModel::field('id,name')->all();
        $carType = CarTypeModel::field('id,name')->all();
        $carCountry = CarCountryModel::field('id,name')->all();
        if ($data){
            $newData['data'] = $data['data'];
            $newData['total'] = $data['total'];
            $newData['car_brands'] = $catBrands;
            $newData['car_type'] = $carType;
            $newData['car_country'] = $carCountry;
            return $this->res_json('success',1,true,$newData);
        }
        return $this->res_json('error');
    }
}


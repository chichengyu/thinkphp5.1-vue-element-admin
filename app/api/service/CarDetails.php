<?php
namespace app\api\service;

class CarDetails{


    /** 拼合上传多图的数据
     * @param $imagesss
     * @param $id
     * @return array
     */
    public static function handImageConcatData($imagess,$id){
        $carImageData = [];
        if ($imagess){
            $carImageData = self::imageConcatData($imagess['facade'],1,$id);
            $carImageData = array_merge($carImageData,self::imageConcatData($imagess['center'],2,$id));
            $carImageData = array_merge($carImageData,self::imageConcatData($imagess['seat'],3,$id));
            $carImageData = array_merge($carImageData,self::imageConcatData($imagess['detail'],4,$id));
        }
        return $carImageData;
    }
    // 合并上传多张图片的数据
    private static function imageConcatData($data=[],$typeVal,$carId){
        $newData = [];
        if (count($data)){
            foreach ($data as $k=>$v){
                $newData[$k]['image'] = $v;
                $newData[$k]['type'] = $typeVal;
                $newData[$k]['car_id'] = $carId;
            }
        }
        return $newData;
    }

    /** 处理编辑时上传图片数据筛选
     * @param $data
     */
    public static function handleEditData($imagess,$carData,$id){
        $ids = [];
        $carImageData = [];
        if (!is_array($imagess)){
            return [$ids,$carImageData];
        }
        $newData = [];
        if (array_key_exists('facade',$imagess) && count($imagess['facade'])){
            $newData = array_merge($newData,$imagess['facade']);
        }
        if (array_key_exists('center',$imagess) && count($imagess['center'])){
            $newData = array_merge($newData,$imagess['center']);
        }
        if (array_key_exists('seat',$imagess) && count($imagess['seat'])){
            $newData = array_merge($newData,$imagess['seat']);
        }
        if (array_key_exists('detail',$imagess) && count($imagess['detail'])){
            $newData = array_merge($newData,$imagess['detail']);
        }
        $images = [];
        foreach ($carData as $k => $v){
            $images[] = $v['image'];
            if (!in_array($v['image'],$newData)){
                $ids[] = $v['id'];
            }
        }
        if (count($images)){
            $self = new self();
            isset($imagess['facade']) && ($facade = array_udiff($imagess['facade'],$images,[$self,'carImages']));
            isset($imagess['center']) && ($center = array_udiff($imagess['center'],$images,[$self,'carImages']));
            isset($imagess['seat']) && ($seat = array_udiff($imagess['seat'],$images,[$self,'carImages']));
            isset($imagess['detail']) && ($detail = array_udiff($imagess['detail'],$images,[$self,'carImages']));

            isset($facade) && ($carImageData = self::imageConcatData($facade,1,$id));
            isset($center) && ($carImageData = array_merge($carImageData,self::imageConcatData($center,2,$id)));
            isset($seat) && ($carImageData = array_merge($carImageData,self::imageConcatData($seat,3,$id)));
            isset($detail) && ($carImageData = array_merge($carImageData,self::imageConcatData($detail,4,$id)));
        }
        return [$ids,$carImageData];
    }
    // 比較數組差集
    private static function carImages($a,$b)
    {
        if ($a===$b)
        {
            return 0;
        }
        return ($a>$b)?1:-1;
    }
}
<?php
namespace app\api\validate;

use think\facade\Request;
use app\lib\exception\ParamsException;
use think\Validate;

class BaseValidate extends Validate {

    // 验证参数
    public function goCheck()
    {
        $request = Request::instance();
        $params = $request->param();
        $result = $this->batch()->check($params);
        if ($result) {
            return true;
        } else {
            throw new ParamsException(array(
                'msg' => is_array($this->error)?implode(',',$this->error):$this->error
            ));
        }
    }
    // 验证是否正整数
    protected function isIdInteger($value,$rule='',$data='',$field='')
    {
        if (is_int($value + 0) && ($value+0) > 0) {
            return true;
        } else {
            return false;
        }
    }
    // 验证是否为空
    protected function isNotEmpty($value,$rule='',$data='',$field='')
    {
        if (empty($value)) {
            return false;
        }
        return true;
    }
    // 验证手机号
    public function isMobile($value)
    {
        $reg = '^1(3|4|5|7|8|9)[0-9]\d{8}$^';
        $result = preg_match($reg,$value);
        if ($result) {
            return true;
        }
        return false;
    }
    // 验证价格
    public function isPrivce($value,$rule,$data=[],$field=''){
        $reg = '/(^[1-9]\d*(\.\d{1,2})?$)|(^0(\.\d{1,2})?$)/';
        $result = preg_match_all($reg,$value);
        if ($result) {
            return true;
        }
        return false;
    }
    // 对提交的数据进行安全验证并过滤
    public function getDataByRule($data)
    {
        if (array_key_exists('user_id',$data) || array_key_exists('uid', $data)) {
            throw new ParamsException(array(
                'msg' => '参数中存在非法的参数名user_id或uid'
            ));
        }
        $newArray = array();
        foreach ($this->rule as $k => $v) {
            $newArray[$k] = $data[$k];
        }
        return $newArray;
    }
}
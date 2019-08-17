<?php
namespace qrcode;
require_once 'Phpqrcode.php';

class Qrcode {

    /**
     * @param $val 二维码内容
     * @param string $dir 缓存路径
     * @return string
     */
    public function png($val,$dir='weixin'){
        $path = '/static/qrcode';
        if ($this->del_dir($path)){
            $file = '.'.$path.'/'.date('YmdHis').'.png';
            \QRcode::png($val,$file);
            return ltrim($file,'.');
        }
    }

    /** 删除目录并创建新目录 （注意：需要 php 开启禁用函数 system）
     * @param $dir  目录路径
     * @return bool
     */
    private function del_dir($dir) {
        $path = $_SERVER['DOCUMENT_ROOT'] . $dir;
        if(strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') {
            $str = "rmdir /s/q " . str_replace('/','\\', $path);
        } else {
            $str = "rm -Rf " . $path;
        }
        // $status 0为成功 1为失败
        system($str,$status);
        $res = mkdir($path, 0777, true);
        if (is_dir($path) && $status==1) {
            return false;
        }
        if ($res) {
            return true;
        }
        return false;
    }
}
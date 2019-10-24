<?php
namespace app\api\controller;

use think\Controller;
use app\lib\ResponseJson;
use think\Request;

class BaseController extends Controller{
    use ResponseJson;

    /**
     *  上传图片或文件
     */
    public function upload(Request $request,$image)
    {
        if ($request->isPost()){
            $file = $request->file('file');
            $ext = 'jpg,png,gif';
            !$file->checkExt($ext)&&($ext = 'xlsx');
            $res = $this->imageUpload($file,$image,$ext);
            return json($res);
        }
    }

    /**
     * 删除上传图片
     */
    public function delUpload(Request $request)
    {
        if ($request->isPost()){
            $path = input('post.path');
            if (!$path){
                return $this->responseError('参数有误');
            }
            if ($this->delImage($path)){
                return $this->responseSuccess('删除成功');
            }
            return $this->responseError('删除失败');
        }
    }

    /** 上传一张图片
     * @param $file     文件对象
     * @param $dirPath  保存路径
     * @return array
     */
    public function imageUpload($file,$dirPath,$ext='jpg,png,gif',$size=1024*1024*2){
        $path = $_SERVER['DOCUMENT_ROOT'].'/';
        $subPath = 'static/uploads/'.$dirPath.'/'.date('Y-m');
        is_dir($path.$subPath) || mkdir($path.$subPath,0777,true);
        $info = $file->validate(['size'=>$size,'ext'=>$ext])->rule('uniqid')->move($path.$subPath);
        if ($info){
            return ['code' => 1,'msg' => '上传成功','path'=>'/'.$subPath.'/'.$info->getFilename(),'ivew_path' => httpHost().$_SERVER['HTTP_HOST'].'/'.$subPath.'/'.$info->getFilename()];
        }
        return ['code' => 0,'msg' =>'上传失败：' . $file->getError()];
    }

    /** 删除图片
     * @param $path  图片路径
     * @return bool
     */
    public function delImage($path){
        try{
            @unlink('.'.$path);
            return true;
        }catch (\Exception $e){
            return false;
        }
    }
}
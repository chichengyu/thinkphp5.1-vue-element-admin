<?php
namespace app\api\controller\v1;
use think\Request;

class Index extends Base{
    public function index()
    {
        return ;
    }

    /**
     *  广告上传
     */
    public function upload(Request $request)
    {
        if ($request->isPost()){
            $file = $request->file('file');
            $res = $this->imageUpload($file,'advert');
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
                return $this->res_json('参数有误');
            }
            if ($this->delImage($path)){
                return $this->res_json('删除成功',1);
            }
            return $this->res_json('删除失败');
        }
    }
}

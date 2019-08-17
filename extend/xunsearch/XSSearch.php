<?php
namespace xunsearch;
/**
 * Class XSSearch  全文檢索
 */

class XSSearch {

    /**
     * 中文分词搜索
     * @param string $keywords 关键词
     * @param string $file ini文件名
     * @param bool $is_scws 是否开启中文分词（例如：口袋新世代，拆分成：口袋、新、世代）
     * @param int $limit 搜索结果条数
     * @return array 返回结果
     * @throws \XSException
     */
    public static function xunsearch($keywords,$file = 'demo',$is_scws = false,$limit = 100){
        $xs = new \XS($file);
        if($is_scws === true) {
            //中文分词
            $tokenizer = new \XSTokenizerScws;
            //词语拆分
            $words = $tokenizer->getTokens($keywords);
            $where = '';
            //拼接成查询条件（OR）
            foreach ($words as $key => $val) {
                if ($key == 0) {
                    $where = $val;
                } else {
                    $where .= ' OR ' . $val;
                }
            }
        }else {
            $where = $keywords;
        }
        $result =  $xs->search->setQuery($where)
        //    ->setSort('id','asc') #按索引排序
            ->setDocOrder(true) #按添加索引排序（升序）
            ->setLimit($limit)
            ->search();
        $xs->search->close();

        return $result;
    }

    /**
     * 新增（更新）xunsearch数据库
     * @param array $data
     * @param string $file ini文件名
     * @return bool
     */
    public function xunsearchSave($data,$file = 'demo'){
        try {
            $xs = new \XS($file);
            #创建文档对象
            $doc = new \XSDocument;
            $doc->setFields($data);

            #更新（新增）数据
            $index = $xs->index;
            $index->update($doc);

            #强制刷新当前索引列表数据
            $result = $index->flushIndex();

            return $result;
        }catch (\Exception $e){
            return false;
        }
    }
}


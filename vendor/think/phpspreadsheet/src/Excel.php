<?php 
namespace think;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment as PHPExcel_Style_Alignment;
use PhpOffice\PhpSpreadsheet\Cell\DataType as PHPExcel_Cell_DataType;
use PhpOffice\PhpSpreadsheet\Style\Fill as PHPExcel_Style_Fill;
use PhpOffice\PhpSpreadsheet\Style\Border as Border;
use PhpOffice\PhpSpreadsheet\IOFactory;


class Excel {

	/*
	 * 导入数据
	 * @params String $path 上传的Excel文件路径(相对路径)
	 * @return Array 二维数组 
	*/
	public static function excelReader($path){
		$inputFileType = IOFactory::identify($path); //传入Excel路径
		$excelReader   = IOFactory::createReader($inputFileType); //Xlsx
		$PHPExcel      = $excelReader->load($path); // 载入excel文件
		$sheet         = $PHPExcel->getSheet(0); // 读取第一個工作表
		$sheetdata = $sheet->toArray();
		return $sheetdata; // --- 直接返回数组数据
	}

	/*
	 *	导出数据
	 *
	*/
	public static function excelPut($Excel,$expTableData,$path='',$type='xlsx'){
		// $Excel['sheetTitle']=iconv('utf-8', 'gb2312',$Excel['sheetTitle']);
        //  ------------- 文件参数 -------------
        $cellName = $Excel['cellName'];
        $xlsCell = $Excel['xlsCell'];
        $cellNum = count($xlsCell);//计算总列数
        $dataNum = count($expTableData);//计算数据总行数
        $spreadsheet = new Spreadsheet();
        $sheet0 = $spreadsheet->getActiveSheet();
        $sheet0->setTitle("Sheet1");
        //设置表格标题A1
        $sheet0->mergeCells('A1:'.$cellName[$cellNum-1].'1');//表头合并单元格

        // ------------- 表头 -------------
        // $sheet0->setCellValue('A1',"测试表头");
        $sheet0->setCellValue('A1',$Excel['sheetTitle'].date("YmdHis"));

        $sheet0->getStyle('A1')->getFont()->setSize(20);
        $sheet0->getStyle('A1')->getFont()->setName('微软雅黑');
        //设置行高和列宽
        // ------------- 横向水平宽度 -------------
        if(isset($Excel['H'])){
            foreach ($Excel['H'] as $key => $value) {
                $sheet0->getColumnDimension($key)->setWidth($value);
            }
        }

        // ------------- 纵向垂直高度 -------------
        if(isset($Excel['V'])){
            foreach ($Excel['V'] as $key => $value) {
                $sheet0->getRowDimension($key)->setRowHeight($value);
            }
        }

        // ------------- 第二行：表头要加粗和居中，加入颜色 -------------
        $sheet0->getStyle('A1')
        ->applyFromArray(['font' => ['bold' => false],'alignment' => ['horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER]]);
        $setcolor = $sheet0->getStyle("A2:".$cellName[$cellNum-1]."2")->getFill();
        $setcolor->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $colors=['00a000','53a500','3385FF','00a0d0','D07E0E','c000c0','0C8080','EFE4B0'];//设置总颜色
        $selectcolor=$colors[mt_rand(0,count($colors)-1)];//获取随机颜色
        $setcolor->getStartColor()->setRGB($selectcolor);

        // ------------- 根据表格数据设置列名称 -------------

        for($i=0;$i<$cellNum;$i++){
            $sheet0->setCellValue($cellName[$i].'2', $xlsCell[$i][1])
            ->getStyle($cellName[$i].'2')
            ->applyFromArray(['font' => ['bold' => true],'alignment' => ['horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER]]);
        }

        // ------------- 渲染表中数据内容部分 -------------

        for($i=0;$i<$dataNum;$i++){
            for($j=0;$j<$cellNum;$j++){
                $sheet0->getStyle($cellName[$j].($i+3))->applyFromArray(['alignment' => ['horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER]]);
                $sheet0->setCellValueExplicit($cellName[$j].($i+3),$expTableData[$i][$xlsCell[$j][0]],PHPExcel_Cell_DataType::TYPE_STRING);
                $sheet0->getStyle($cellName[$j].($i+3))->getNumberFormat()->setFormatCode("@");
            }
        }

        // ------------- 设置边框 -------------
        // $sheet0->getStyle('A2:'.$cellName[$cellNum-1].($i+2))->applyFromArray(['borders' => ['allborders' => ['style' => PHPExcel_Style_Border::BORDER_THIN]]]);

        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF505050'],
                ],
            ],
        ];

        $sheet0->getStyle('A2:'.$cellName[$cellNum-1].($i+2))->applyFromArray($styleArray);

        //$sheet0->setCellValue("A".($dataNum+10)," ");//多设置一些行

        // ------------- 输出 -------------
        $writer = new Xlsx($spreadsheet);
        if (empty($path)){
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');//告诉浏览器输出07Excel文件
            //header('Content-Type:application/vnd.ms-excel');//告诉浏览器将要输出Excel03版本文件
            header("Content-Disposition: attachment;filename=".$Excel['fileName'].".xlsx");//告诉浏览器输出浏览器名称
            header('Cache-Control: max-age=0');//禁止缓存
            $writer->save('php://output');
            exit;
        }else{
            $writer->save($path);
        }
	}

}
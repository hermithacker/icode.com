<?PHP
/*将数据库数据导入到Excel*/
require_once("config.php");
//返回 web 服务器和 PHP 之间的接口类型
define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

require_once 'include/PHPExcel_1.8.0/PHPExcel.php';

// Create new PHPExcel object
echo date('H:i:s') , " 创建一个PHPExcel对象" , EOL;
$objPHPExcel = new PHPExcel();
// Set document properties
echo date('H:i:s') , " 设置文档属性" , EOL;
$objPHPExcel->getProperties()->setCreator("Ron.Chen") //作者
							 ->setLastModifiedBy("Ron.Chen") //最后修改人
							 ->setTitle("iExcel") //标题
							 ->setSubject("iSubject")   //主题
							 ->setDescription("this is the mine first ") //文档描述
							 ->setKeywords("office PHPExcel php")	//文档关键字
							 ->setCategory("Test result file");	   //文档类别

// Add some data
echo date('H:i:s') , " 添加一些数据" , EOL;
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Hello')
            ->setCellValue('B2', 'world!')
            ->setCellValue('C1', 'Hello')
            ->setCellValue('D2', 'world!');

// Miscellaneous glyphs, UTF-8
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A4', 'Miscellaneous glyphs')
            ->setCellValue('A5', 'éàèùâêîôûëïüÿäöüç');

$objPHPExcel->getActiveSheet()->setCellValue('A8',"Hello\nWorld");
$objPHPExcel->getActiveSheet()->getRowDimension(8)->setRowHeight(-1);
$objPHPExcel->getActiveSheet()->getStyle('A8')->getAlignment()->setWrapText(true);
// Rename worksheet
echo date('H:i:s') , " 重命名当前的工作表" , EOL;
$objPHPExcel->getActiveSheet()->setTitle('Simple');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

// Save Excel 2007 file
echo date('H:i:s') , " 写入Excel 2007版本" , EOL;
$callStartTime = microtime(true);

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save(str_replace('.php', '.xlsx', __FILE__));
$callEndTime = microtime(true);
$callTime = $callEndTime - $callStartTime;

echo date('H:i:s') , " File written to " , str_replace('.php', '.xlsx', pathinfo(__FILE__, PATHINFO_BASENAME)) , EOL;
echo 'Call time to write Workbook was ' , sprintf('%.4f',$callTime) , " seconds" , EOL;
// Echo memory usage
echo date('H:i:s') , ' Current memory usage: ' , (memory_get_usage(true) / 1024 / 1024) , " MB" , EOL;


// Save Excel 95 file
echo date('H:i:s') , " Write to Excel5 format" , EOL;
$callStartTime = microtime(true);

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save(str_replace('.php', '.xls', __FILE__));
$callEndTime = microtime(true);
$callTime = $callEndTime - $callStartTime;

echo date('H:i:s') , " File written to " , str_replace('.php', '.xls', pathinfo(__FILE__, PATHINFO_BASENAME)) , EOL;
echo 'Call time to write Workbook was ' , sprintf('%.4f',$callTime) , " seconds" , EOL;
// Echo memory usage
echo date('H:i:s') , ' Current memory usage: ' , (memory_get_usage(true) / 1024 / 1024) , " MB" , EOL;


// Echo memory peak usage
echo date('H:i:s') , " Peak memory usage: " , (memory_get_peak_usage(true) / 1024 / 1024) , " MB" , EOL;

// Echo done
echo date('H:i:s') , " Done writing files" , EOL;
echo 'Files have been created in ' , getcwd() , EOL;


?>
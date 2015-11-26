<?php
/**
 
**/
 
//Various excel formats supported by PHPExcel library
$excel_readers = array(
    'Excel5' , 
    'Excel2003XML' , 
    'Excel2007'
);
 
require_once('/home/naren/Downloads/PHPExcel_1.8.0_doc/Classes/PHPExcel.php');
 
$reader = PHPExcel_IOFactory::createReader('Excel2007');
$reader->setReadDataOnly(true);
 
$path = '/home/naren/Downloads/FlatFileClothing.xlsx';
$excel = $reader->load($path);
 
$writer = PHPExcel_IOFactory::createWriter($excel, 'CSV');
$writer->save('/home/naren/Downloads/Tshirt.csv');
 
echo 'File saved to csv format';

?>
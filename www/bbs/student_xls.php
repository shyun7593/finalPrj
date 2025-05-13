<?php
define('G5_CERT_IN_PROG', true);
include_once('./_common.php');

$sql_add = " 1=1 ";
if(!$bid){

    switch($_SESSION['mb_profile']){
        case 'C40000001':
            $bid = "";
            break;
        case 'C40000002':
            $bid = $_SESSION['mb_signature'];
            break;
    }
}

if($bid){
    $sql_add .= " AND gb.idx = {$bid} ";
}

if($text){
    $sql_add .= " AND (gm.mb_name like '%{$text}%' OR replace(gm.mb_hp,'-','') like '%{$text}%' OR gm.mb_1 like '%{$text}%')";
}

$msql = " select *
    from g5_member gm
    LEFT JOIN g5_branch gb on
    gb.idx = gm.mb_signature
    where 
    {$sql_add}
    AND gm.mb_id NOT IN ( '{$member['mb_id']}')
    AND gm.mb_id != 'admin'
    AND gm.mb_profile in ('C40000003','C40000004')";
$result = sql_query($msql);
$data = array();
foreach($result as $k => $v){
    $gender = '';
    switch ($v['mb_sex']) {
        case 'M':
            $gender = '남';
            break;
        case 'F':
            $gender = '여';
            break;
    }
    $tmp_array = array(
                    'mb_id'=>$v['mb_id'],
                    'branchName'=>$v['branchName'],
                    'mb_name'=>$v['mb_name'],
                    'mb_1'=>$v['mb_1'],
                    'mb_2'=>$v['mb_2'],
                    'gender'=>$gender,
                    'mb_hp'=>hyphen_hp_number($v['mb_hp']),
                    'mb_birth'=>hyphen_birth_number($v['mb_birth'])
                );
    array_push($data,$tmp_array);
}
// $fname = tempnam(G5_DATA_PATH, "tmp-estimate.xls");
//$fname = iconv("UTF-8", "EUC-KR", $fname);

include_once(G5_LIB_PATH.'/PHPExcel.php');
$objPHPExcel = new PHPExcel();
// include_once(G5_LIB_PATH.'/PHPExcel/Classes/PHPExcel/IOFactory.php');

//include_once(RAON_LIB_PATH.'/Excel/php_writeexcel/class.writeexcel_worksheet.inc.php');

$objPHPExcel->createSheet();
$objPHPExcel->removeSheetByIndex(0);
$objPHPExcel->getActiveSheet()->setTitle( '학생리스트' );
$sheet = $objPHPExcel->getActiveSheet();
//엑셀 수정시 비밀번호를 입력해야된다.
// $objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
// $objPHPExcel->getActiveSheet()->getProtection()->setSort(true);
// $objPHPExcel->getActiveSheet()->getProtection()->setInsertRows(true);
// $objPHPExcel->getActiveSheet()->getProtection()->setFormatCells(true);
// $objPHPExcel->getActiveSheet()->getProtection()->setPassword("0000");
// 셀 합치기
//$sheet->mergeCells('A1:C1');
$sheet->getColumnDimension('A')->setWidth(20);// 셀 가로
$sheet->getColumnDimension('B')->setWidth(20);
$sheet->getColumnDimension('C')->setWidth(20);
$sheet->getColumnDimension('D')->setWidth(20);
$sheet->getColumnDimension('E')->setWidth(20);
$sheet->getColumnDimension('F')->setWidth(20);
$sheet->getColumnDimension('G')->setWidth(20);
$sheet->getColumnDimension('H')->setWidth(20);

//$sheet->getRowDimension(1)->setRowHeight(35);// 셀 높이

// 다중 셀 보더 스타일 적용

// foreach(range('A','H') as $i => $cell){
//     $sheet->getStyle($cell.'1')->applyFromArray( $headBorder );
// }

$sheet->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->setCellValueExplicit('A0', '123413123123', PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel -> setActiveSheetIndex(0)
-> setCellValue("A1", "아이디")
-> setCellValue("B1", "소속")
-> setCellValue("C1", "이름")
-> setCellValue("D1", "학교")
-> setCellValue("E1", "학년")
-> setCellValue("F1", "성별")
-> setCellValue("G1", "연락처")
-> setCellValue("H1", "생년월일");

$count = 2;
foreach($data as $k =>$v){
    $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValueExplicit("A$count", $v['mb_id'], PHPExcel_Cell_DataType::TYPE_STRING)
              ->setCellValueExplicit("B$count", $v['branchName'], PHPExcel_Cell_DataType::TYPE_STRING)
              ->setCellValueExplicit("C$count", $v['mb_name'], PHPExcel_Cell_DataType::TYPE_STRING)
              ->setCellValueExplicit("D$count", $v['mb_1'], PHPExcel_Cell_DataType::TYPE_STRING)
              ->setCellValueExplicit("E$count", $v['mb_2'], PHPExcel_Cell_DataType::TYPE_STRING)
              ->setCellValueExplicit("F$count", $v['gender'], PHPExcel_Cell_DataType::TYPE_STRING)
              ->setCellValueExplicit("G$count", $v['mb_hp'], PHPExcel_Cell_DataType::TYPE_STRING)
              ->setCellValueExplicit("H$count", $v['mb_birth'], PHPExcel_Cell_DataType::TYPE_STRING);
              
              $count++;
}

header('Content-Type:application/vnd.ms-excel; charset=utf-8');
header('Content-Disposition:attachment; filename = 학생리스트-'.date('ymd', time()).'.xls');
header('Content-Description:PHP4 Generated Data');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');



exit;
?>
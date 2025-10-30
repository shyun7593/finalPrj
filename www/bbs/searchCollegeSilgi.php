<?php
define('G5_CERT_IN_PROG', true);
include_once('./_common.php');


$subIdx = $_POST['subIdx'];
$id = $_POST['id'];
$gender = $_POST['gender'] == '남' ? 'M' : 'F';


$csRes= sql_query("SELECT * FROM g5_college_silgi WHERE csubIdx = '{$subIdx}' AND memId = '{$id}'");

$data = [];

foreach ($csRes as $k => $v) {
    $data[]=[
        'subject' => $v['subNm'], // 실기 종목
        'recode' => $v['subRecode'], // 실기 기록
        'score' => $v['subScore'] // 실기 점수
    ];
}

$csRecord = sql_query("SELECT * FROM g5_junsi_sub WHERE sIdx = {$subIdx} AND gender = '{$gender}' ORDER BY subName, min_score");

$records = [];
foreach($csRecord as $k => $v){
    $records[]=[
        'subName' => $v['subName'],
        'min' => $v['min_score'],
        'max' => $v['max_score'],
        'score' => $v['score']
    ];
}

$result = [
    'data' => $data,
    'records' => $records
];

echo json_encode($result, JSON_UNESCAPED_UNICODE);

?>
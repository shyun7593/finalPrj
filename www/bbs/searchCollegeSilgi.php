<?php
define('G5_CERT_IN_PROG', true);
include_once('./_common.php');


$subIdx = $_POST['subIdx'];
$id = $_POST['id'];

$csRes= sql_query("SELECT * FROM g5_college_silgi WHERE csubIdx = '{$subIdx}' AND memId = '{$id}'");

$data = [];

foreach ($csRes as $k => $v) {
    $data[]=[
        'subject' => $v['subNm'], // 실기 종목
        'recode' => $v['subRecode'], // 실기 기록
        'score' => $v['subScore'] // 실기 점수
    ];
}

$result = [
    'data' => $data
];

echo json_encode($result, JSON_UNESCAPED_UNICODE);

?>
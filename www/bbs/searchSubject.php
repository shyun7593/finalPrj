<?php
define('G5_CERT_IN_PROG', true);
include_once('./_common.php');


$idx = $_POST['idx']; // 학과 인덱스
$stype = $_POST['stype']; // 검색 수시,정시

$add_sql = " gcs.sIdx = {$idx} ";
$join_sql = " LEFT JOIN g5_susi gs on
                    gs.suIdx = gcs.susiIdx
              LEFT JOIN g5_jungsi gj on
                    gj.juIdx = gcs.jungsiIdx ";
switch($stype){
    case 'jungsi':
        $sql_add .= " AND gcs.jungsiIdx is not null ";
        $join_sql = "LEFT JOIN g5_jungsi gj on
                    gj.juIdx = gcs.jungsiIdx ";
        break;
    case 'susi':
        $sql_add .= " AND gcs.susiIdx is not null ";
        $join_sql = " LEFT JOIN g5_susi gs on
                        gs.suIdx = gcs.susiIdx ";
        break;
    default:
        break;
}

$sql = "SELECT 
            *,
            gcc.codeName as 'gun',
            gcc2.codeName as 'areaName'
        FROM g5_college_subject gcs 
        JOIN g5_college gc on
            gc.cIdx = gcs.collegeIdx
        JOIN g5_cmmn_code gcc on
            gcc.code = gcs.cmmn1
        JOIN g5_cmmn_code gcc2 on
            gcc2.code = gcs.areaCode
        {$join_sql}
        WHERE {$add_sql}";

$mres = sql_query($sql);
$data = [];

foreach ($mres as $k => $v) {
    $data['college']=[
        'collegeNm' => $v['cName'], // 대학명
        'subjectNm' => $v['sName'], // 학과명
        'img' => $v['c_url'], // 대학로고
        'areaNm' => $v['areaName'], // 지역
        'gun' => $v['gun'], // 가,나,다 군
        'collegeType' => $v['collegeType'] // 사립, 국립
    ];

    if($v['suIdx']){
        $data['susi'] = [
            'person' => $v['suPerson'] // 모집인원
        ];
    } else {
        $data['susi'] = [];
    }

    if($v['juIdx']){
        $data['jungsi'] = [
            'person' => $v['juPerson'] // 모집인원
        ];
    } else {
        $data['jungsi'] = [];
    }
}

$result = [
    'data' => $data
];

echo json_encode($result, JSON_UNESCAPED_UNICODE);

?>
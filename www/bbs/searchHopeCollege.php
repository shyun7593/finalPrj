<?php
define('G5_CERT_IN_PROG', true);
include_once('./_common.php');


$mb_Id = $_POST['memberId']; // 학생인덱스

$sql = "SELECT 
    gac.memId,
    gac.regId,
    gac.regDate,
    gc.cName,
    gcs.sName,
    gcc.codeName as 'gun',
    gcc2.codename as 'area',
    gcs.collegeType,
    COUNT(gac.idx) as 'cnt'
FROM g5_add_college gac 
JOIN g5_college_subject gcs on 
    gcs.sIdx = gac.subIdx
JOIN g5_college gc on 
    gcs.collegeIdx = gc.cIdx 
JOIN g5_cmmn_code gcc on
    gcc.code = gcs.cmmn1
JOIN g5_cmmn_code gcc2 on
    gcc2.code = gcs.areaCode
WHERE gac.memId = '{$mb_Id}'
GROUP BY
    gcs.sIdx
ORDER BY 
    CASE
        WHEN gac.memId != gac.regId THEN 1
        WHEN gac.memId = gac.regId THEN 2
    END ASC,
    gcc.codeName,
    gc.cName,
    gcs.sName";

$mres = sql_query($sql);
$data = [];

foreach ($mres as $k => $v) {

    $data[]=[
        'memId' => $v['memId'], // 학생 아이디
        'regId' => $v['regId'], // 등록자 아이디
        'regDate' => $v['regDate'], // 등록일시
        'cName' => $v['cName'], // 대학명
        'sName' => $v['sName'], // 학과명
        'gun' => $v['gun'], // 군
        'area' => $v['area'], // 지역
        'collegeType' => $v['collegeType'], // 국립/사립
        'cnt' => $v['cnt']
    ];
}

$result = [
    'data' => $data
];

echo json_encode($result, JSON_UNESCAPED_UNICODE);

?>
<?php
define('G5_CERT_IN_PROG', true);
include_once('./_common.php');


$mb_no = $_POST['mb_no']; // 학생인덱스
$area = $_POST['area']; // 지역코드
$code = $_POST['code']; // 가나다군 코드
$texts = $_POST['texts']; // 학과, 대학명

foreach ($area as $ar => $a){
    $areas .= $a . ',';
}
$areas = str_replace("\\", '', $areas);
$areas =rtrim($areas, ',');

$add_sql = " 1=1 ";

if($areas){
    $add_sql .= " AND gcs.areaCode in ({$areas}) ";
}

if($code){
    $add_sql .= " AND gcs.cmmn1 = '{$code}' ";
}

if($texts){
    $add_sql .= " AND (gcs.sName like '%{$texts}%' OR gc.cName like '%{$texts}%') ";
}


$mb_id = sql_fetch("SELECT mb_id FROM g5_member WHERE mb_no = '{$mb_no}'");

function getReturnRes($val) {
    $val = trim((string)$val);

    // '%'가 없으면 그냥 반환
    if (strpos($val, '%') === false) {
        return $val;
    }

    // '%' 제거
    $clean = str_replace('%', '', $val);

    // 쉼표나 문자가 포함되어 있으면 원본 그대로 반환
    if (strpos($clean, ',') !== false || preg_match('/[a-zA-Z가-힣]/u', $clean)) {
        return $val;
    }

    // 숫자 판별
    if (is_numeric($clean)) {
        $floatVal = (float)$clean;

        // 소수점 아래 두 자리가 00이면 *100 후 정수 반환
        if (fmod($floatVal, 1) == 0.00) {
            return (int)($floatVal).'%';
        }

        // 숫자는 그대로 반환 (단, '%'는 제거된 상태)
        return $clean.'%';
    }

    // 그 외는 원본 그대로 반환
    return $val;
}

$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
if ($page < 1) $page = 1;
$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 20;

$cnt_row = sql_fetch("SELECT 
    COUNT(*) as 'cnt'
FROM g5_college gc JOIN g5_college_subject gcs on gcs.collegeIdx = gc.cIdx JOIN g5_jungsi gj on gj.juIdx = gcs.jungsiIdx  WHERE {$add_sql}");
$total_count = $cnt_row['cnt'];
$total_page = max(1, ceil($total_count / $rows));
$offset = ($page - 1) * $rows;

$sql = "SELECT 
	subName,
	subIdx,
	gun,
	areaName,
	person,
	pSub,
	collegeName,
    collegeType,
	addS,
	addT,
    silgi
FROM
(
-- SELECT 
--     gcs.sName as 'subName',
--     gcs.sIdx as 'subIdx',
--     gcc.codeName as 'gun',
--     gcc2.codeName as 'areaName',
--     gs.suPerson as 'person',
--     '' as 'pSub',
--     gc.cName as 'collegeName',
--     gcs.collegeType as 'collegeType',
--     (SELECT
--         COUNT(*) FROM g5_add_college gac WHERE gac.subIdx = gcs.sIdx AND gac.memId = '{$mb_id['mb_id']}' AND gac.regId = '{$mb_id['mb_id']}') as 'addS',
--     (SELECT
--         COUNT(*) FROM g5_add_college gac2 WHERE gac2.subIdx = gcs.sIdx AND gac2.memId = '{$mb_id['mb_id']}' AND gac2.regId != '{$mb_id['mb_id']}') as 'addT'
-- FROM g5_college_subject gcs 
-- JOIN g5_college gc on
--     gc.cIdx = gcs.collegeIdx
-- JOIN g5_cmmn_code gcc on
--     gcc.code = gcs.cmmn1
-- JOIN g5_cmmn_code gcc2 on
--     gcc2.code = gcs.areaCode
-- LEFT JOIN g5_susi gs on
--     gs.suIdx = gcs.susiIdx
-- UNION ALL
SELECT 
    gcs.sName as 'subName',
    gcs.sIdx as 'subIdx',
    gcc.codeName as 'gun',
    gcc2.codeName as 'areaName',
    gj.juPerson as 'person',
    gj.juPsub as 'pSub',
    gc.cName as 'collegeName',
    gcs.collegeType as 'collegeType',
    (SELECT
        COUNT(*) FROM g5_add_college gac WHERE gac.subIdx = gcs.sIdx AND gac.memId = '{$mb_id['mb_id']}' AND gac.regId = '{$mb_id['mb_id']}') as 'addS',
    (SELECT
        COUNT(*) FROM g5_add_college gac2 WHERE gac2.subIdx = gcs.sIdx AND gac2.memId = '{$mb_id['mb_id']}' AND gac2.regId != '{$mb_id['mb_id']}') as 'addT',
    (SELECT
        COUNT(*) FROM g5_college_silgi ggs WHERE ggs.csubIdx = gcs.sIdx AND ggs.memId = '{$mb_id['mb_id']}' AND subRecode + 0 > 0) as 'silgi'
FROM g5_college_subject gcs 
JOIN g5_college gc on
    gc.cIdx = gcs.collegeIdx
JOIN g5_cmmn_code gcc on
    gcc.code = gcs.cmmn1
JOIN g5_cmmn_code gcc2 on
    gcc2.code = gcs.areaCode
JOIN g5_jungsi gj on
    gj.juIdx = gcs.jungsiIdx
WHERE {$add_sql}
) as A
ORDER BY A.addT DESC, A.addS DESC, A.collegeName, A.subName";

$mres = sql_query($sql);
$data = [];

foreach ($mres as $k => $v) {
    $shis = [];
    $slang = [];
    $seng = [];

    $jhis = [];
    $jlang = [];
    $jeng = [];

    $data[]=[
        'subIdx' => $v['subIdx'], // 학과인덱스
        'teacher' => $v['addT'], // 선생님 추천
        'student' => $v['addS'], // 내 추천
        'areaNm' => $v['areaName'], // 지역
        'collegeType' => $v['collegeType'], // 사립, 국립
        'gun' => $v['gun'], // 가,나,다 군
        'collegeNm' => $v['collegeName'], // 대학명
        'subjectNm' => $v['subName'], // 학과명
        'person' => $v['person'], // 모집인원
        'aCut' => $v['collegeType'], // 수능커트
        'bCut' => $v['collegeType'], // 내신커트
        'cCut' => $v['collegeType'], // 실기커트
        'dCut' => $v['collegeType'], // 기타커트
        'pSub' => $v['pSub'], // 실기과목
        'silgi' => $v['silgi'] // 실기 작성여부
    ];
}

$paging = [
    'total_count' => $total_count,
    'total_page' => $total_page,
    'page' => $page
];

$result = [
    'data' => $data,
    'paging' => $paging
];

echo json_encode($result, JSON_UNESCAPED_UNICODE);

?>
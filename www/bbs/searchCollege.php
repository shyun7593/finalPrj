<?php

use Google\Service\CloudControlsPartnerService\Console;

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

if($textCol){
    $add_sql .= " AND gc.cName like '%{$textCol}%' ";
}

if($textSub){
    $add_sql .= " AND gcs.sName like '%{$textSub}%' ";
}


$mb_id = sql_fetch("SELECT mb_id,mb_sex FROM g5_member WHERE mb_no = '{$mb_no}'");

if($mb_id['mb_sex'] == 'M'){
    $add_sql .= " AND gc.cName not like '%여자%' ";
}

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
FROM g5_college gc JOIN g5_college_subject gcs on gcs.collegeIdx = gc.cIdx JOIN g5_jungsi gj on gj.juSubIdx = gcs.sIdx  WHERE {$add_sql}");

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
    silgi,
    silSum,
    hisList,
    langList,
    engList,
    juTotal,
    juSrate,
    juKorrate,
    juKorSelect,
    juMathrate,
    juMathSelect,
    juEngrate,
    juEngSelect,
    juTamrate,
    juTamSelect,
    juTamCnt,
    juHisAdd,
    juHisSelect,
    juChar,
    juTamChar,
    juLanSelect,
    juPrate,
    juTamSub
FROM
(
SELECT 
    gcs.sName as 'subName',
    gcs.sIdx as 'subIdx',
    gcc.codeName as 'gun',
    gcc2.codeName as 'areaName',
    gj.juPerson as 'person',
    gj.juPsub as 'pSub',
    gc.cName as 'collegeName',
    gcs.collegeType as 'collegeType',
    IFNULL(addc.addS, 0) AS addS,
    IFNULL(addc.addT, 0) AS addT,
    IFNULL(silgiTbl.silgi, 0) AS silgi,
    IFNULL(silgiTbl.silgiSum, 0) as silSum,
    hist.hisList,
    lang.langList,
    eng.engList,
    gj.juTotal, -- 총점
    gj.juSrate, -- 수능 반영비율
    gj.juKorrate, -- 국어 반영비율
    gj.juKorSelect, -- 국어 필선
    gj.juMathrate, -- 수학 반영비율
    gj.juMathSelect, -- 수학 필선
    gj.juEngrate, -- 영어 반영비율
    gj.juEngSelect, -- 영어 필선
    gj.juTamrate, -- 탐구 반영비율
    gj.juTamSelect, -- 탐구 필선
    gj.juTamCnt, -- 탐구 과목수
    gj.juHisAdd, -- 한국사 가산/감점
    gj.juHisSelect, -- 한국사 필선
    gj.juChar, -- 국/수 표백등최
    gj.juTamChar, -- 탐구 표백등최
    gj.juLanSelect, -- 제2외국어 필선
    gj.juPrate, -- // 실기 반영비율
    gj.juTamSub -- // 과탐 제외과목
FROM g5_college_subject gcs 
JOIN g5_college gc ON
    gc.cIdx = gcs.collegeIdx
JOIN g5_cmmn_code gcc ON
    gcc.code = gcs.cmmn1
JOIN g5_cmmn_code gcc2 ON
    gcc2.code = gcs.areaCode
JOIN g5_jungsi gj ON
    gj.juSubIdx = gcs.sIdx
LEFT JOIN (
    SELECT
    	hSubIdx,
        GROUP_CONCAT(hScore ORDER BY hGrade SEPARATOR ',') AS hisList
    FROM g5_history_score
    GROUP BY hSubIdx
) hist ON hist.hSubIdx = gcs.sIdx
LEFT JOIN (
    SELECT
    	lSubIdx,
        GROUP_CONCAT(LScore ORDER BY lGrade SEPARATOR ',') AS langList
    FROM g5_lang_score
    GROUP BY lSubIdx
) lang ON lang.lSubIdx = gcs.sIdx
LEFT JOIN (
    SELECT
    	eSubIdx,
        GROUP_CONCAT(eScore ORDER BY eGrade SEPARATOR ',') AS engList
    FROM g5_english_score
    GROUP BY eSubIdx
) eng ON eng.eSubIdx = gcs.sIdx
LEFT JOIN (
  SELECT 
    subIdx,
    COUNT(CASE WHEN memId = '{$mb_id['mb_id']}' AND regId = '{$mb_id['mb_id']}' THEN 1 END) AS addS,
    COUNT(CASE WHEN memId = '{$mb_id['mb_id']}' AND regId != '{$mb_id['mb_id']}' THEN 1 END) AS addT
  FROM g5_add_college
  GROUP BY subIdx
) addc ON addc.subIdx = gcs.sIdx
LEFT JOIN (
  SELECT 
    csubIdx,
    COUNT(*) AS silgi,
    IFNULL(SUM(subScore), 0) as 'silgiSum'
  FROM g5_college_silgi
  WHERE memId = '{$mb_id['mb_id']}' AND subRecode + 0 > 0
  GROUP BY csubIdx
) silgiTbl ON silgiTbl.csubIdx = gcs.sIdx
WHERE {$add_sql}
) AS A
ORDER BY A.addT DESC, A.addS DESC, A.gun, A.collegeName, A.subName
";
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
        'silgi' => $v['silgi'], // 실기 작성여부
        'engList' => $v['engList'], // 영어 등급표
        'langList' => $v['langList'], // 제2외국어 등급표
        'histList' => $v['hisList'], // 한국사 등급표
        'juTotal' => $v['juTotal'], //  -- 총점
        'juSrate' => getReturnRes($v['juSrate']), //  -- 수능 반영비율
        'juKorrate' => getReturnRes($v['juKorrate']), //  -- 국어 반영비율
        'juKorSelect' => $v['juKorSelect'], //  -- 국어 필선
        'juMathrate' => getReturnRes($v['juMathrate']), //  -- 수학 반영비율
        'juMathSelect' => $v['juMathSelect'], //  -- 수학 필선
        'juEngrate' => getReturnRes($v['juEngrate']), //  -- 영어 반영비율
        'juEngSelect' => $v['juEngSelect'], //  -- 영어 필선
        'juTamrate' => getReturnRes($v['juTamrate']), //  -- 탐구 반영비율
        'juTamSelect' => $v['juTamSelect'], //  -- 탐구 필선
        'juTamCnt' => $v['juTamCnt'], //  -- 탐구 과목수
        'juHisAdd' => $v['juHisAdd'], //  -- 한국사 가산/감점
        'juHisSelect' => $v['juHisSelect'], // -- 한국사 필선
        'juChar' => $v['juChar'], // -- 국/수 변표최등
        'juTamChar' => $v['juTamChar'], // 탐구 변표최등
        'juLanSelect' => $v['juLanSelect'], // 제2외국어 필선
        'juPrate' => $v['juPrate'], // 실기 반영비율
        'juTamSub' => $v['juTamSub'], // 탐구 제외과목
        'silSum' => $v['silSum'] // 실기점수
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
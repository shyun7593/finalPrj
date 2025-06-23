<?php
define('G5_CERT_IN_PROG', true);
include_once('./_common.php');


$idx = $_POST['idx']; // 학과 인덱스
$stype = $_POST['stype']; // 검색 수시,정시

$add_sql = " gcs.sIdx = {$idx} ";

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



switch($stype){
    case 'jungsi':
        $sql_add .= " AND gcs.jungsiIdx is not null ";
        break;
    case 'susi':
        $sql_add .= " AND gcs.susiIdx is not null ";
        break;
    default:
        break;
}

$sql = "SELECT 
            gcs.*,
            gj.*,
            gs.*,
            gc.*,
            gcc.codeName as 'gun',
            gcc2.codeName as 'areaName',
            gac.idx as 'addC'
        FROM g5_college_subject gcs 
        JOIN g5_college gc on
            gc.cIdx = gcs.collegeIdx
        JOIN g5_cmmn_code gcc on
            gcc.code = gcs.cmmn1
        JOIN g5_cmmn_code gcc2 on
            gcc2.code = gcs.areaCode
        LEFT JOIN g5_susi gs on
            gs.suIdx = gcs.susiIdx
        LEFT JOIN g5_jungsi gj on
            gj.juIdx = gcs.jungsiIdx
        LEFT JOIN g5_add_college gac on
            gac.subIdx = gcs.sIdx AND memId = '{$_SESSION['ss_mb_id']}'
        WHERE {$add_sql}";

$mres = sql_query($sql);
$data = [];

foreach ($mres as $k => $v) {
    $shis = [];
    $slang = [];
    $seng = [];

    $jhis = [];
    $jlang = [];
    $jeng = [];

    $data['college']=[
        'collegeNm' => $v['cName'], // 대학명
        'subjectNm' => $v['sName'], // 학과명
        'img' => $v['c_url'], // 대학로고
        'areaNm' => $v['areaName'], // 지역
        'gun' => $v['gun'], // 가,나,다 군
        'collegeType' => $v['collegeType'], // 사립, 국립
        'addYn' => $v['addC']
    ];

    if($v['suIdx']){
        $sh = sql_query("SELECT * FROM g5_history_score WHERE hSubIdx = {$v['sIdx']}");
        $sl = sql_query("SELECT * FROM g5_lang_score WHERE lSubIdx = {$v['sIdx']}");
        $se = sql_query("SELECT * FROM g5_english_score WHERE eSubIdx = {$v['sIdx']}");
        $data['susi'] = [
            'person' => $v['suPerson'] // 모집인원
        ];
    } else {
        $data['susi'] = [];
    }

    if($v['juIdx']){
        $jh = sql_query("SELECT * FROM g5_history_score WHERE hSubIdx = '{$v['sIdx']}'");
        foreach($jh as $hv => $h){
            $jhis[$h['hGrade']] = [
            'score' => $h['hScore']
            ];
        }

        $jl = sql_query("SELECT * FROM g5_lang_score WHERE lSubIdx = '{$v['sIdx']}'");
        foreach($jl as $lv => $l){
            $jlang[$l['lGrade']] = [
            'score' => $l['lScore']
            ];
        }

        $je = sql_query("SELECT * FROM g5_english_score WHERE eSubIdx = '{$v['sIdx']}'");
        foreach($je as $ev => $e){
            $jeng[$e['eGrade']] = [
            'score' => $e['eScore']
            ];
        }

        $data['jungsi'] = [
            'person' => $v['juPerson'], // 모집인원
            'history' => $jhis, // 한국사 등급
            'lang' => $jlang, // 제2외국어 등급
            'eng' => $jeng, // 영어 등급
            'total' => $v['juTotal'], // 전형 총점
            'Srate' => $v['juSrate'], // 수능 반영비율
            'Nrate' => $v['juNrate'], // 내신 반영비율
            'Prate' => $v['juPrate'], // 실기 반영비율
            'Orate' => $v['juOrate'], // 기타 반영비율
            'Korrate' => getReturnRes($v['juKorrate']), // 국어 반영비율
            'KorSelect' => $v['juKorSelect'], // 국어 선필
            'Mathrate' => getReturnRes($v['juMathrate']), // 수학 반영비율
            'MathSelect' => $v['juMathSelect'], // 수학 선필
            'Engrate' => getReturnRes($v['juEngrate']), // 영어 반영비율
            'EngSelect' => $v['juEngSelect'], // 영어 선필
            'Tamrate' => getReturnRes($v['juTamrate']), // 탐구 반영비율
            'TamSelect' => $v['juTamSelect'], // 탐구 선필
            'TamCnt' => $v['juTamCnt'], // 탐구 선택 수
            'HisAdd' => $v['juHisAdd'], // 한국사 가산
            'HisSelect' => $v['juHisSelect'], // 한국사 선필
            'LanSelect' => $v['juLanSelect'], // 제2외국어 선필
            'Char' => $v['juChar'], // 국,수 활용지표
            'TamChar' => $v['juTamChar'], // 탐구 활용지표
            'TransIdx' => $v['juTransIdx'], // 변표 인덱스
            'KorSub' => $v['juKorSub'], // 국어 제외과목
            'MathSub' => $v['juMathSub'], // 수학 제외과목
            'TamSub' => $v['juTamSub'], // 탐구 제외과목
            'KorAdd' => $v['juKorAdd'], // 국어 가산점
            'MathAdd' => $v['juMathAdd'], // 수학 가산점
            'TamAdd' => $v['juTamAdd'], // 탐구 가산점
            'Psub' => $v['juPsub'] // 실기 종목
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
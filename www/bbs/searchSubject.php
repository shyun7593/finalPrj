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



// switch($stype){
//     case 'jungsi':
//         $sql_add .= " AND gcs.jungsiIdx is not null ";
//         break;
//     case 'susi':
//         $sql_add .= " AND gcs.susiIdx is not null ";
//         break;
//     default:
//         break;
// }

$sql = "SELECT 
            gcs.*,
            gc.*,
            gcc.codeName as 'gun',
            gcc2.codeName as 'areaName',
            gac.idx as 'addC',
            (SELECT COUNT(*) FROM g5_susi gs WHERE gs.suSubIdx = gcs.sIdx ) as 'su',
            (SELECT COUNT(*) FROM g5_jungsi gj WHERE gj.juSubIdx = gcs.sIdx ) as 'ju'
        FROM g5_college_subject gcs 
        JOIN g5_college gc on
            gc.cIdx = gcs.collegeIdx
        LEFT JOIN g5_cmmn_code gcc on
            gcc.code = gcs.cmmn1
        JOIN g5_cmmn_code gcc2 on
            gcc2.code = gcs.areaCode
        LEFT JOIN g5_add_college gac on
            gac.subIdx = gcs.sIdx AND memId = '{$_SESSION['ss_mb_id']}' AND gac.memId = gac.regId 
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

    if($v['su']>0){
        $sures = sql_query("SELECT * FROM g5_susi WHERE suSubIdx = {$v['sIdx']}");
        $sucnt = 0;
        foreach($sures as $sr => $s){
            
            $data['susi'][$sucnt] = [
                'suOrder'=> $s['suOrder'], // 실기/종합/논술 등등
                'suPerson'=> $s['suPerson'], // 모집인원
                'suPro'=> $s['suPro'], // 교직여부
                'suType'=> $s['suType'], // 전형 
                'suDetail'=> $s['suDetail'], // 상세
                'suSchool'=> $s['suSchool'], // 학생부 기준일
                'suAppStart'=> $s['suAppStart'], // 원서 시작일
                'suAppEnd'=> $s['suAppEnd'], // 원서 마감일
                'suSilStart'=> $s['suSilStart'], // 실기 시작일
                'suSilEnd'=> $s['suSilEnd'], // 실기 마감일
                'suPsDate'=> $s['suPsDate'], // 합격자 발표
                'suGraduDate'=> $s['suGraduDate'], // 졸업년도
                'suSchoolType'=> $s['suSchoolType'], // 지원 고등학교
                'suNaeSinType'=> $s['suNaeSinType'], // 내신반영구분 교과/비교과
                'suFirst'=> $s['suFirst'], // 1단계 적용/배수
                'suSecond'=> $s['suSecond'], // 내신/실기/면접/기타
                'suTotalScore'=> $s['suTotalScore'], // 총점
                'suGradeScore'=> $s['suGradeScore'], // 학년별 내신 반영 비율 1/2/3/전학년
                'suNaesinNormal'=> $s['suNaesinNormal'], // 일반
                'suNaesinFutuer'=> $s['suNaesinFutuer'], // 진로
                'suNaesinSubject'=> $s['suNaesinSubject'], // 전과목 국/영/수/사/과
                'suNaesinOther'=> $s['suNaesinOther'], // 기타
                'suNaesinGuide'=> $s['suNaesinGuide'], // 활용지표
                'suNaesinGrade'=> $s['suNaesinGrade'], // 내신성적 등급표
                'suSuneungCut'=> $s['suSuneungCut'], // 수능 최저
                'suSilgiGap'=> $s['suSilgiGap'], // 실기 구간별 점수차
                'suSilgi'=> $s['suSilgi'] // 실기 종목
            ];
            $sucnt++;
        }
    } else {
        $data['susi'] = [];
    }

    if($v['ju'] > 0){
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

        $ju = sql_fetch("SELECT * FROM g5_jungsi WHERE juSubIdx = '{$v['sIdx']}'");
        $data['jungsi'] = [
            'person' => $ju['juPerson'], // 모집인원
            'history' => $jhis, // 한국사 등급
            'lang' => $jlang, // 제2외국어 등급
            'eng' => $jeng, // 영어 등급
            'total' => $ju['juTotal'], // 전형 총점
            'Srate' => $ju['juSrate'], // 수능 반영비율
            'Nrate' => $ju['juNrate'], // 내신 반영비율
            'Prate' => $ju['juPrate'], // 실기 반영비율
            'Orate' => $ju['juOrate'], // 기타 반영비율
            'Korrate' => getReturnRes($ju['juKorrate']), // 국어 반영비율
            'KorSelect' => $ju['juKorSelect'], // 국어 선필
            'Mathrate' => getReturnRes($ju['juMathrate']), // 수학 반영비율
            'MathSelect' => $ju['juMathSelect'], // 수학 선필
            'Engrate' => getReturnRes($ju['juEngrate']), // 영어 반영비율
            'EngSelect' => $ju['juEngSelect'], // 영어 선필
            'Tamrate' => getReturnRes($ju['juTamrate']), // 탐구 반영비율
            'TamSelect' => $ju['juTamSelect'], // 탐구 선필
            'TamCnt' => $ju['juTamCnt'], // 탐구 선택 수
            'HisAdd' => $ju['juHisAdd'], // 한국사 가산
            'HisSelect' => $ju['juHisSelect'], // 한국사 선필
            'LanSelect' => $ju['juLanSelect'], // 제2외국어 선필
            'Char' => $ju['juChar'], // 국,수 활용지표
            'TamChar' => $ju['juTamChar'], // 탐구 활용지표
            'TransIdx' => $ju['juTransIdx'], // 변표 인덱스
            'KorSub' => $ju['juKorSub'], // 국어 제외과목
            'MathSub' => $ju['juMathSub'], // 수학 제외과목
            'TamSub' => $ju['juTamSub'], // 탐구 제외과목
            'KorAdd' => $ju['juKorAdd'], // 국어 가산점
            'MathAdd' => $ju['juMathAdd'], // 수학 가산점
            'TamAdd' => $ju['juTamAdd'], // 탐구 가산점
            'Psub' => $ju['juPsub'], // 실기 종목
            'AppStart' => $ju['juAppStart'], // 원서 시작일
            'AppEnd' => $ju['juAppEnd'], // 원서 종료일
            'PrStart' => $ju['juPrStart'], // 실기 시작일
            'PrEnd' => $ju['juPrEnd'], // 실기 종료일
            'PsDate' => $ju['juPsDate'], // 합격자 발표
            'Pro' => $ju['juPro'] // 교직여부
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
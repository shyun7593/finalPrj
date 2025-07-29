<?php
define('G5_CERT_IN_PROG', true);
include_once('./_common.php');


$mb_no = $_POST['mb_no'];

$memb = sql_fetch("select *
                        from g5_member gm 
                        LEFT JOIN g5_branch gb on
                        gm.mb_signature = gb.idx
                        where gm.mb_no = '{$mb_no}'");
if($memb['mb_sex'] == 'M'){
    $gender = '남';
} else {
    $gender = '여';
}
$data3 = [
    'memberName' => $memb['mb_name'],
    'gender' => $gender ? $gender : '',
    'school' => $memb['mb_1'] ? $memb['mb_1'] : '',
    'layer' => $memb['mb_2'] ? $memb['mb_2'] : '',
    'branch' => $memb['branchName']
];

$msql = "WITH RECURSIVE dateMonth AS (
    SELECT code,codeName
    FROM g5_cmmn_code gcc 
    WHERE upperCode = 'C60000000' AND useYN = 1
)
SELECT 
    gms.scoreMonth,
    d.codeName,
    (SELECT 
        codeName
    FROM g5_cmmn_code gcc 
    WHERE gcc.code = gms.subject 
    ) as 'subject',
    (SELECT 
        codeName
    FROM g5_cmmn_code gcc 
    WHERE gcc.code = gms.upperCode
    ) as 'subjectSub',
    gms.origin,
    gms.pscore ,
    gms.sscore ,
    gms.grade ,
    gms.subject as 'subCode'
FROM
    dateMonth d
LEFT JOIN g5_member_score gms on
    d.code = gms.scoreMonth
WHERE 
    gms.memId = '{$memb['mb_id']}'
    AND gms.upperCode != 'C50000000'
GROUP BY
    d.code,
    gms.upperCode 
ORDER BY
    d.code;";
$mres = sql_query($msql);
$data = [];
foreach ($mres as $k => $v) {
    $scoreMonth = $v['scoreMonth']; // 코드
    $monthNm = $v['codeName']; // 3모, 6모, 9모 등등
    $subject = $v['subject']; // 화법과작문, 확률과통계 등등
    $subjectSub = $v['subjectSub']; // 국,영,수 등등
    $origin = $v['origin']; // 원점수
    $pscore = $v['pscore']; // 표준점수
    $sscore = $v['sscore']; // 백분위
    $grade = $v['grade']; // 등급
    $subCode = $v['subCode']; // 등급

    // 배열 초기화
    if (!isset($data[$scoreMonth])) {
        $data[$scoreMonth] = [
            'scoreMonth' => $scoreMonth, // 월코드
            'monthNm' => $monthNm, // 모의고사 월
            'data' => []
        ];
    }
    if (!isset($data[$scoreMonth]['data'][$subjectSub])) {
        $data[$scoreMonth]['data'][$subjectSub] = [
            'subject' => $subject, // 선택과목이름
            'SubDesc' => $SubDesc, // 중분류 순서
            'origin' => $origin,
            'pscore' => $pscore,
            'sscore' => $sscore,
            'grade' => $grade,
            'subCode' => $subCode
        ];
    }
}

$monthArr = sql_query(" SELECT code,codeName
                    FROM g5_cmmn_code gcc 
                    WHERE upperCode = 'C60000000' AND useYN = 1 ORDER BY code");

$data2 = [];
foreach ($monthArr as $k => $v) {
    $code = $v['code']; // 코드
    $codeName = $v['codeName']; // 3모, 6모, 9모 등등

    // 배열 초기화
    if (!isset($data2[$code])) {
        $data2[$code] = [
            'code' => $code, // 월코드
            'codeName' => $codeName // 모의고사 월
        ];
    }
}

$result = [
    'scoreData' => $data,
    'monthList' => $data2,
    'info' => $data3
];

echo json_encode($result, JSON_UNESCAPED_UNICODE);

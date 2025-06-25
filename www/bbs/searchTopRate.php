<?php
define('G5_CERT_IN_PROG', true);
include_once('./_common.php');

$topSql = "SELECT
	MAX(ggc.gradePscore + 0) as 'topRate',
    ggc.gradeType,
	(
	SELECT
		codeName
	FROM
		g5_cmmn_code gc
	WHERE
		gc.code = ggc.gradeCode) as 'gradeName'
FROM
	g5_gradeCut ggc
WHERE ggc.gradeYear = '" . date('Y') . "'
GROUP BY
	ggc.gradeYear ,
	ggc.gradeType ,
	ggc.gradeCode;";


$topres = sql_query($topSql);

$data = [];
foreach ($topres as $k => $v) {
    $monthNm = $v['gradeType']; // 3모, 6모, 9모 등등
    $subject = $v['gradeName']; // 화법과작문, 확률과통계 등등
    $topRate = $v['topRate']; // 최고표준점수
    

    // 배열 초기화
    if (!isset($data[$monthNm])) {
        $data[$monthNm] = [
            'data' => []
        ];
    }
    if (!isset($data[$monthNm]['data'][$subject])) {
        $data[$monthNm]['data'][$subject] = [
            'topRate' => $topRate
        ];
    }
}
$result = [
    'topRateData' => $data
];

echo json_encode($result, JSON_UNESCAPED_UNICODE);

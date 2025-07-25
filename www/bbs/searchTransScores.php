<?php
define('G5_CERT_IN_PROG', true);
include_once('./_common.php');

$topSql = "SELECT
    pSubIdx,
    pCmmn,
    pSscore,
    pTransScore
  FROM g5_trans_pscore";


$topres = sql_query($topSql);

$data = [];

foreach($topres as $tp => $v){
    $subIdx = $v['pSubIdx'];
    $subCode = $v['pCmmn'];
    $Sscore = $v['pSscore'];
    $transScore = $v['pTransScore'];

    if (!isset($data[$subIdx])) {
        $data[$subIdx] = [
            'data' => []
        ];
    }
    if (!isset($data[$subIdx]['data'][$subCode])) {
        $data[$subIdx]['data'][$subCode] = [
            'data' => []
        ];
    }

    if (!isset($data[$subIdx]['data'][$subCode]['data'][$Sscore])) {
        $data[$subIdx]['data'][$subCode]['data'][$Sscore] = [
            'transScore' => $transScore
        ];
    }
}

$result = [
    'transData' => $data
];

echo json_encode($result, JSON_UNESCAPED_UNICODE);

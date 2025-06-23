<?php
define('G5_CERT_IN_PROG', true);
include_once('./_common.php');
include_once('./memoSearch.php');

$mbIdx = $_POST['memberIdx'];

$recode = sql_query("
   SELECT 
            sp.idx,
            sp.memberIdx ,
            DATE_FORMAT(sp.`date`,'%Y-%m') as 'date' ,
            sp.sRank ,
            sp.grade ,
            sp.core_Rank,
            sp.core_score,
            sp.10m_Rank,
            sp.10m_score,
            sp.medicine_Rank,
            sp.medicine_score,
            sp.left_Rank,
            sp.left_score,
            sp.stand_Rank,
            sp.stand_score,
            sp.20mBu_Rank,
            sp.20mBu_score,
            sp.situp_Rank,
            sp.situp_score,
            sp.surgent_Rank,
            sp.surgent_score,
            sp.total_Rank,
            sp.total_Rev
        FROM g5_student_Practice sp
        WHERE
            sp.memberIdx = '{$mbIdx}' AND DATE_FORMAT(sp.`date`,'%Y') = '".date("Y")."'
        ORDER BY DATE_FORMAT(sp.`date`,'%Y-%m'),grade
");

$recodeData = [];
foreach ($recode as $v) {
    $gmIdx = $v['idx'];
    $recodeData['data'][$gmIdx] = [
        'date' => $v['date'],
        'sRank' => $v['sRank'],
        'core_Rank' => $v['core_Rank'],
        'core_score' => $v['core_score'],
        '10m_Rank' => $v['10m_Rank'],
        '10m_score' => $v['10m_score'],
        'medicine_Rank' => $v['medicine_Rank'],
        'medicine_score' => $v['medicine_score'],
        'left_Rank' => $v['left_Rank'],
        'left_score' => $v['left_score'],
        'stand_Rank' => $v['stand_Rank'],
        'stand_score' => $v['stand_score'],
        '20mBu_Rank' => $v['20mBu_Rank'],
        '20mBu_score' => $v['20mBu_score'],
        'situp_Rank' => $v['situp_Rank'],
        'situp_score' => $v['situp_score'],
        'surgent_Rank' => $v['surgent_Rank'],
        'surgent_score' => $v['surgent_score'],
        'total_Rank' => $v['total_Rank'],
        'total_Rev' => $v['total_Rev']
    ];
}

echo json_encode($recodeData, JSON_UNESCAPED_UNICODE);
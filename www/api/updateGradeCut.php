<?php

// require '../vendor/autoload.php';
ini_set('max_execution_time', 300); // 5분까지 허용 (원하는 시간으로 조정 가능)
ini_set('memory_limit', '512M');    // 메모리 제한도 충분히 늘리기
include('../vendor/autoload.php');
include_once('../common.php');

$regId = "master";

$client = new Google_Client();
$client->setApplicationName('Google Sheets API with PHP');
$client->setScopes([
    Google_Service_Sheets::SPREADSHEETS_READONLY  // 읽기 전용 권한
]);
// $client->setAuthConfig('/api/spreadsheet-457500-2c59f048e424.json');
$client->setAuthConfig('spreadsheet-457500-cb4d1dabeba0.json');
$client->setAccessType('offline');

$service = new Google_Service_Sheets($client);

// // 스프레드시트 ID 및 시트 이름 설정
// $spreadsheetId = '1LdNf4_s5CV8SdvMPMad3g_YvxhPOtZcXkq1ktpx10Ek';  // 예: 1BxiMVs0XRA5nFMdKvBdBZjgmUUqptlbs74OgvE2upms
$spreadsheetId = '1RSVMScdHfMOuwQQYApBVNPi8mFr0sutFmiB9kibKRfo';  // 2025-2026 등급컷
$range3 = '표백등!B3:F'; // 3월
$range6 = '표백등!H3:L'; // 6월
$range9 = '표백등!N3:R'; // 9월
$range0 = '표백등!T3:X'; // 가채점
$range1 = '표백등!Z3:AD'; // 수능

function getCodeMap() {
    static $map = null;
    if ($map === null) {
        $result = sql_query("SELECT code, codeName FROM g5_cmmn_code WHERE code like '%C200%' AND depth = 2 AND upperCode not like '%C2005%'");
        $map = [];
        while ($rows = sql_fetch_array($result)) {
            $map[$rows['codeName']] = $rows['code'];
        }
    }

    return $map;
}

function subjectCode($subject) {
    $map = getCodeMap();
    return isset($map[$subject]) ? $map[$subject] : $subject;
}

function delPrevDate($gType){
    sql_query("DELETE FROM g5_gradeCut WHERE gradeYear = '". date('Y') ."' AND gradeType = '{$gType}'");
}

try {
    // 3월
    $response3 = $service->spreadsheets_values->get($spreadsheetId, $range3);
    $values3 = $response3->getValues();
    // 6월
    $response6 = $service->spreadsheets_values->get($spreadsheetId, $range6);
    $values6 = $response6->getValues();
    // 9월
    $response9 = $service->spreadsheets_values->get($spreadsheetId, $range9);
    $values9 = $response9->getValues();
    // 가채점
    $response0 = $service->spreadsheets_values->get($spreadsheetId, $range0);
    $values0 = $response0->getValues();
    // 수능
    $response1 = $service->spreadsheets_values->get($spreadsheetId, $range1);
    $values1 = $response1->getValues();
} catch (Exception $e) {
    echo '오류 발생';
    exit;
}
if (empty($values3)) {
    
} else {
    delPrevDate('C60000001');
    foreach ($values3 as $row) {
        $subCode = subjectCode($row[0]);
        sql_query("INSERT INTO g5_gradeCut set
            gradeYear = '2025',
            gradeCode = '{$subCode}',
            gradeScore = '{$row[1]}',
            gradePscore = '{$row[2]}',
            gradeSscore = '{$row[3]}',
            gGrade = '{$row[4]}',
            regId = '{$regId}',
            gradeType = 'C60000001'
        ");
        if(strstr($subCode,'C2004')){
            $subCode2 = str_replace('C2004', 'C2005', $subCode);
            sql_query("INSERT INTO g5_gradeCut set
                gradeYear = '2025',
                gradeCode = '{$subCode2}',
                gradeScore = '{$row[1]}',
                gradePscore = '{$row[2]}',
                gradeSscore = '{$row[3]}',
                gGrade = '{$row[4]}',
                regId = '{$regId}',
                gradeType = 'C60000001'
            ");
        }
    }
}

if (empty($values6)) {
    
} else {
    delPrevDate('C60000002');
    foreach ($values6 as $row) {
        $subCode = subjectCode($row[0]);
        
        sql_query("INSERT INTO g5_gradeCut set
            gradeYear = '2025',
            gradeCode = '{$subCode}',
            gradeScore = '{$row[1]}',
            gradePscore = '{$row[2]}',
            gradeSscore = '{$row[3]}',
            gGrade = '{$row[4]}',
            regId = '{$regId}',
            gradeType = 'C60000002'
        ");

        if(strstr($subCode,'C2004')){
            $subCode2 = str_replace('C2004', 'C2005', $subCode);
            sql_query("INSERT INTO g5_gradeCut set
                gradeYear = '2025',
                gradeCode = '{$subCode2}',
                gradeScore = '{$row[1]}',
                gradePscore = '{$row[2]}',
                gradeSscore = '{$row[3]}',
                gGrade = '{$row[4]}',
                regId = '{$regId}',
                gradeType = 'C60000002'
            ");
        }
    }
}



if (empty($values9)) {
    
} else {
    delPrevDate('C60000003');
    foreach ($values9 as $row) {
        $subCode = subjectCode($row[0]);
        sql_query("INSERT INTO g5_gradeCut set
            gradeYear = '2025',
            gradeCode = '{$subCode}',
            gradeScore = '{$row[1]}',
            gradePscore = '{$row[2]}',
            gradeSscore = '{$row[3]}',
            gGrade = '{$row[4]}',
            regId = '{$regId}',
            gradeType = 'C60000003'
        ");
        if(strstr($subCode,'C2004')){
            $subCode2 = str_replace('C2004', 'C2005', $subCode);
            sql_query("INSERT INTO g5_gradeCut set
                gradeYear = '2025',
                gradeCode = '{$subCode2}',
                gradeScore = '{$row[1]}',
                gradePscore = '{$row[2]}',
                gradeSscore = '{$row[3]}',
                gGrade = '{$row[4]}',
                regId = '{$regId}',
                gradeType = 'C60000003'
            ");
        }
    }
}

if (empty($values0)) {
} else {
    delPrevDate('C60000004');
    foreach ($values0 as $row) {
        $subCode = subjectCode($row[0]);
        sql_query("INSERT INTO g5_gradeCut set
            gradeYear = '2025',
            gradeCode = '{$subCode}',
            gradeScore = '{$row[1]}',
            gradePscore = '{$row[2]}',
            gradeSscore = '{$row[3]}',
            gGrade = '{$row[4]}',
            regId = '{$regId}',
            gradeType = 'C60000004'
        ");
        if(strstr($subCode,'C2004')){
            $subCode2 = str_replace('C2004', 'C2005', $subCode);
            sql_query("INSERT INTO g5_gradeCut set
                gradeYear = '2025',
                gradeCode = '{$subCode2}',
                gradeScore = '{$row[1]}',
                gradePscore = '{$row[2]}',
                gradeSscore = '{$row[3]}',
                gGrade = '{$row[4]}',
                regId = '{$regId}',
                gradeType = 'C60000004'
            ");
        }
    }
}

if (empty($values1)) {
} else {
    delPrevDate('C60000005');
    foreach ($values1 as $row) {
        $subCode = subjectCode($row[0]);
        sql_query("INSERT INTO g5_gradeCut set
            gradeYear = '2025',
            gradeCode = '{$subCode}',
            gradeScore = '{$row[1]}',
            gradePscore = '{$row[2]}',
            gradeSscore = '{$row[3]}',
            gGrade = '{$row[4]}',
            regId = '{$regId}',
            gradeType = 'C60000005'
        ");
        if(strstr($subCode,'C2004')){
            $subCode2 = str_replace('C2004', 'C2005', $subCode);
            sql_query("INSERT INTO g5_gradeCut set
                gradeYear = '2025',
                gradeCode = '{$subCode2}',
                gradeScore = '{$row[1]}',
                gradePscore = '{$row[2]}',
                gradeSscore = '{$row[3]}',
                gGrade = '{$row[4]}',
                regId = '{$regId}',
                gradeType = 'C60000005'
            ");
        }
    }
}
echo 'success';
?>
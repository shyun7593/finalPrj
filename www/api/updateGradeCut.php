<?php

// require '../vendor/autoload.php';
ini_set('max_execution_time', 300); // 5분까지 허용 (원하는 시간으로 조정 가능)
ini_set('memory_limit', '512M');    // 메모리 제한도 충분히 늘리기
include('../vendor/autoload.php');
include_once('../common.php');

$regId = $_SESSION['ss_mb_id'];
$gradeYear = $_POST['gradeYear'];
$gradeMonth = $_POST['gradeMonth'];

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

function delPrevDate($gradeYear,$gType){
    sql_query("DELETE FROM g5_gradeCut WHERE gradeYear = '{$gradeYear}' AND gradeType = '{$gType}'");
}

switch($gradeMonth){
    case 'C60000001': // 3모
        $range = '표백등!B3:F'; // 3월        
        break;
    case 'C60000002': // 6모
        $range = '표백등!H3:L'; // 6월
        break;
    case 'C60000003': // 9모
        $range = '표백등!N3:R'; // 9월
        break;
    case 'C60000004': // 가채점
        $range = '표백등!T3:X'; // 가채점
        break;
    case 'C60000005': // 수능
        $range = '표백등!Z3:AD'; // 수능
        break;
    
}

try {
    $response = $service->spreadsheets_values->get($spreadsheetId, $range);
    $values = $response->getValues();
} catch (Exception $e) {
    echo '오류 발생';
    exit;
}


if (empty($values)) {
    
} else {
    delPrevDate($gradeYear,$gradeMonth);
    foreach ($values as $row) {
        $subCode = subjectCode($row[0]);
        sql_query("INSERT INTO g5_gradeCut set
            gradeYear = '{$gradeYear}',
            gradeCode = '{$subCode}',
            gradeScore = '{$row[1]}',
            gradePscore = '{$row[2]}',
            gradeSscore = '{$row[3]}',
            gGrade = '{$row[4]}',
            regId = '{$regId}',
            gradeType = '{$gradeMonth}'
        ");
        if(strstr($subCode,'C2004')){
            $subCode2 = str_replace('C2004', 'C2005', $subCode);
            sql_query("INSERT INTO g5_gradeCut set
                gradeYear = '{$gradeYear}',
                gradeCode = '{$subCode2}',
                gradeScore = '{$row[1]}',
                gradePscore = '{$row[2]}',
                gradeSscore = '{$row[3]}',
                gGrade = '{$row[4]}',
                regId = '{$regId}',
                gradeType = '{$gradeMonth}'
            ");
        }
    }
}


echo 'success';
?>
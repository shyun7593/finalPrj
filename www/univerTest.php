<?php

// require '../vendor/autoload.php';
include('./vendor/autoload.php');
include_once('./_common.php');


$client = new Google_Client();
$client->setApplicationName('Google Sheets API with PHP');
$client->setScopes([
    Google_Service_Sheets::SPREADSHEETS_READONLY  // 읽기 전용 권한
]);
// $client->setAuthConfig('/api/spreadsheet-457500-2c59f048e424.json');
$client->setAuthConfig('./api/spreadsheet-457500-cb4d1dabeba0.json');
$client->setAccessType('offline');

$service = new Google_Service_Sheets($client);

// // 스프레드시트 ID 및 시트 이름 설정
// $spreadsheetId = '1LdNf4_s5CV8SdvMPMad3g_YvxhPOtZcXkq1ktpx10Ek';  // 예: 1BxiMVs0XRA5nFMdKvBdBZjgmUUqptlbs74OgvE2upms
$spreadsheetId = '1Kujgdh17qxYvlEiX2Yyk_eHwIjqUqNTYxINEygscLUo';  // 2025-2026 등급컷

$range = "24-25백데이터!D5:BQ";

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


// 3월
echo '3월<br>';
try {
    $response = $service->spreadsheets_values->get($spreadsheetId, $range);
    $values = $response->getValues();

    echo "<pre>";
    print_r($values);
    echo "</pre>";
} catch (Exception $e) {
    echo '오류 발생: ' . $e->getMessage();
}

// $response = $service->spreadsheets_values->get($spreadsheetId, $range);
// $values = $response->getValues();


echo '<hr>';
if (empty($values)) {
    echo "데이터가 없습니다.\n";
} else {
    // $prevSub = '';
    foreach ($values as $row) {
        
        echo $row;
        echo '<br>';
        // $subCode = subjectCode($row[0]);
        // sql_query("INSERT INTO g5_gradeCut set
        //     gradeYear = '2025',
        //     gradeCode = '{$subCode}',
        //     gradeScore = '{$row[1]}',
        //     gradePscore = '{$row[2]}',
        //     gradeSscore = '{$row[3]}',
        //     gGrade = '{$row[4]}',
        //     regId = '{$regId}',
        //     gradeType = 'C60000001'
        // ");
        // if(strstr($subCode,'C2004')){
        //     $subCode2 = str_replace('C2004', 'C2005', $subCode);
        //     sql_query("INSERT INTO g5_gradeCut set
        //         gradeYear = '2025',
        //         gradeCode = '{$subCode2}',
        //         gradeScore = '{$row[1]}',
        //         gradePscore = '{$row[2]}',
        //         gradeSscore = '{$row[3]}',
        //         gGrade = '{$row[4]}',
        //         regId = '{$regId}',
        //         gradeType = 'C60000001'
        //     ");
        // }
    }
}

?>
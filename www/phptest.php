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
$client->setAuthConfig('./api/spreadsheet-457500-2c59f048e424.json');
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

// function getCodeMap() {
//     static $map = null;
//     if ($map === null) {
//         $result = sql_query("SELECT code, codeName FROM g5_cmmn_code WHERE code like '%C200%' AND depth = 2 AND upperCode not like '%C2005%'");
//         var_dump($result);
//         $map = [];
//         while ($rows = sql_fetch_array($result)) {
//             $map[$rows['codeName']] = $rows['code'];
//         }
//     }

//     return $map;
// }

// function subjectCode($subject) {
//     $map = getCodeMap();
//     return isset($map[$subject]) ? $map[$subject] : $subject;
// }


// 3월
echo '3월<br>';
try {
    $response3 = $service->spreadsheets_values->get($spreadsheetId, $range3);
    $values3 = $response3->getValues();

    echo "<pre>";
    print_r($values3);
    echo "</pre>";
} catch (Exception $e) {
    echo '오류 발생: ' . $e->getMessage();
}

// $response3 = $service->spreadsheets_values->get($spreadsheetId, $range3);
// $values3 = $response3->getValues();



if (empty($values3)) {
    echo "데이터가 없습니다.\n";
} else {
    // $prevSub = '';
    foreach ($values3 as $row) {
        echo '여기';
        echo $row.'<br>';
        // $subCode = subjectCode($row[0]);
        // echo $subCode;
        // sql_query("INSERT INTO g5_gradeCut set
        //     gradeYear = '2025',
        //     gradeCode = '{$subCode}',
        //     gradeScore = '{$row[1]}',
        //     gradePscore = '{$row[2]}',
        //     gradeSscore = '{$row[3]}',
        //     gGrade = '{$row[4]}',
        //     regId = '{$regId}',
        //     gradeType = 'm_3'
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
        //         gradeType = 'm_3'
        //     ");
        // }
    }
}
exit;
// 6월
echo '<br>6월<br>';
$response6 = $service->spreadsheets_values->get($spreadsheetId, $range6);
$values6 = $response6->getValues();

if (empty($values6)) {
    echo "데이터가 없습니다.\n";
} else {
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
            gradeType = 'm_6'
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
                gradeType = 'm_3'
            ");
        }
    }
}

// 9월
echo '<br>9월<br>';
$response9 = $service->spreadsheets_values->get($spreadsheetId, $range9);
$values9 = $response9->getValues();

if (empty($values9)) {
    echo "데이터가 없습니다.\n";
} else {
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
            gradeType = 'm_9'
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
                gradeType = 'm_3'
            ");
        }
    }
}

// 가채점
echo '<br>가채점<br>';
$response0 = $service->spreadsheets_values->get($spreadsheetId, $range0);
$values0 = $response0->getValues();

if (empty($values0)) {
    echo "데이터가 없습니다.\n";
} else {
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
            gradeType = 'm_0'
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
                gradeType = 'm_3'
            ");
        }
    }
}

// 수능
echo '<br>수능<br>';
$response1 = $service->spreadsheets_values->get($spreadsheetId, $range1);
$values1 = $response1->getValues();

if (empty($values1)) {
    echo "데이터가 없습니다.\n";
} else {
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
            gradeType = 'm_1'
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
                gradeType = 'm_3'
            ");
        }
    }
}
echo '성공';
?>
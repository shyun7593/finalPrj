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
$spreadsheetId = '1ulvTMx8zcEnLbVOTubnYa2uhQjmx30y8ObGQ9MhOo6Q';  // 실기

$range = "메인!A4:AR";

function subjectCode($subject) {
    $map = getCodeMap();
    return isset($map[$subject]) ? $map[$subject] : $subject;
}


// 3월

$response = $service->spreadsheets_values->get($spreadsheetId, $range);
$values = $response->getValues();

// $response = $service->spreadsheets_values->get($spreadsheetId, $range);
// $values = $response->getValues();


// [0] : 날짜
// [1] : 순위
// [2] : ID  -- 추후에 추가
// [6] : 학년
// [8] : 100m 기록
// [9] : 100m 점수
// [10] : 핸던 기록
// [11] : 핸던 점수
// [12] : Z런 기록
// [13] : Z런 점수
// [14] : 배근력 기록
// [15] : 배근력 점수
// [16] : 10m 왕복 기록
// [17] : 10m 왕복 점수
// [18] : 메디신 기록
// [19] : 메디신 점수
// [20] : 좌전굴 기록
// [21] : 좌전굴 점수
// [22] : 제멀 기록
// [23] : 제멀 점수
// [24] : 턱걸이 기록
// [25] : 턱걸이 점수
// [26] : 20m콘턴 기록
// [27] : 20m콘턴 점수
// [28] : 20m부저 기록
// [29] : 20m부저 점수
// [30] : 윗몸 기록
// [31] : 윗몸 점수
// [32] : 지즈재그런 기록
// [33] : 지즈재그런 점수
// [34] : 사이드스텝 기록
// [35] : 사이드스텝 점수
// [36] : 서전트 기록
// [37] : 서전트 점수
// [38] : 농구 기록
// [39] : 농구 점수
// [40] : 배구 기록
// [41] : 배구 점수
// [42] : 총점 기록
// [43] : 총점 점수


echo '<hr>';
if (empty($values)) {
    echo "데이터가 없습니다.\n";
} else {
    // $prevSub = '';
    foreach ($values as $row) {
        if($row[3] == '최원호'){
            $row[2] = 'chois';
            echo "INSERT INTO g5_student_Practice SET
                `date` = '{$row[0]}',
                sRank = '{$row[1]}',
                memberIdx = '{$row[2]}',
                grade = '{$row[6]}',
                100m_Rank = '{$row[8]}',
                100m_score = '{$row[9]}',
                hand_Rank = '{$row[10]}',
                hand_score = '{$row[11]}',
                zrun_Rank = '{$row[12]}',
                zrun_score = '{$row[13]}',
                core_Rank = '{$row[14]}',
                core_score = '{$row[15]}',
                10m_Rank = '{$row[16]}',
                10m_score = '{$row[17]}',
                medicine_Rank = '{$row[18]}',
                medicine_score = '{$row[19]}',
                left_Rank = '{$row[20]}',
                left_score = '{$row[21]}',
                stand_Rank = '{$row[22]}',
                stand_score = '{$row[23]}',
                chin_Rank = '{$row[24]}',
                chin_score = '{$row[25]}',
                20mTurn_Rank = '{$row[26]}',
                20mTurn_score = '{$row[27]}',
                20mBu_Rank = '{$row[28]}',
                20mBu_score = '{$row[29]}',
                situp_Rank = '{$row[30]}',
                situp_score = '{$row[31]}',
                zig_Rank = '{$row[32]}',
                zig_score = '{$row[33]}',
                surgent_Rank = '{$row[34]}',
                surgent_score = '{$row[35]}',
                side_Rank = '{$row[36]}',
                side_score = '{$row[37]}',
                basket_Rank = '{$row[38]}',
                basket_score = '{$row[39]}',
                handball_Rank = '{$row[40]}',
                handball_score = '{$row[41]}',
                toatl_Rank = '{$row[42]}',
                toatl_Rev = '{$row[43]}'";
            sql_query("INSERT INTO g5_student_Practice SET
                `date` = '{$row[0]}',
                sRank = '{$row[1]}',
                memberIdx = '{$row[2]}',
                grade = '{$row[6]}',
                100m_Rank = '{$row[8]}',
                100m_score = '{$row[9]}',
                hand_Rank = '{$row[10]}',
                hand_score = '{$row[11]}',
                zrun_Rank = '{$row[12]}',
                zrun_score = '{$row[13]}',
                core_Rank = '{$row[14]}',
                core_score = '{$row[15]}',
                10m_Rank = '{$row[16]}',
                10m_score = '{$row[17]}',
                medicine_Rank = '{$row[18]}',
                medicine_score = '{$row[19]}',
                left_Rank = '{$row[20]}',
                left_score = '{$row[21]}',
                stand_Rank = '{$row[22]}',
                stand_score = '{$row[23]}',
                chin_Rank = '{$row[24]}',
                chin_score = '{$row[25]}',
                20mTurn_Rank = '{$row[26]}',
                20mTurn_score = '{$row[27]}',
                20mBu_Rank = '{$row[28]}',
                20mBu_score = '{$row[29]}',
                situp_Rank = '{$row[30]}',
                situp_score = '{$row[31]}',
                zig_Rank = '{$row[32]}',
                zig_score = '{$row[33]}',
                surgent_Rank = '{$row[34]}',
                surgent_score = '{$row[35]}',
                side_Rank = '{$row[36]}',
                side_score = '{$row[37]}',
                basket_Rank = '{$row[38]}',
                basket_score = '{$row[39]}',
                handball_Rank = '{$row[40]}',
                handball_score = '{$row[41]}',
                total_Rank = '{$row[42]}',
                total_Rev = '{$row[43]}'
            ");
        }
        // echo "<pre>";
        // print_r($row);
        // echo "</pre>";
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
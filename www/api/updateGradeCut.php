<?php
ini_set('max_execution_time', 300); // 5분까지 허용
ini_set('memory_limit', '512M');
include('../vendor/autoload.php');
include_once('../common.php');

$regId = $_SESSION['ss_mb_id'];
$gradeYear = $_POST['gradeYear'];

$client = new Google_Client();
$client->setApplicationName('Google Sheets API with PHP');
$client->setScopes([
    Google_Service_Sheets::SPREADSHEETS_READONLY
]);
$client->setAuthConfig('spreadsheet-457500-cb4d1dabeba0.json');
$client->setAccessType('offline');

$service = new Google_Service_Sheets($client);
$spreadsheetId = '1RSVMScdHfMOuwQQYApBVNPi8mFr0sutFmiB9kibKRfo'; // 2025-2026 등급컷

// 📌 각 시험월별 시트 범위 정의
$ranges = [
    'C60000001' => '표백등!B3:F',  // 3모
    'C60000002' => '표백등!H3:L',  // 6모
    'C60000003' => '표백등!N3:R',  // 9모
    'C60000004' => '표백등!T3:X',  // 가채점
    'C60000005' => '표백등!Z3:AD', // 수능
];

/** 공통코드 매핑 */
function getCodeMap() {
    static $map = null;
    if ($map === null) {
        $result = sql_query("SELECT code, codeName FROM g5_cmmn_code WHERE code LIKE '%C200%' AND depth = 2 AND upperCode NOT LIKE '%C2005%'");
        $map = [];
        while ($rows = sql_fetch_array($result)) {
            $map[$rows['codeName']] = $rows['code'];
        }
    }
    return $map;
}

/** 기존 데이터 삭제 */

sql_query("DELETE FROM g5_gradeCut WHERE gradeYear = '{$gradeYear}'");


/** 숫자 반올림 보정 */
function roundNumber($nm) {
    return (fmod($nm, 1) == 0.0) ? (int)$nm : $nm;
}

// ---------------------------------------------------------
// 🧩 각 시험월별 반복 처리
// ---------------------------------------------------------
$map = getCodeMap();

foreach ($ranges as $monthCode => $range) {
    try {
        $response = $service->spreadsheets_values->get($spreadsheetId, $range);
        $values = $response->getValues();

        if (empty($values)) {
            echo "{$monthCode} : no data<br>";
            continue;
        }
        $rowsToInsert = [];

        foreach ($values as $row) {
            // 빈 행 처리 방지
            if (empty($row[0]) || empty($row[1])) continue;

            $subCode = isset($map[$row[0]]) ? $map[$row[0]] : $row[0];
            $sscore = roundNumber($row[3]);
            $gradeScore = $row[1] ?? 0;
            $gradePscore = $row[2] ?? 0;
            $gGrade = $row[4] ?? '';

            // 기본 과목행
            $rowsToInsert[] = "(
                '{$gradeYear}',
                '{$subCode}',
                '{$gradeScore}',
                '{$gradePscore}',
                '{$sscore}',
                '{$gGrade}',
                '{$regId}',
                '{$monthCode}'
            )";

            // C2004 → C2005 과목 복제
            if (strstr($subCode, 'C2004')) {
                $subCode2 = str_replace('C2004', 'C2005', $subCode);
                $rowsToInsert[] = "(
                    '{$gradeYear}',
                    '{$subCode2}',
                    '{$gradeScore}',
                    '{$gradePscore}',
                    '{$sscore}',
                    '{$gGrade}',
                    '{$regId}',
                    '{$monthCode}'
                )";
            }
        }

        // 500개씩 나눠서 대량 삽입
        foreach (array_chunk($rowsToInsert, 500) as $chunk) {
            $query = "
                INSERT INTO g5_gradeCut 
                (gradeYear, gradeCode, gradeScore, gradePscore, gradeSscore, gGrade, regId, gradeType)
                VALUES " . implode(',', $chunk);
            sql_query($query);
        }
    } catch (Exception $e) {
        echo "{$monthCode} : error → " . $e->getMessage() . "<br>";
    }
}

echo "success";
?>

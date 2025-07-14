<?

if (!defined('_GNUBOARD_')) exit;

function getMemberFullData($mb_no) {
    global $g5; // DB 테이블 접두사 사용

    // mb_id 가져오기
    $mbIdRow = sql_fetch("SELECT mb_id FROM {$g5['member_table']} WHERE mb_no = '{$mb_no}'");
    if (!$mbIdRow) return false; // 없는 회원이면 false 반환
    $mbId = $mbIdRow['mb_id'];

    // 메모 데이터 가져오기
    $memNote = sql_query("
        SELECT
	n.regDate,
	n.updDate,
	n.memo,
	c.code as 'tag',
	n.title,
	g1.mb_name AS regName,
	g2.mb_name AS updName,
	n.idx
FROM
	g5_cmmn_code c
LEFT JOIN 
    g5_member_note n ON
	c.code = n.tag
	AND n.mbIdx = '{$mb_no}'
LEFT JOIN g5_member g1 ON
	g1.mb_id = n.regId
LEFT JOIN g5_member g2 ON
	g2.mb_id = n.updId
WHERE
	c.upperCode LIKE 'C6000%'
	OR c.code LIKE 'C0000%'
    ");

    $memoData = [];
    foreach ($memNote as $v) {
        $tag = $v['tag'];
        $gmIdx = $v['idx'];

        if (!isset($memoData[$tag])) {
            $memoData[$tag] = [
                'tag' => $tag,
                'gmIdx' => $gmIdx,
                'data' => []
            ];
        }
        $memoData[$tag]['data'] = [
            'gmIdx' => $gmIdx,
            'memo' => $v['memo'],
            'title' => $v['title'],
            'regName' => $v['regName'],
            'regDate' => $v['regDate'],
            'updName' => $v['updName'],
            'updDate' => $v['updDate']
        ];
    }

    // 점수 데이터 가져오기
    $mres = sql_query("
        WITH RECURSIVE dateMonth AS (
            SELECT code, codeName
            FROM g5_cmmn_code
            WHERE upperCode = 'C60000000'
        )
        SELECT 
            gms.scoreMonth,
            d.codeName,
            (SELECT codeName FROM g5_cmmn_code WHERE code = gms.subject) AS subject,
            (SELECT codeName FROM g5_cmmn_code WHERE code = gms.upperCode) AS subjectSub,
            gms.origin,
            gms.pscore,
            gms.sscore,
            gms.grade
        FROM dateMonth d
        LEFT JOIN g5_member_score gms ON d.code = gms.scoreMonth
        WHERE gms.memId = '{$mbId}'
          AND gms.upperCode != 'C50000000'
        GROUP BY d.code, gms.upperCode
        ORDER BY d.code
    ");

    $scoreData = [];
    foreach ($mres as $v) {
        $scoreMonth = $v['scoreMonth'];
        $subjectSub = $v['subjectSub'];

        if (!isset($scoreData[$scoreMonth])) {
            $scoreData[$scoreMonth] = [
                'scoreMonth' => $scoreMonth,
                'monthNm' => $v['codeName'],
                'data' => []
            ];
        }
        $scoreData[$scoreMonth]['data'][$subjectSub] = [
            'subject' => $v['subject'],
            'SubDesc' => $subjectSub,
            'origin' => $v['origin'],
            'pscore' => $v['pscore'],
            'sscore' => $v['sscore'],
            'grade' => $v['grade']
        ];
    }

    return [
        'scoreData' => $scoreData,
        'memoData' => $memoData
    ];
}
?>
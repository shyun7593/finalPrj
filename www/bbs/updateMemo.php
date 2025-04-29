<?php
define('G5_CERT_IN_PROG', true);
include_once('./_common.php');
include_once('./memoSearch.php');


$type = $_POST['type'];
$memoIdx = $_POST['memoIdx'];
$memoMonth = $_POST['memoMonth'];
$memoTitle = $_POST['memoTitle'];
$memoCont = $_POST['memoCont'];
$mbIdx = $_POST['mbIdx'];

if($type == 'save'){
    sql_query("INSERT INTO g5_member_note SET
                memo = '{$memoCont}',
                title = '{$memoTitle}',
                mbIdx = '{$mbIdx}',
                tag = '{$memoMonth}',
                regId = '{$_SESSION['ss_mb_id']}'
        ");

} else if($type == 'update'){
    sql_query("UPDATE g5_member_note SET
                memo = '{$memoCont}',
                title = '{$memoTitle}',
                updId = '{$_SESSION['ss_mb_id']}',
                updDate = '".G5_TIME_YMDHIS."'
            WHERE idx = '{$memoIdx}'
    ");
}

$data = getMemberFullData($mbIdx);

echo json_encode($data, JSON_UNESCAPED_UNICODE);
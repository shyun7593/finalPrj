<?php
define('G5_CERT_IN_PROG', true);
include_once('./_common.php');

$noticeIdx = $_POST['noticeIdx'];
$noticeType = $_POST['noticeType'];
$fixed = $_POST['fixed'];
$orderNum = $_POST['orderNum'];
$noticeTitle = $_POST['noticeTitle'];
$contents = $_POST['contents'];

if($noticeType == 'insert'){
    sql_query("INSERT INTO g5_notice SET
                contents = '{$contents}',
                title = '{$noticeTitle}',
                isFixed = {$fixed},
                ordered = {$orderNum},
                regId = '{$_SESSION['ss_mb_id']}'
        ");
    
} else if($noticeType == 'update'){
    sql_query("UPDATE g5_notice SET
                contents = '{$contents}',
                title = '{$noticeTitle}',
                isFixed = {$fixed},
                ordered = {$orderNum},
                updId = '{$_SESSION['ss_mb_id']}',
                updDate = '".G5_TIME_YMDHIS."'
            WHERE idx = '{$noticeIdx}'
    ");
    sql_query("DELETE FROM g5_notice_read WHERE noticeIdx = '{$noticeIdx}'");
}

echo 'success';
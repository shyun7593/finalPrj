<?php
define('G5_CERT_IN_PROG', true);
include_once('./_common.php');

$memoIdx = $_POST['memoIdx'];
$memo = $_POST['memo'];
$memberIdx = $_POST['memberIdx'];
$type = $_POST['type'];

if($type == 'update'){
    $result = sql_query("UPDATE g5_memo SET 
                            memo = '{$memo}',
                            updId = '{$_SESSION['ss_mb_id']}',
                            updDate = '".G5_TIME_YMDHIS."'
                        WHERE idx = '{$memoIdx}'
                ");

} else if($type == 'add'){
    $result = sql_query("INSERT INTO g5_memo SET
                            memberIdx = '{$memberIdx}',
                            memo = '{$memo}',
                            regId = '{$_SESSION['ss_mb_id']}',
                            regDate = '".G5_TIME_YMDHIS."'
    ");
}

if ($result) {
    $msg = "success";
} else {
    $msg = "failed";
}

echo $msg;

?>
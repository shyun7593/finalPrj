<?php
define('G5_CERT_IN_PROG', true);
include_once('./_common.php');

$subject = $_REQUEST['subject'];
$origin = $_REQUEST['origin'];
$sscore = $_REQUEST['sscore'];
$pscore = $_REQUEST['pscore'];
$grade = $_REQUEST['grade'];
$month = $_REQUEST['month'];
$upperCode = $_REQUEST['upperCode'];
$id = $_REQUEST['id'];

$length = count($subject);

for($i = 0; $i < $length; $i++){
    $cnt = sql_fetch("SELECT COUNT(*) as 'cnt' FROM g5_member_score WHERE memId = '{$id}' AND `subject` = '{$subject[$i]}' AND scoreMonth = '{$month}'");
    if($cnt['cnt'] > 0){
        sql_query("UPDATE g5_member_score SET
                        origin = '{$origin[$i]}',
                        sscore = '{$sscore[$i]}',
                        pscore = '{$pscore[$i]}',
                        grade = '{$grade[$i]}',
                        upperCode = '{$upperCode[$i]}',
                        updDate = '".G5_TIME_YMDHIS."',
                        updID = '{$_SESSION['ss_mb_id']}'
                    WHERE memId = '{$id}' AND scoreMonth = '{$month}' AND `subject` = '{$subject[$i]}'
        ");
    } else {
        sql_query("INSERT INTO g5_member_score SET
                `subject` = '{$subject[$i]}',
                memId = '{$id}' ,
                origin = '{$origin[$i]}',
                sscore = '{$sscore[$i]}',
                pscore = '{$pscore[$i]}',
                grade = '{$grade[$i]}',
                scoreMonth = '{$month}',
                upperCode = '{$upperCode[$i]}',
                insID = '{$_SESSION['ss_mb_id']}'
        ");
    }
}

echo 'success';
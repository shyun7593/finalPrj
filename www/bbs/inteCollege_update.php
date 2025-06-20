<?php
define('G5_CERT_IN_PROG', true);
include_once('./_common.php');


$idx = $_POST['idx'];
$id = $_POST['id'];
$type = $_POST['type'];

if($type == 'add'){
    $result = sql_query("INSERT INTO g5_add_college SET subIdx = '{$idx}', memId = '{$id}'");

} else if($type == 'remove'){
    $result = sql_query("DELETE FROM g5_add_college WHERE memId = '{$id}' AND subIdx = '{$idx}'");
}

if($result){
    $msg = "success";
} else {
    $msg = "failed";
}

echo $msg;

<?php
define('G5_CERT_IN_PROG', true);
include_once('./_common.php');


$branchName = $_POST['branchName'];
$branchManager = $_POST['branchManager'];
$branchHp = $_POST['branchHp'];
$branchMemo = $_POST['branchMemo'];
$branchActive = $_POST['branchActive'];
$type = $_POST['type'];

if($type == 'insert'){
    sql_query("INSERT INTO g5_branch SET
        branchName = '{$branchName}',
        branchManager = '{$branchManager}',
        branchHp = replace('{$branchHp}','-',''),
        branchMemo = '{$branchMemo}',
        branchActive = {$branchActive}
    ");
    
} else if($type == 'update'){
    $idx = $_POST['branchPopIdx'];
    sql_query("UPDATE g5_branch SET
        branchName = '{$branchName}',
        branchManager = '{$branchManager}',
        branchHp = replace('{$branchHp}','-',''),
        branchMemo = '{$branchMemo}',
        branchActive = {$branchActive}
        WHERE idx = '{$idx}'
    ");
}

echo 'success';
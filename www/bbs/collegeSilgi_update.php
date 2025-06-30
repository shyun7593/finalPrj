<?php
define('G5_CERT_IN_PROG', true);
include_once('./_common.php');



$datas = $_POST['datas'];
$type = $_POST['type'];
$subIdx = $_POST['subIdx'];
$id = $_POST['id'];

$length = count($datas);

if($type == 'add'){
    foreach($datas as $row){
       $result = sql_query("INSERT INTO g5_college_silgi SET 
                    csubIdx = '{$subIdx}', 
                    subNm = '{$row['subject']}', 
                    subRecode = '{$row['recode']}', 
                    subScore = '{$row['score']}', 
                    memId = '{$id}',
                    regId = '{$_SESSION['ss_mb_id']}'");
        if(!$result){
            break;
        }
    }
    

} else if($type == 'update'){
    foreach($datas as $row){
        $result = sql_query("UPDATE g5_college_silgi SET 
                    subRecode = '{$row['recode']}', 
                    subScore = '{$row['score']}',
                    updId = '{$_SESSION['ss_mb_id']}'
                WHERE csubIdx = '{$subIdx}' AND memId = '{$id}' AND subNm = '{$row['subject']}'
                ");
        if(!$result){
            break;
        }
    }
}

if($result){
    $msg = "success";
} else {
    $msg = "failed";
}

echo $msg;

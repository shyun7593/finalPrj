<?php
include_once('../common.php');

@mkdir(G5_DATA_PATH."/final_attach"."/".G5_TIME_YM , G5_DIR_PERMISSION);
@chmod(G5_DATA_PATH."/final_attach"."/".G5_TIME_YM , G5_DIR_PERMISSION);

$it_img_dir = G5_DATA_PATH.'/final_attach'."/".G5_TIME_YM;

$extension = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
$filename = explode(".",$_FILES['file']['name']);
$safe_file_name = preg_replace("/[^a-zA-Z0-9_\-가-힣]/u", "", $filename[0]).".".$extension;


$files = raon_attach_upload($_FILES['file']['tmp_name'], $_FILES['file']['name'], $it_img_dir,'oh24_attach');

$response = array();


$fsql = "INSERT INTO g5_final_file set
        pidx = '{$p_idx}',
        `fileName` = '{$files}', 
        realname = '".$safe_file_name."', 
        regDate = '" . G5_TIME_YMDHIS . "',
        fileTable = '{$attach_type}',
        regId = '관리자'
        ";

sql_query($fsql);    

//error_log($fsql);

//$response =  sql_insert_id();

$response['success'] = true;
$response['idx'] = sql_insert_id();


header('Content-Type: application/json');
echo json_encode($response);

//echo $response;

?>

<?php
define('G5_CERT_IN_PROG', true);
include_once('./_common.php');


$idx = $_POST['idx'];

$sql = sql_query("SELECT * FROM g5_branch WHERE idx = '{$idx}'");


$result = sql_fetch_array($sql);

foreach ($sql  as $k => $v) {
	$data_list[] = $v;
}


$v_result  .= "{\"list\":[";

if (sizeof($data_list)  >  0) {
	for ($i = 0; $i < sizeof($data_list); $i++) {
		if ($i != 0) {
			$v_result  .=  ",";
		}
		
        $v_result  .=  "{\"branchName\":\"" . $data_list[$i]['branchName'] 
        . "\",\"branchManager\":\"" . $data_list[$i]['branchManager']
        . "\",\"branchHp\":\"" . $data_list[$i]['branchHp']
        . "\",\"branchActive\":\"" . $data_list[$i]['branchActive']
        . "\",\"branchMemo\":\"" . $data_list[$i]['branchMemo'] . "\"}";
	}
}

$v_result  .= "]}";

echo $v_result;

?>
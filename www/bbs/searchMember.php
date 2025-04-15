<?php
define('G5_CERT_IN_PROG', true);
include_once('./_common.php');


$mb_no = $_POST['mbno'];

$sql = sql_query("SELECT * FROM g5_member WHERE mb_no = '{$mb_no}'");


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
		
        $v_result  .=  "{\"mb_id\":\"" . $data_list[$i]['mb_id'] 
        . "\",\"mb_name\":\"" . $data_list[$i]['mb_name']
        . "\",\"mb_sex\":\"" . $data_list[$i]['mb_sex']
        . "\",\"mb_birth\":\"" . $data_list[$i]['mb_birth']
        . "\",\"mb_profile\":\"" . $data_list[$i]['mb_profile']
        . "\",\"mb_1\":\"" . $data_list[$i]['mb_1']
        . "\",\"mb_2\":\"" . $data_list[$i]['mb_2']
        . "\",\"mb_hp\":\"" . $data_list[$i]['mb_hp']
        . "\",\"mb_level\":\"" . $data_list[$i]['mb_level']
        . "\",\"mb_signature\":\"" . $data_list[$i]['mb_signature'] . "\"}";
	}
}

$v_result  .= "]}";

echo $v_result;

?>
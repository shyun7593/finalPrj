<?php
define('G5_CERT_IN_PROG', true);
include_once('./_common.php');
include_once('./memoSearch.php');

$mb_no = $_POST['mbIdx'];

$data = getMemberFullData($mb_no);


echo json_encode($data, JSON_UNESCAPED_UNICODE);
?>
<?php
define('G5_CERT_IN_PROG', true);
include_once('./_common.php');


$memberIdx = $_POST['memberIdx'];

$sql = sql_query("");

$memNote = sql_query("SELECT 
                        gm.*, 
                        mem.mb_name as 'regName', 
                        mem2.mb_name as 'updName' 
                    FROM g5_memo gm 
                    LEFT JOIN g5_member mem on 
                        gm.regId = mem.mb_id 
                    LEFT JOIN g5_member mem2 on 
                        mem2.mb_id = updId 
                    WHERE memberIdx = '{$memberIdx}'
");

$memoData = [];
foreach ($memNote as $v) {
$memoData['data'] = [
    'idx' => $v['idx'],
    'memo' => $v['memo'],
    'memberIdx' => $v['memberIdx'],
    'regName' => $v['regName'],
    'regDate' => $v['regDate'],
    'updName' => $v['updName'],
    'updDate' => $v['updDate']
];
}


echo json_encode($memoData, JSON_UNESCAPED_UNICODE);

?>
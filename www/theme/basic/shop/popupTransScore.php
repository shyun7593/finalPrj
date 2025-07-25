<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가
if($_SESSION['mb_profile'] == 'C40000004' || !$subIdx){
    exit;
}

$g5['title'] = '변환표준 점수';
include_once('./_head.php');

$sql = sql_query("SELECT * FROM g5_trans_pscore WHERE pSubIdx = '{$subIdx}' ORDER BY pCmmn, pSscore+0 desc");
$data = [];
foreach($sql as $tp => $v){
    $psubIdx = $v['pSubIdx'];
    $subCode = $v['pCmmn'];
    $Sscore = (int)$v['pSscore'];
    $transScore = $v['pTransScore'];
    $data[$psubIdx][$subCode][$Sscore] = [
        'transScore' => $transScore
    ];
}

// echo "<pre>";
// print_r($data);
// echo "</pre>";
?>
<style>
    #wrapper {
        z-index: 5;
        position: relative;
        width: 100% !important;
        font-size: 1.2em;
        min-width: 0px !important;
        padding: 20px 50px 0 50px;
        margin-left: 0px !important;
        transition: margin-left 0.5s ease, width 0.5s ease;
    }
    #hd_menu{
        display: none !important;
    }
    #wrapper_title{
        padding: 0 0 10px 0!important;
    }
    
    table{
        border-collapse: collapse;
        text-align: center;
        width: 100%;
        table-layout: fixed;
    }

    table th, table td{
        padding:4px 0;
    }
    table thead th{
        background-color: #334d63;
        color:white;
    }
    table thead th:not(:last-child){
        border-right:1px solid white;
    }
    table tbody td:not(:last-child){
        border-right: 1px solid #eee;
    }
    table tbody tr:not(:last-child){
        border-bottom: 1px solid #eee;
    }
    
</style>
<!-- 등급관리 시작 { -->
<div id="smb_my">
    <div id="smb_my_list">
        <!-- 최근 주문내역 시작 { -->
        <section id="smb_my_od" style="margin:0;">
        <div style="height:75vh;overflow-y:auto">
            <table style="text-align:center;">
                <colgroup>
                    <col width="80px">
                    <col width="*">
                </colgroup>
                <thead style="position: sticky;top:0px;z-index:5;">
                    <tr>
                    <th>백분위</th> 
                    <th>과탐</th> 
                    <th>사탐</th> 
                    <th>직탐</th> 
                    </tr>
                </thead>
                <tbody style="border:1px solid #eee;">
                    <?
                        for($i = 100 ; $i >= 0; $i--){?>
                            <tr>
                                <td><?=$i?></td>
                                <td><?=$data[$subIdx]['과탐'][$i]['transScore']?></td>
                                <td><?=$data[$subIdx]['사탐'][$i]['transScore']?></td>
                                <td><?=$data[$subIdx]['직탐'][$i]['transScore']?></td>
                            </tr>
                        <?}
                    ?>
                    <tr>
                    </tr>
                </tbody>
            </table>
        </div>
        </section>
        <!-- } 최근 주문내역 끝 -->
    </div>
</div>


<?php
include_once("./_tail.php");
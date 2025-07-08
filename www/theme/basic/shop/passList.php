<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가
if($_SESSION['mb_profile'] != 'C40000001'){
    goto_url('/index');
}
$g5['title'] = '합격자 명단';
include_once('./_head.php');

if(!$str_date){
    $str_date = date('Y');
}

if(!$coltype){
    $coltype = 'ju';
}

if($coltype == 'ju'){
    $tblNm = 'g5_pass_ju_list';
} else {
    $tblNm = 'g5_pass_su_list';
}

$sql_add = "";

if($gender){
    $sql_add .= " AND Gender = '{$gender}' ";
}

$sql = "SELECT * FROM $tblNm WHERE College = '{$college}' AND `Subject` = '{$subject}' $sql_add";

?>
<style>
    select{
        height : auto !important;
    }
    tbody tr{
        border-bottom:1px solid #d3d3d3;
    }
    .sub-Jongmok{
        padding: 5px 0;
    }
    td div:not(:last-child){
        border-bottom:1px solid #e4e4e4;
    }
</style>
<!-- 등급관리 시작 { -->
<div id="smb_my">
    <div id="smb_my_list">
        <!-- 최근 주문내역 시작 { -->
        <section id="smb_my_od" style="margin:0;">
            <form id="fsearch" name="fsearch" onsubmit="return fsearch_submit(this);" class="local_sch01 local_sch" method="get">
                <input type="hidden" id="gender" name="gender" value="<?=$gender?>">
                <input type="hidden" id="coltype" name="coltype" value="<?=$coltype?>">
                <input type="hidden" id="college" name="college" value="<?=$college?>">
                <input type="hidden" id="subject" name="subject" value="<?=$subject?>">
                <div style="display: flex;flex-direction:column;row-gap:10px;margin-bottom:10px;background:white;padding:10px 5px;">
                    <?if($_SESSION['mb_profile'] != 'C40000003'){?>
                    <div style="display: flex;align-items:center;gap:10px;">
                        <div style="font-weight:800;">모&nbsp;&nbsp;&nbsp;집 : </div>
                        <div>
                            <button type="button" class="btn-n <?if($coltype == 'ju') echo "active";?>" onclick="viewJuSu('ju')">정시</button>
                            <button type="button" class="btn-n <?if($coltype == 'su') echo "active";?>" onclick="viewJuSu('su')">수시</button>
                        </div>
                    </div>
                    <div style="display: flex;align-items:center;gap:10px;">
                        <div style="font-weight:800;">성&nbsp;&nbsp;&nbsp;별 : </div>
                        <div>
                            <button type="button" class="btn-n <?if($gender == '') echo "active";?>" onclick="viewGender('')">전체</button>
                            <button type="button" class="btn-n <?if($gender == 'M') echo "active";?>" onclick="viewGender('M')">남</button>
                            <button type="button" class="btn-n <?if($gender == 'F') echo "active";?>" onclick="viewGender('F')">여</button>
                        </div>
                    </div>
                    <?}?>
                    <div style="display: flex;align-items:center;gap:10px;">
                        <div style="font-weight:800;">학&nbsp;&nbsp;&nbsp;교 : </div>
                        <div>
                            <select name="selectcollege" id="selectcollege" class="frm_input">
                                <option value="">선택하세요.</option>
                                <?
                                    $passRes = sql_query("SELECT College FROM $tblNm GROUP BY College");
                                    foreach($passRes as $pr => $p){?>
                                        <option value="<?=$p['College']?>" <?if($p['College'] == $college) echo 'selected';?>><?=$p['College']?></option>
                                <? }
                                ?>
                            </select>
                        </div>
                        <div style="font-weight:800;">학&nbsp;&nbsp;&nbsp;과 : </div>
                        <div>
                            <select name="selectsubject" id="selectsubject" class="frm_input">
                                <option value="">대학먼저 선택해 주세요.</option>
                                <?if($college){
                                    $subRes = sql_query("SELECT `Subject` FROM $tblNm WHERE College = '{$college}' GROUP BY `Subject`");
                                    foreach($subRes as $sr => $s){?>
                                        <option value="<?=$s['Subject']?>" <?if($s['Subject'] == $subject) echo 'selected';?>><?=$s['Subject']?></option>
                                <?  }
                                }?>
                            </select>
                        </div>
                    </div>
                </div>
            </form>
            <?if($subject){?>
            <div class="tbl_wrap border-tb scroll-y" style="overflow-x: auto;max-height:73vh;">
                <?if($coltype == 'ju'){?>
                <table class="tbl_head01" style="width: auto;">
                    <thead>
                        <tr class="headd">
                            <th style="position:sticky;top:0;z-index:13;min-width:50px;width:50px;background:rgba(227, 244, 248);border-right:1px solid white;" rowspan="2">성별</th>
                            <th style="position:sticky;top:0;z-index:13;min-width:100px;width:100px;background:rgba(227, 244, 248);border-right:1px solid white;" colspan="5">국어</th>
                            <th style="position:sticky;top:0;z-index:13;min-width:100px;width:100px;background:rgba(227, 244, 248);border-right:1px solid white;" colspan="5">수학</th>
                            <th style="position:sticky;top:0;z-index:13;min-width:100px;width:100px;background:rgba(227, 244, 248);border-right:1px solid white;" colspan="2">영어</th>
                            <th style="position:sticky;top:0;z-index:13;min-width:100px;width:100px;background:rgba(227, 244, 248);border-right:1px solid white;" colspan="5">탐구1</th>
                            <th style="position:sticky;top:0;z-index:13;min-width:100px;width:100px;background:rgba(227, 244, 248);border-right:1px solid white;" colspan="5">탐구2</th>
                            <th style="position:sticky;top:0;z-index:13;min-width:100px;width:100px;background:rgba(227, 244, 248);border-right:1px solid white;" colspan="2">한국사</th>
                            <th style="position:sticky;top:0;z-index:13;min-width:100px;width:100px;background:rgba(227, 244, 248);border-right:1px solid white;" rowspan="2">내신</th>
                            <th style="position:sticky;top:0;z-index:13;min-width:50px;width:50px;background:rgba(227, 244, 248);border-right:1px solid white;" rowspan="2">군</th>
                            <th style="position:sticky;top:0;z-index:13;min-width:100px;width:100px;background:rgba(227, 244, 248);border-right:1px solid white;" rowspan="2">수능</th>
                            <th style="position:sticky;top:0;z-index:13;min-width:100px;width:100px;background:rgba(227, 244, 248);border-right:1px solid white;" rowspan="2">내신</th>
                            <th style="position:sticky;top:0;z-index:13;min-width:100px;width:100px;background:rgba(227, 244, 248);border-right:1px solid white;" rowspan="2">실기</th>
                            <th style="position:sticky;top:0;z-index:13;min-width:100px;width:100px;background:rgba(227, 244, 248);border-right:1px solid white;" rowspan="2">총점</th>
                            <th style="position:sticky;top:0;z-index:13;min-width:100px;width:100px;background:rgba(227, 244, 248);border-right:1px solid white;" colspan="2">합격</th>
                            <th style="position:sticky;top:0;z-index:13;min-width:100px;width:100px;background:rgba(227, 244, 248);" colspan="3">종목</th>
                        </tr>
                    <tr class="sub-header">
                        <th style="position:sticky;top:45px;min-width:100px;background:rgba(227, 244, 248);border-right:1px solid white;border-top:1px solid white;">과목</th>
                        <th style="position:sticky;top:45px;min-width:100px;background:rgba(227, 244, 248);border-right:1px solid white;border-top:1px solid white;">원</th>
                        <th style="position:sticky;top:45px;min-width:100px;background:rgba(227, 244, 248);border-right:1px solid white;border-top:1px solid white;">표</th>
                        <th style="position:sticky;top:45px;min-width:100px;background:rgba(227, 244, 248);border-right:1px solid white;border-top:1px solid white;">백</th>
                        <th style="position:sticky;top:45px;min-width:100px;background:rgba(227, 244, 248);border-right:1px solid white;border-top:1px solid white;">등</th>
                        <th style="position:sticky;top:45px;min-width:100px;background:rgba(227, 244, 248);border-right:1px solid white;border-top:1px solid white;">과목</th>
                        <th style="position:sticky;top:45px;min-width:100px;background:rgba(227, 244, 248);border-right:1px solid white;border-top:1px solid white;">원</th>
                        <th style="position:sticky;top:45px;min-width:100px;background:rgba(227, 244, 248);border-right:1px solid white;border-top:1px solid white;">표</th>
                        <th style="position:sticky;top:45px;min-width:100px;background:rgba(227, 244, 248);border-right:1px solid white;border-top:1px solid white;">백</th>
                        <th style="position:sticky;top:45px;min-width:100px;background:rgba(227, 244, 248);border-right:1px solid white;border-top:1px solid white;">등</th>
                        <th style="position:sticky;top:45px;min-width:100px;background:rgba(227, 244, 248);border-right:1px solid white;border-top:1px solid white;">원</th>
                        <th style="position:sticky;top:45px;min-width:100px;background:rgba(227, 244, 248);border-right:1px solid white;border-top:1px solid white;">등</th>
                        <th style="position:sticky;top:45px;min-width:100px;background:rgba(227, 244, 248);border-right:1px solid white;border-top:1px solid white;">과목</th>
                        <th style="position:sticky;top:45px;min-width:100px;background:rgba(227, 244, 248);border-right:1px solid white;border-top:1px solid white;">원</th>
                        <th style="position:sticky;top:45px;min-width:100px;background:rgba(227, 244, 248);border-right:1px solid white;border-top:1px solid white;">표</th>
                        <th style="position:sticky;top:45px;min-width:100px;background:rgba(227, 244, 248);border-right:1px solid white;border-top:1px solid white;">백</th>
                        <th style="position:sticky;top:45px;min-width:100px;background:rgba(227, 244, 248);border-right:1px solid white;border-top:1px solid white;">등</th>
                        <th style="position:sticky;top:45px;min-width:100px;background:rgba(227, 244, 248);border-right:1px solid white;border-top:1px solid white;">과목</th>
                        <th style="position:sticky;top:45px;min-width:100px;background:rgba(227, 244, 248);border-right:1px solid white;border-top:1px solid white;">원</th>
                        <th style="position:sticky;top:45px;min-width:100px;background:rgba(227, 244, 248);border-right:1px solid white;border-top:1px solid white;">표</th>
                        <th style="position:sticky;top:45px;min-width:100px;background:rgba(227, 244, 248);border-right:1px solid white;border-top:1px solid white;">백</th>
                        <th style="position:sticky;top:45px;min-width:100px;background:rgba(227, 244, 248);border-right:1px solid white;border-top:1px solid white;">등</th>
                        <th style="position:sticky;top:45px;min-width:100px;background:rgba(227, 244, 248);border-right:1px solid white;border-top:1px solid white;">원</th>
                        <th style="position:sticky;top:45px;min-width:100px;background:rgba(227, 244, 248);border-right:1px solid white;border-top:1px solid white;">등</th>
                        <th style="position:sticky;top:45px;min-width:100px;background:rgba(227, 244, 248);border-right:1px solid white;border-top:1px solid white;">최초</th>
                        <th style="position:sticky;top:45px;min-width:100px;background:rgba(227, 244, 248);border-right:1px solid white;border-top:1px solid white;">최종</th>
                        <th style="position:sticky;top:45px;min-width:100px;background:rgba(227, 244, 248);border-right:1px solid white;border-top:1px solid white;">종목</th>
                        <th style="position:sticky;top:45px;min-width:100px;background:rgba(227, 244, 248);border-right:1px solid white;border-top:1px solid white;">기록</th>
                        <th style="position:sticky;top:45px;min-width:100px;background:rgba(227, 244, 248);border-right:1px solid white;border-top:1px solid white;">점수</th>
                    </tr>
                </thead>
                    <tbody>
                    <?
                        $res = sql_query($sql);
                        foreach($res as $rs => $s){
                            if($s['Gender'] == "M"){
                                $g = "남";
                            } else {
                                $g = "여";
                            }
                            $silgiJong = "";
                            $silgiScore = "";
                            $silgiRecord = "";
                            $silgi = explode('/',$s['ssSilgiText']);
                            if($silgi[0] == '비실기'){
                                $silgiJong = '비실기';
                            } else if(!$silgi[0]){

                            } else {
                                foreach($silgi as $sil => $si){
                                    $note = explode(',',$si);
                                    $i = 0;
                                    foreach($note as $nt => $n){
                                        switch($i){
                                            case 0 :
                                                $silgiJong .= '<div class="sub-Jongmok">'. $n . '</div>';
                                                break;
                                            case 1 :
                                                $silgiScore .= '<div class="sub-Jongmok">'. $n . '</div>';
                                                break;
                                            case 2 :
                                                $silgiRecord .= '<div class="sub-Jongmok">'. $n . '</div>';
                                                break;
                                        }
                                        $i++;
                                    }
                                    $i = 0;
                                }
                            }
                            ?>
                            <tr class="connt">
                                <td style="max-width:50px;text-align:center;"><?=$g?></td>
                                <td style="max-width:50px;text-align:center;"><?=$s['ssKorSubject']?></td>
                                <td style="max-width:100px;text-align:center;"><?=$s['ssKorOrigin']?></td>
                                <td style="max-width:50px;text-align:center;"><?=$s['ssKorPscore']?></td>
                                <td style="max-width:180px;text-align:center;"><?=$s['ssKorSscore']?></td>
                                <td style="max-width:50px;text-align:center;"><?=$s['ssKorGrade']?></td>
                                <td style="max-width:100px;text-align:center;"><?=$s['ssMathSubject']?></td>
                                <td style="width:150px;text-align:center;"><?=$s['ssMathOrigin']?></td>
                                <td style="width:150px;text-align:center;"><?=$s['ssMathPscore']?></td>
                                <td style="width:150px;text-align:center;"><?=$s['ssMathSscore']?></td>
                                <td style="width:150px;text-align:center;"><?=$s['ssMathGrade']?></td>
                                <td style="width:150px;text-align:center;"><?=$s['ssEngOrigin']?></td>
                                <td style="width:150px;text-align:center;"><?=$s['ssEngGrade']?></td>
                                <td style="width:150px;text-align:center;"><?=$s['ssTam1Subject']?></td>
                                <td style="width:150px;text-align:center;"><?=$s['ssTam1Origin']?></td>
                                <td style="width:150px;text-align:center;"><?=$s['ssTam1Pscore']?></td>
                                <td style="width:150px;text-align:center;"><?=$s['ssTam1Sscore']?></td>
                                <td style="width:150px;text-align:center;"><?=$s['ssTam1Grade']?></td>
                                <td style="width:150px;text-align:center;"><?=$s['ssTam2Subject']?></td>
                                <td style="width:150px;text-align:center;"><?=$s['ssTam2Origin']?></td>
                                <td style="width:150px;text-align:center;"><?=$s['ssTam2Pscore']?></td>
                                <td style="width:150px;text-align:center;"><?=$s['ssTam2Sscore']?></td>
                                <td style="width:150px;text-align:center;"><?=$s['ssTam2Grade']?></td>
                                <td style="width:150px;text-align:center;"><?=$s['ssHisOrigin']?></td>
                                <td style="width:150px;text-align:center;"><?=$s['ssHisGrade']?></td>
                                <td style="width:150px;text-align:center;"><?=$s['ssGrade']?></td>
                                <td style="width:150px;text-align:center;"><?=$s['ssGun']?></td>
                                <td style="width:150px;text-align:center;"><?=$s['ssSuScore']?></td>
                                <td style="width:150px;text-align:center;"><?=$s['ssGradeScore']?></td>
                                <td style="width:150px;text-align:center;"><?=$s['ssSilgiScore']?></td>
                                <td style="width:150px;text-align:center;"><?=$s['ssTotalScore']?></td>
                                <td style="width:150px;text-align:center;"><?=$s['ssResult1']?></td>
                                <td style="width:150px;text-align:center;"><?=$s['ssResult2']?></td>
                                <td style="text-align:center;padding : 0px;">
                                    <?=$silgiJong?>
                                </td>
                                <td style="text-align:center;padding : 0px;">
                                    <?=$silgiScore?>
                                </td>
                                <td style="text-align:center;padding : 0px;">
                                    <?=$silgiRecord?>
                                </td>
                            </tr>
                        <?}?>
                    </tbody>
                </table>
                <?}else{?>
                    <table class="tbl_head01" style="width: auto;">
                    <thead>
                        <tr class="headd">
                            <th style="position:sticky;top:0;z-index:13;min-width:50px;width:50px;background:rgba(227, 244, 248);border-right:1px solid white;" rowspan="2">성별</th>
                            <th style="position:sticky;top:0;z-index:13;min-width:200px;width:200px;background:rgba(227, 244, 248);border-right:1px solid white;" rowspan="2">전형명</th>
                            <th style="position:sticky;top:0;z-index:13;min-width:100px;width:100px;background:rgba(227, 244, 248);border-right:1px solid white;" rowspan="2">등급</th>
                            <th style="position:sticky;top:0;z-index:13;min-width:100px;width:100px;background:rgba(227, 244, 248);border-right:1px solid white;" rowspan="2">내신</th>
                            <th style="position:sticky;top:0;z-index:13;min-width:100px;width:100px;background:rgba(227, 244, 248);border-right:1px solid white;" rowspan="2">실기</th>
                            <th style="position:sticky;top:0;z-index:13;min-width:100px;width:100px;background:rgba(227, 244, 248);border-right:1px solid white;" rowspan="2">기타</th>
                            <th style="position:sticky;top:0;z-index:13;min-width:100px;width:100px;background:rgba(227, 244, 248);border-right:1px solid white;" rowspan="2">총점</th>
                            <th style="position:sticky;top:0;z-index:13;min-width:100px;width:100px;background:rgba(227, 244, 248);border-right:1px solid white;" colspan="2">합격</th>
                            <th style="position:sticky;top:0;z-index:13;width:100%;background:rgba(227, 244, 248);border-right:1px solid white;" colspan="3">종목</th>
                        </tr>
                        <tr class="sub-header">
                            <th style="position:sticky;top:45px;min-width:100px;background:rgba(227, 244, 248);border-right:1px solid white;border-bottom: 1px solid #d3d3d3;">최초</th>
                            <th style="position:sticky;top:45px;min-width:100px;background:rgba(227, 244, 248);border-right:1px solid white;border-bottom: 1px solid #d3d3d3;">최종</th>
                            <th style="position:sticky;top:45px;min-width:100px;background:rgba(227, 244, 248);border-right:1px solid white;border-bottom: 1px solid #d3d3d3;">종목</th>
                            <th style="position:sticky;top:45px;min-width:100px;background:rgba(227, 244, 248);border-right:1px solid white;border-bottom: 1px solid #d3d3d3;">기록</th>
                            <th style="position:sticky;top:45px;min-width:100px;background:rgba(227, 244, 248);border-right:1px solid white;border-bottom: 1px solid #d3d3d3;">점수</th>
                        </tr>
                </thead>
                    <tbody>
                    <?
                        $res = sql_query($sql);
                        foreach($res as $rs => $s){
                            if($s['Gender'] == "M"){
                                $g = "남";
                            } else {
                                $g = "여";
                            }
                            $silgiJong = "";
                            $silgiScore = "";
                            $silgiRecord = "";
                            $silgi = explode('/',$s['psuSilgiText']);
                            if($silgi[0] == '비실기'){
                                $silgiJong = '비실기';
                            } else if(!$silgi[0]){

                            } else {
                                foreach($silgi as $sil => $si){
                                    $note = explode(',',$si);
                                    $i = 0;
                                    foreach($note as $nt => $n){
                                        switch($i){
                                            case 0 :
                                                $silgiJong .= '<div class="sub-Jongmok">'. $n . '</div>';
                                                break;
                                            case 1 :
                                                $silgiScore .= '<div class="sub-Jongmok">'. $n . '</div>';
                                                break;
                                            case 2 :
                                                $silgiRecord .= '<div class="sub-Jongmok">'. $n . '</div>';
                                                break;
                                        }
                                        $i++;
                                    }
                                    $i = 0;
                                }
                            }
                            ?>
                            <tr class="connt">
                                <td style="max-width:50px;text-align:center;"><?=$g?></td>
                                <td style="max-width:50px;text-align:center;"><?=$s['psuPassType']?></td>
                                <td style="max-width:100px;text-align:center;"><?=$s['psuGrade']?></td>
                                <td style="max-width:50px;text-align:center;"><?=$s['psuNaesin']?></td>
                                <td style="max-width:180px;text-align:center;"><?=$s['psuSilgi']?></td>
                                <td style="max-width:50px;text-align:center;"><?=$s['psuOther']?></td>
                                <td style="max-width:100px;text-align:center;"><?=$s['psuTotal']?></td>
                                <td style="width:150px;text-align:center;"><?=$s['psuResult1']?></td>
                                <td style="width:150px;text-align:center;"><?=$s['psuResult2']?></td>
                                <td style="text-align:center;padding : 0px;">
                                    <?=$silgiJong?>
                                </td>
                                <td style="text-align:center;padding : 0px;">
                                    <?=$silgiScore?>
                                </td>
                                <td style="text-align:center;padding : 0px;">
                                    <?=$silgiRecord?>
                                </td>
                            </tr>
                        <?}?>
                    </tbody>
                </table>
                <?}?>
        </div>
            <?} else {?>
                <div class="tbl_wrap border-tb">
                <table class="tbl_head01">
                    <tbody>
                        <tr>
                            <td style="text-align:center;">검색 결과가 없습니다.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <?}?>
        </section>
        <!-- } 최근 주문내역 끝 -->
    </div>
</div>

<script>
    function viewTypeChange(e){
        let id = e.currentTarget.id;
        let classes = e.currentTarget.classList;
        let cnt = $(".subje.active").length;
        if(id == 'tall'){
            if(classes.contains('active')){

            } else {
                document.querySelectorAll('.subje').forEach((el,i,arr)=>{
                    if(el.id != id){
                        el.classList.remove('active');
                    } else{
                        el.classList.add('active');
                    }
                });
            }
            
        } else {
            $("#tall").removeClass('active');
            if(classes.contains('active')){
                if(cnt == 1){
                    document.querySelectorAll('.subje').forEach((el,i,arr)=>{
                        if(el.id != 'tall'){
                            el.classList.remove('active');
                        } else{
                            el.classList.add('active');
                        }
                    });
                } else {
                    classes.remove('active');
                }
            } else {
                $(`#${id}`).addClass('active');
                if($(".subje.active").length == $(".subje").length -1){
                    document.querySelectorAll('.subje').forEach((el,i,arr)=>{
                        if(el.id != 'tall'){
                            el.classList.remove('active');
                        } else{
                            el.classList.add('active');
                        }
                    });
                }
            }
        }
        resetView();
    }

    function fsearch_submit(e) {
    }

    function resetView(){
        var arrs = [];
        var arrs2 = [];
        if($("#tall").hasClass('active')){
            document.querySelectorAll('.subje').forEach((el,i,arr)=>{
                arrs.push(el.id);
            });
            for(let i = 0; i < arrs.length; i++){
                document.querySelectorAll(`.${arrs[i]}`).forEach((el,i,arr)=>{
                    el.style.display = '';
                });
            }
        } else {
            document.querySelectorAll('.subje').forEach((el,i,arr)=>{
                if(el.classList.contains('active')){
                    arrs.push(el.id);
                } else {
                    arrs2.push(el.id);
                }
            });
    
            for(let i = 0; i < arrs2.length; i++){
                document.querySelectorAll(`.${arrs2[i]}`).forEach((el,i,arr)=>{
                    el.style.display = 'none';
                });
            }
    
            for(let i = 0; i < arrs.length; i++){
                document.querySelectorAll(`.${arrs[i]}`).forEach((el,i,arr)=>{
                    el.style.display = '';
                });
            }
        }
        
        document.querySelectorAll(".avg").forEach((el,i,arr)=>{
            let total = 0;
            let cnt = 0;
            for(let j=7; j<el.parentElement.children.length - 2;j++){
                if(arrs.some(cls => el.parentElement.children[j].classList.contains(cls)) && el.parentElement.children[j].classList[1] == `${el.parentElement.children[j].classList[0]}_score`){
                    if(el.parentElement.children[j].textContent != ''){
                        total+=Number(el.parentElement.children[j].textContent);
                        cnt++;
                    }
                }
            }
            el.previousElementSibling.textContent = total;
            if(cnt == 0){
                el.textContent = 0;
            } else {
                el.textContent = Math.round((total/cnt)*100)/100;
            }
        });
    }

    function viewGender(e){
        $("#gender").val(e);
        $("#fsearch").submit();
    }

    function viewJuSu(e){
        $("#college").val('');
        $("#subject").val('');
        $("#coltype").val(e);
        $("#fsearch").submit();
    }

    $("#selectcollege").on('change',function(){
        $("#college").val($(this).val());
        $("#subject").val('');
        $("#fsearch").submit();
    });

    $("#selectsubject").on('change',function(){
        $("#subject").val($(this).val());
        $("#fsearch").submit();
    });
</script>
<!-- } 마이페이지 끝 -->

<?php
include_once("./_tail.php");
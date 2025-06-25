<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

$g5['title'] = '성적산출';
include_once('./_head.php');


switch($_SESSION['mb_profile']){
    case 'C40000001':
        break;
    case 'C40000002':
        $bid = $_SESSION['mb_signature'];
        break;
}

if(!$selMonth){
    $selMonth = '3모';
}

$query_string = http_build_query(array(
    'bid' => $_GET['bid'],
));

?>

<style>
    .outputScore table tr th{
        background-color: rgba(31, 119, 180,0.1) !important;
    }
    .outputScore th, .outputScore td{
        border: 1px solid #d3d3d3 !important;   
        padding:8px 5px !important;
        text-align: center !important;
    }
    .outputScore table input{
        width:100%;
        text-align: center;
    }
    .isauto{
        pointer-events: none;
        border: unset !important;
        background-color: unset !important;
        box-shadow: unset !important;
    }
</style>
<!-- 마이페이지 시작 { -->
<div id="smb_my">

    <div id="smb_my_list">
        <!-- 성적입력 시작 { -->
        <!-- <section id="smb_my_od">
            <div style="display: flex; align-items:center;gap:30px;margin-bottom:10px;">
                <div style="display: flex;gap:10px;">
                    <button class="btn-n btn-green btn-bold" type="buttton" onclick="saveGrade()">저장</button>
                    <?
                        foreach($m_cmmn as $mcm => $m){
                            $cnt = sql_fetch("SELECT COUNT(*) as cnt FROM g5_member_score WHERE memId = '{$membId}' AND scoreMonth = '{$m['code']}'");
                            ?>
                        <button class="btn-n <?if($month == $m['code']) echo "active";?> <?if($cnt['cnt'] > 0) {echo "iswrite";}else{echo "btn-gray";}?>" id="<?=$m['code']?>" onclick="viewMonth(event)" type="buttton"><?=$m['codeName']?></button>
                    <?}?>
                </div>
                <div style="position:absolute;right:0;">
                    점수 업데이트 : <?=$recD['regDate']?>
                </div>
            </div>


            <div class="tbl_wrap border-tb">
                <table class="tbl_head01">
                    <colgroup width="20%">
                    <colgroup width="20%">
                    <colgroup width="20%">
                    <colgroup width="20%">
                    <colgroup width="20%">
                    <thead>
                        <th>영역</th>
                        <th>원점수</th>
                        <th>표준점수</th>
                        <th>백분위</th>
                        <th>등급</th>
                    </thead>
                    <tbody>
                        <?
                        $subs = sql_query("SELECT code, codeName,upperCode FROM g5_cmmn_code gcc WHERE upperCode = (SELECT code FROM g5_cmmn_code WHERE codeName = '과목' AND useYn = 1) ORDER BY codeDesc");
                        foreach($subs as $sub => $s){
                            $i = 0;
                            $sub = "";
                            $memberGrade = sql_fetch("SELECT gms.* FROM g5_member_score gms WHERE gms.memId = '{$membId}' AND gms.scoreMonth = '{$month}' AND upperCode = '{$s['code']}'");
                            ?>
                            <tr style="text-align: center;" class="mySubgrade">
                                <?if($s['codeName'] != '영어' && $s['codeName'] != '한국사' && $s['codeName'] != '제2외국어/한문'){?>
                                <td style="text-align: left;">
                                    <?=$s['codeName']?><br>
                                    <select name="subject" class="frm_input" style="width: 100%;">
                                        <option value="">선택하세요</option>
                                        <?$jsql = sql_query("SELECT * FROM g5_cmmn_code WHERE upperCode = '{$s['code']}'");
                                        foreach($jsql as $js => $j){
                                            if(!$memberGrade['subject']){
                                                if(strstr($s['code'],'C2005')){
                                                    $i2 = 1;
                                                    if($i == 1){
                                                        $sub = $j['code'];
                                                    }
                                                } else {
                                                    $i2 = 0;
                                                    if($i == 0){
                                                        $sub = $j['code'];
                                                    }
                                                }
                                            }
                                            ?>
                                            <option value="<?=$j['code']?>" <?if(($memberGrade['subject'] == $j['code']) || (!$memberGrade['subject'] && $i==$i2)) echo 'selected';?>><?=$j['codeName']?></option>
                                        <?$i++;}?>
                                    </select>
                                    <input type="hidden" name="subjectCode" value="<?if($memberGrade['subject']){echo "{$memberGrade['subject']}";}else{echo "{$sub}";}?>">
                                    <input type="hidden" name="upperCode" value="<?=$s['code']?>">
                                </td>
                                <td><br><input type="number" oninput="this.value = Math.max(0, Math.min(<?if($s['codeName'] == '탐구영역1' || $s['codeName'] == '탐구영역2'){echo 50;}else{ echo 100;}?>, this.value))" class="frm_input" style="width: 100%;text-align:center;" name="origin" value="<?=$memberGrade['origin']?>"></td>
                                <td><br><input type="number" class="frm_input" style="width: 100%;text-align:center;" name="pscore" value="<?=$memberGrade['pscore']?>"></td>
                                <td><br><input type="number" class="frm_input" style="width: 100%;text-align:center;" name="sscore" value="<?=$memberGrade['sscore']?>"></td>
                                <td><br><input type="number" class="frm_input" style="width: 100%;text-align:center;" name="grade" value="<?=$memberGrade['grade']?>"></td>
                                <?} else if($s['codeName'] == '제2외국어/한문'){?>
                                    <td style="text-align: left;">
                                    <?=$s['codeName']?><br>
                                    <select name="subject" class="frm_input" style="width: 100%;">
                                        <option value="">선택하세요</option>
                                        <?$jsql = sql_query("SELECT * FROM g5_cmmn_code WHERE upperCode = '{$s['code']}'");
                                        foreach($jsql as $js => $j){
                                            ?>
                                            <option value="<?=$j['code']?>" <?if(($memberGrade['subject'] == $j['code'])) echo 'selected';?>><?=$j['codeName']?></option>
                                        <?}?>
                                    </select>
                                    <input type="hidden" name="subjectCode" value="<?if($memberGrade['subject']){echo "{$memberGrade['subject']}";}?>">
                                    <input type="hidden" name="upperCode" value="<?=$s['code']?>">
                                </td>
                                <td><br><input type="number" oninput="this.value = Math.max(0, Math.min(50, this.value))" class="frm_input" style="width: 100%;text-align:center;" name="origin" value="<?=$memberGrade['origin']?>"></td>
                                <td><br>-</td>
                                <td><br>-</td>
                                <td><br><input type="number" class="frm_input" style="width: 100%;text-align:center;" name="grade" value="<?=$memberGrade['grade']?>"></td>
                                <?} else{
                                    $subJectCd = sql_fetch("SELECT code FROM g5_cmmn_code WHERE upperCode = '{$s['code']}'");
                                    ?>
                                    <td style="text-align: left;">
                                        <?=$s['codeName']?>
                                        <input type="hidden" name="subjectCode" value="<?=$subJectCd['code']?>">
                                        <input type="hidden" name="upperCode" value="<?=$s['code']?>">
                                    </td>
                                    <td><br><input type="number" oninput="this.value = Math.max(0, Math.min(<?if($s['codeName'] == '한국사'){echo 50;}else{ echo 100;}?>, this.value))" class="frm_input" style="width: 100%;text-align:center;" name="origin" value="<?=$memberGrade['origin']?>"></td>
                                    <td><br>-</td>
                                    <td><br>-</td>
                                    <td><br><input type="number" class="frm_input" style="text-align:center;width: 100%;" name="grade" value="<?=$memberGrade['grade']?>"></td>    
                                <?}?>
                            </tr>
                        <?}?>
                        
                        <tr style="text-align: center;">
                            <td style="text-align:left;">
                                내신
                            </td>
                            <td colspan="4">
                                <select class="frm_input" id="grade" name="grade" style="width: 100%;">
                                    <option value="">선택하세요.</option>
                                    <?
                                        $admitt = sql_query("SELECT code, codeName FROM g5_cmmn_code WHERE upperCode = 'C50000000' AND useYN = 1");
                                        $memberGrade = sql_fetch("SELECT gms.* FROM g5_member_score gms WHERE gms.memId = '{$membId}' AND gms.scoreMonth = '{$month}' AND upperCode = 'C50000000'");
                                        foreach($admitt as $adm => $a){
                                    ?>
                                    <option value="<?=$a['code']?>" <?if($memberGrade['subject'] == $a['code']) echo 'selected';?>><?=$a['codeName']?></option>
                                    <?}?>
                                </select>
                                <input type="hidden" name="admittupperCode" id="admittupperCode" value="C50000000">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div style="margin-top:10px;">
                <button class="btn-n btn-green btn-bold btn-large" type="buttton" onclick="saveGrade()">저장</button>
            </div>
        </section> -->
        <!-- } 성적입력 끝 -->
    </div>
    <div id="smb_my_list" style="width: 100%;">
        <!-- 지원대학 시작 { -->
        <section id="smb_my_od">
            
                <input type="hidden" id="kor_Code" name="kor_Code">
                <input type="hidden" id="math_Code" name="math_Code">
                <input type="hidden" id="eng_Code" name="eng_Code">
                <input type="hidden" id="tam1_Code" name="tam1_Code">
                <input type="hidden" id="tam2_Code" name="tam2_Code">
                <input type="hidden" id="his_Code" name="his_Code">
                <div class="tbl_wrap outputScore" style="width:60vw;">
                    <table class="tbl_head01 tbl_2n_color">
                        <colgroup>
                            <col width="100px">
                            <col width="100px">
                            <col width="100px">
                            <col width="100px">
                            <col width="100px">
                            <col width="100px">
                            <col width="100px">
                            <col width="100px">
                            <col width="100px">
                        </colgroup>
                        <tbody>
                            <tr>
                                <th rowspan="2">캠퍼스</th>
                                <td rowspan="2">
                                <form id="fsearch" name="fsearch" onsubmit="return fsearch_submit(this);" class="local_sch01 local_sch" method="get">
                                    <select id="bid" name="bid" style="border:1px solid #d3d3d3;height: 45px;width:100%;padding:5px;" <?if($_SESSION['mb_profile'] == 'C40000002') echo 'class="isauto"';?>>
                                        <option value="">선택</option>
                                        <?
                                            $camSql = sql_query("SELECT * FROM g5_branch");
                                            
                                            foreach($camSql as $cm => $c){
                                        ?>
                                            <option value="<?=$c['idx']?>" <?if($bid == $c['idx']) echo 'selected';?>><?=$c['branchName']?></option>
                                        <?}?>
                                    </select>
                                </form>
                                </td>
                                <th colspan="7">성적표</th>
                            </tr>
                            <tr>
                                <th></th>
                                <th>국어</th>
                                <th>수학</th>
                                <th>영어</th>
                                <th>탐구1</th>
                                <th>탐구2</th>
                                <th>한국사</th>
                            </tr>
                            <tr>
                                <th rowspan="2">시험구분</th>
                                <td rowspan="2" class="selMonth">
                                    <?if($bid){?>
                                        <select name="selMonth" id="selMonth"  style="border:1px solid #d3d3d3;height: 45px;width:100%;padding:5px;">
                                            <?
                                                $msql = sql_query("SELECT * FROM g5_cmmn_code WHERE upperCode = (SELECT code FROM g5_cmmn_code WHERE codeName = '모의고사')");
                                                foreach($msql as $ms => $m){
                                            ?>
                                                <option value="<?=$m['code']?>"><?=$m['codeName']?></option>
                                            <?}?>
                                        </select>
                                    <?}else{?>
                                        캠퍼스 먼저 선택해주세요.
                                    <?}?>
                                </td>
                                <th>과목</th>
                                <td><input type="text" class="frm_input isauto" name="korSub"></td>
                                <td><input type="text" class="frm_input isauto" name="mathSub"></td>
                                <td><input type="text" class="frm_input isauto" name="engSub"></td>
                                <td><input type="text" class="frm_input isauto" name="tamSub1"></td>
                                <td><input type="text" class="frm_input isauto" name="tamSub2"></td>
                                <td><input type="text" class="frm_input isauto" name="hisSub"></td>
                            </tr>
                            <tr>
                                <th>원점수</th>
                                <td class="kor"><input oninput="this.value = Math.max(0, Math.min(100, this.value))" name="kor_Origin" class="frm_input isauto" type="number"></td>
                                <td class="math"><input oninput="this.value = Math.max(0, Math.min(100, this.value))" name="math_Origin" class="frm_input isauto" type="number"></td>
                                <td class="eng"><input oninput="this.value = Math.max(0, Math.min(100, this.value))" name="eng_Origin" class="frm_input isauto" type="number"></td>
                                <td class="tam1"><input oninput="this.value = Math.max(0, Math.min(50, this.value))" name="tam1_Origin" class="frm_input isauto" type="number"></td>
                                <td class="tam2"><input oninput="this.value = Math.max(0, Math.min(50, this.value))" name="tam2_Origin" class="frm_input isauto" type="number"></td>
                                <td class="his"><input oninput="this.value = Math.max(0, Math.min(50, this.value))" name="his_Origin" class="frm_input isauto" type="number"></td>
                            </tr>
                            <tr>
                                <th>이름</th>
                                <td class="studentNm">
                                    <?if($bid){?>
                                        <select name="selStudent" id="selStudent"  style="border:1px solid #d3d3d3;height: 45px;width:100%;padding:5px;">
                                            <option value="" <?if(!$selStudent) echo 'selected';?>>선택하세요.</option>
                                            <?
                                                $memsql = sql_query("SELECT * FROM g5_member WHERE mb_signature = '{$bid}'");
                                                foreach($memsql as $mm => $me){
                                            ?>
                                                <option value="<?=$me['mb_no']?>" <?if($selStudent && $selStudent == $me['mb_no']) echo 'selected';?>><?=$me['mb_name']?></option>
                                            <?}?>
                                        </select>
                                    <?}else{?>
                                        캠퍼스 먼저 선택해주세요.
                                    <?}?>
                                </td>
                                <th>최고표점</th>
                                <td class="kor"><input type="number" class="frm_input isauto" name="kor_TopRate"></td>
                                <td class="math"><input type="number" class="frm_input isauto" name="math_TopRate"></td>
                                <td></td>
                                <td class="tam1"><input type="number" class="frm_input isauto" name="tam1_TopRate"></td>
                                <td class="tam2"><input type="number" class="frm_input isauto" name="tam2_TopRate"></td>
                                <td></td>
                            </tr>
                            <tr>
                                <th>학교</th>
                                <td class="school"></td>
                                <th>표준점수</th>
                                <td><input type="number" name="kor_Pscore" class="frm_input isauto"></td>
                                <td><input type="number" name="math_Pscore" class="frm_input isauto"></td>
                                <td></td>
                                <td><input type="number" name="tam1_Pscore" class="frm_input isauto"></td>
                                <td><input type="number" name="tam2_Pscore" class="frm_input isauto"></td>
                                <td></td>
                            </tr>
                            <tr>
                                <th>학년</th>
                                <td class="layer"></td>
                                <th>백분위</th>
                                <td><input type="text" name="kor_Sscore" class="frm_input isauto"></td>
                                <td><input type="text" name="math_Sscore" class="frm_input isauto"></td>
                                <td></td>
                                <td><input type="text" name="tam1_Sscore" class="frm_input isauto"></td>
                                <td><input type="text" name="tam2_Sscore" class="frm_input isauto"></td>
                                <td></td>
                            </tr>
                            <tr>
                                <th>성별</th>
                                <td class="gender"></td>
                                <th>등급</th>
                                <td><input type="text" name="kor_Grade" class="frm_input isauto" /></td>
                                <td><input type="text" name="math_Grade" class="frm_input isauto" /></td>
                                <td><input type="text" name="eng_Grade" class="frm_input isauto" /></td>
                                <td><input type="text" name="tam1_Grade" class="frm_input isauto" /></td>
                                <td><input type="text" name="tam2_Grade" class="frm_input isauto" /></td>
                                <td><input type="text" name="his_Grade" class="frm_input isauto" /></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </form>
        </section>
        <!-- } 지원대학 끝 -->
    </div>
</div>



<script>
    let topRate = "";
    $(document).ready(function(){
        $.ajax({
            url: "/bbs/searchTopRate.php",
            type: "POST",
            data: {},
            async: false,
            error: function(data) {
                alert('에러가 발생하였습니다.');
                return false;
            },
            success: function(data) {
                topRate = eval("(" + data + ");");
            }
        });
    })
    function fsearch_submit(e){
        
    }

    let json ="";

    $("#bid").on('change',function(){
        $("#fsearch").submit();
    });

    $("#selStudent").on('change',function(){
        let vl = $(this).val();
        if(vl){
            $.ajax({
                url: "/bbs/searchScore.php",
                type: "POST",
                data: {
                    mb_no : vl,
                },
                async: false,
                error: function(data) {
                    alert('에러가 발생하였습니다.');
                    return false;
                },
                success: function(data) {
                    json = eval("(" + data + ");");
                    showView(json);
                }
            });
        } else {
            rePage();
        }
    });

    $("#selMonth").on('change',function(){
        if($("#selStudent").val()){
            showView(json);
        }
    });

    function showView(data){
        let month = $("#selMonth").val();
        $(".school").text(data['info']['school'] ? data['info']['school'] : '');
        $(".layer").text(data['info']['layer'] ? data['info']['layer'] : '');
        $(".gender").text(data['info']['gender'] ? data['info']['gender'] : '');
        
        if(data['scoreData'][month]){
            $("#kor_Code").val(data['scoreData'][month]['data']['국어']['subCode'] ? data['scoreData'][month]['data']['국어']['subCode']  : '');
            $("#math_Code").val(data['scoreData'][month]['data']['수학']['subCode'] ? data['scoreData'][month]['data']['수학']['subCode']  : '');
            $("#eng_Code").val(data['scoreData'][month]['data']['영어']['subCode'] ? data['scoreData'][month]['data']['영어']['subCode']  : '');
            $("#tam1_Code").val(data['scoreData'][month]['data']['탐구영역1']['subCode'] ? data['scoreData'][month]['data']['탐구영역1']['subCode']  : '');
            $("#tam2_Code").val(data['scoreData'][month]['data']['탐구영역2']['subCode'] ? data['scoreData'][month]['data']['탐구영역2']['subCode']  : '');
            $("#his_Code").val(data['scoreData'][month]['data']['한국사']['subCode'] ? data['scoreData'][month]['data']['한국사']['subCode']  : '');
            $("input[name='kor_Origin']").removeClass('isauto');
            $("input[name='math_Origin']").removeClass('isauto');
            $("input[name='eng_Origin']").removeClass('isauto');
            $("input[name='tam1_Origin']").removeClass('isauto');
            $("input[name='tam2_Origin']").removeClass('isauto');
            $("input[name='his_Origin']").removeClass('isauto');

            $("input[name='korSub']").val(data['scoreData'][month]['data']['국어']['subject'] ? data['scoreData'][month]['data']['국어']['subject'] : '');
            $("input[name='mathSub']").val(data['scoreData'][month]['data']['수학']['subject'] ? data['scoreData'][month]['data']['수학']['subject'] : '');
            $("input[name='engSub']").val('');
            $("input[name='tamSub1']").val(data['scoreData'][month]['data']['탐구영역1']['subject'] ? data['scoreData'][month]['data']['탐구영역1']['subject'] : '');
            $("input[name='tamSub2']").val(data['scoreData'][month]['data']['탐구영역2']['subject'] ? data['scoreData'][month]['data']['탐구영역2']['subject'] : '');
            $("input[name='hisSub']").val('');

            // 원점수
            $("input[name='kor_Origin']").val(data['scoreData'][month]['data']['국어']['origin']);
            $("input[name='math_Origin']").val(data['scoreData'][month]['data']['수학']['origin']);
            $("input[name='eng_Origin']").val(data['scoreData'][month]['data']['영어']['origin']);
            $("input[name='tam1_Origin']").val(data['scoreData'][month]['data']['탐구영역1']['origin']);
            $("input[name='tam2_Origin']").val(data['scoreData'][month]['data']['탐구영역2']['origin']);
            $("input[name='his_Origin']").val(data['scoreData'][month]['data']['한국사']['origin']);
            
            // 최고표점
            $("input[name='kor_TopRate']").val(topRate['topRateData'][month]['data'][data['scoreData'][month]['data']['국어']['subject']]['topRate'] ? topRate['topRateData'][month]['data'][data['scoreData'][month]['data']['국어']['subject']]['topRate'] : 0);
            $("input[name='math_TopRate']").val(topRate['topRateData'][month]['data'][data['scoreData'][month]['data']['수학']['subject']]['topRate'] ? topRate['topRateData'][month]['data'][data['scoreData'][month]['data']['수학']['subject']]['topRate'] : 0);
            $("input[name='tam1_TopRate']").val(topRate['topRateData'][month]['data'][data['scoreData'][month]['data']['탐구영역1']['subject']]['topRate'] ? topRate['topRateData'][month]['data'][data['scoreData'][month]['data']['탐구영역1']['subject']]['topRate'] : 0);
            $("input[name='tam2_TopRate']").val(topRate['topRateData'][month]['data'][data['scoreData'][month]['data']['탐구영역2']['subject']]['topRate'] ? topRate['topRateData'][month]['data'][data['scoreData'][month]['data']['탐구영역2']['subject']]['topRate'] : 0);

            // 표점
            $("input[name='kor_Pscore']").val(data['scoreData'][month]['data']['국어']['pscore']);
            $("input[name='math_Pscore']").val(data['scoreData'][month]['data']['수학']['pscore']);
            $("input[name='tam1_Pscore']").val(data['scoreData'][month]['data']['탐구영역1']['pscore']);
            $("input[name='tam2_Pscore']").val(data['scoreData'][month]['data']['탐구영역2']['pscore']);

            // 백분위
            $("input[name='kor_Sscore']").val(data['scoreData'][month]['data']['국어']['sscore']);
            $("input[name='math_Sscore']").val(data['scoreData'][month]['data']['수학']['sscore']);
            $("input[name='tam1_Sscore']").val(data['scoreData'][month]['data']['탐구영역1']['sscore']);
            $("input[name='tam2_Sscore']").val(data['scoreData'][month]['data']['탐구영역2']['sscore']);

            // 등급
            $("input[name='kor_Grade']").val(data['scoreData'][month]['data']['국어']['grade']);
            $("input[name='math_Grade']").val(data['scoreData'][month]['data']['수학']['grade']);
            $("input[name='eng_Grade']").val(data['scoreData'][month]['data']['영어']['grade']);
            $("input[name='tam1_Grade']").val(data['scoreData'][month]['data']['탐구영역1']['grade']);
            $("input[name='tam2_Grade']").val(data['scoreData'][month]['data']['탐구영역2']['grade']);
            $("input[name='his_Grade']").val(data['scoreData'][month]['data']['한국사']['grade']);


        } else {
            rePage();
        }
    }

    function rePage(){
        $("#kor_Code").val('');
            $("#math_Code").val('');
            $("#eng_Code").val('');
            $("#tam1_Code").val('');
            $("#tam2_Code").val('');
            $("#his_Code").val('');

            $("input[name='kor_Origin']").addClass('isauto');
            $("input[name='math_Origin']").addClass('isauto');
            $("input[name='eng_Origin']").addClass('isauto');
            $("input[name='tam1_Origin']").addClass('isauto');
            $("input[name='tam2_Origin']").addClass('isauto');
            $("input[name='his_Origin']").addClass('isauto');

            $("input[name='korSub']").val('');
            $("input[name='mathSub']").val('');
            $("input[name='engSub']").val('');
            $("input[name='tamSub1']").val('');
            $("input[name='tamSub2']").val('');
            $("input[name='hisSub']").val('');

            // 원점수
            $("input[name='kor_Origin']").val('');
            $("input[name='math_Origin']").val('');
            $("input[name='eng_Origin']").val('');
            $("input[name='tam1_Origin']").val('');
            $("input[name='tam2_Origin']").val('');
            $("input[name='his_Origin']").val('');
            
            // 최고표점
            $("input[name='kor_TopRate']").val('');
            $("input[name='math_TopRate']").val('');
            $("input[name='tam1_TopRate']").val('');
            $("input[name='tam2_TopRate']").val('');

            // 표점
            $("input[name='kor_Pscore']").val('');
            $("input[name='math_Pscore']").val('');
            $("input[name='tam1_Pscore']").val('');
            $("input[name='tam2_Pscore']").val('');

            // 백분위
            $("input[name='kor_Sscore']").val('');
            $("input[name='math_Sscore']").val('');
            $("input[name='tam1_Sscore']").val('');
            $("input[name='tam2_Sscore']").val('');

            // 등급
            $("input[name='kor_Grade']").val('');
            $("input[name='math_Grade']").val('');
            $("input[name='eng_Grade']").val('');
            $("input[name='tam1_Grade']").val('');
            $("input[name='tam2_Grade']").val('');
            $("input[name='his_Grade']").val('');

            $(".school").text('');
            $(".layer").text('');
            $(".gender").text('');
    }

    const cache = {};
    $('input[name*="_Origin"]').on('change', function () {
        const fullName = $(this).attr('name'); // 예: "user_Origin"
        const prefix = $(this).attr('name').split('_')[0]; // '_' 앞 부분만 추출
        const subjectCode = $(`#${prefix}_Code`).val();
        const month = $("#selMonth").val();
        const score = $(this).val();
       

        // const month = "<?=$month?>";
        
        const key = `${subjectCode}-${month}-${score}`; // origin 값 포함!
        
        if (cache[key]) {
            if(prefix != 'eng' && prefix != 'his'){
                    $(`input[name='${prefix}_Pscore']`).val(cache[key].pscore);
                    $(`input[name='${prefix}_Sscore']`).val(cache[key].sscore);
                }
                $(`input[name='${prefix}_Grade']`).val(cache[key].gGrade);
            return;
        }

        $.ajax({
            type: 'POST',
            url: '/bbs/get_gradeCut.php',
            data: { subjectCode, month, score },
            success: function (res) {
                const data = JSON.parse(res);
                cache[key] = data;
                if(prefix != 'eng' && prefix != 'his'){
                    $(`input[name='${prefix}_Pscore']`).val(data.pscore);
                    $(`input[name='${prefix}_Sscore']`).val(data.sscore);
                }
                $(`input[name='${prefix}_Grade']`).val(data.gGrade);
            }
        });
        
    });

</script>
<!-- } 마이페이지 끝 -->

<?php
include_once("./_tail.php");

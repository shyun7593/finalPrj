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
    case 'C40000003':
        $bid = $_SESSION['mb_signature'];
        $selMonth = 'C60000005';
        break;
}

if(!$selMonth){
    $selMonth = 'C60000001';
}

$query_string = http_build_query(array(
    'bid' => $_GET['bid'],
));

?>
<style>
    .collegeInfos tbody td{
        border-bottom: 1px solid #e4e4e4;
    }
    .outputScore table tr th{
        background-color: rgba(31, 119, 180,0.1) !important;
        border-bottom: 1px solid #e4e4e4;
    }
    
    .outputScore th, .outputScore td{
        border: 1px solid white !important;   
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
    .collegeInfos table td{
        text-align: center;
    }
    .collegeInfos table thead tr:not(:first-child) td{
        border-top: 1px solid #e4e4e4;
        border-bottom: 1px solid #e4e4e4;
    }
    .collegeInfos table thead {
        background-color: rgba(31, 119, 180, 0.1) !important;
        font-weight: bold;
    }
    .collegeInfos table .checkAuto, .collegeInfos table td.checkAuto input{
        pointer-events: none !important;
        background-color: #eee !important;
    }
    .cutline.remove-view{
        display: none !important;
    }
    #wrapper_title{
        padding: 10px 0 !important;
    }
    .subDetail:hover{
        color:blue;
        text-decoration: underline;
        font-weight: 800;
        cursor:pointer;
    }
    .tbl_head01 td{
        padding : 10px 5px;
    }
</style>
<!-- 마이페이지 시작 { -->
<div id="smb_my">
    <div id="smb_my_list" style="width: 100%;">
        <section id="smb_my_od" style="margin:unset;">
                <input type="hidden" id="kor_Code" name="kor_Code">
                <input type="hidden" id="math_Code" name="math_Code">
                <input type="hidden" id="eng_Code" name="eng_Code" value="C20030001">
                <input type="hidden" id="tam1_Code" name="tam1_Code">
                <input type="hidden" id="tam2_Code" name="tam2_Code">
                <input type="hidden" id="his_Code" name="his_Code" value="C20060001">
                <div class="tbl_wrap outputScore border-tb" style="border:2px solid #828282 !important;border-radius:5px;position:fixed;top:5px;right:5px;z-index:3;">
                    <button type="button" id="viewhideOutput" style="width:30px;height:30px;top:-3px;left:-30px;position: absolute;border: unset;border-radius: 50%;color: white;background: #0c2233;transform: rotate(90deg);"><i id="hide_btn" class="xi-caret-up"></i></button>
                    <table class="tbl_head01 tbl_2n_color" style="margin:0px !important;padding:5px;">
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
                                    <select id="bid" name="bid" style="border:1px solid #d3d3d3;height: 45px;width:100%;padding:5px;" <?if($_SESSION['mb_profile'] != 'C40000001') echo 'class="isauto"';?>>
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
                                <th><button class="btn-n active" onclick="resetScore()">점수 초기화</button></th>
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
                                            <option value="">선택해주세요</option>
                                            <?
                                                $msql = sql_query("SELECT * FROM g5_cmmn_code WHERE upperCode = (SELECT code FROM g5_cmmn_code WHERE codeName = '모의고사')");
                                                foreach($msql as $ms => $m){
                                            ?>
                                                <option value="<?=$m['code']?>" <?if($selMonth == $m['code']) echo "selected";?>><?=$m['codeName']?></option>
                                            <?}?>
                                        </select>
                                    <?}else{?>
                                        캠퍼스 먼저 선택해주세요.
                                    <?}?>
                                </td>
                                <th>과목</th>
                                <td>
                                    <select class="frm_input" id="korSelect" name="korSelect" onchange="reselect(event,'kor')">
                                        <option value="">선택해주세요</option>
                                        <?$korsub = sql_query("SELECT * FROM g5_cmmn_code WHERE upperCode = 'C20010000' AND useYN = 1");
                                        foreach($korsub as $ks => $k){?>
                                            <option value="<?=$k['codeName']?>" data-value="<?=$k['code']?>"><?=$k['codeName']?></option>
                                        <?}?>
                                    </select>
                                    <input type="hidden" class="frm_input isauto" name="korSub">
                                </td>
                                <td>
                                    <select class="frm_input" id="mathSelect" name="mathSelect" onchange="reselect(event,'math')">
                                        <option value="">선택해주세요</option>
                                        <?$korsub = sql_query("SELECT * FROM g5_cmmn_code WHERE upperCode = 'C20020000' AND useYN = 1");
                                        foreach($korsub as $ks => $k){?>
                                            <option value="<?=$k['codeName']?>" data-value="<?=$k['code']?>"><?=$k['codeName']?></option>
                                        <?}?>
                                    </select>
                                    <input type="hidden" class="frm_input isauto" name="mathSub">
                                </td>
                                <td>
                                    <input type="text" class="frm_input isauto" name="engSub">
                                </td>
                                <td>
                                    <select class="frm_input" id="tam1Select" name="tam1Select" onchange="reselect(event,'tam1')">
                                        <option value="">선택해주세요</option>
                                        <?$korsub = sql_query("SELECT * FROM g5_cmmn_code WHERE upperCode = 'C20040000' AND useYN = 1");
                                        foreach($korsub as $ks => $k){?>
                                            <option value="<?=$k['codeName']?>" data-value="<?=$k['code']?>"><?=$k['codeName']?></option>
                                        <?}?>
                                    </select>
                                    <input type="hidden" class="frm_input isauto" name="tam1Sub"> 
                                </td>
                                <td>
                                    <select class="frm_input" id="tam2Select" name="tam2Select" onchange="reselect(event,'tam2')">
                                        <option value="">선택해주세요</option>
                                        <?$korsub = sql_query("SELECT * FROM g5_cmmn_code WHERE upperCode = 'C20050000' AND useYN = 1");
                                        foreach($korsub as $ks => $k){?>
                                            <option value="<?=$k['codeName']?>" data-value="<?=$k['code']?>"><?=$k['codeName']?></option>
                                        <?}?>
                                    </select>
                                    <input type="hidden" class="frm_input isauto" name="tam2Sub"> 
                                </td>
                                <td>
                                    <input type="text" class="frm_input isauto" name="hisSub">
                                </td>
                            </tr>
                            <tr>
                                <th>원점수</th>
                                <td class="kor"><input oninput="this.value = Math.max(0, Math.min(100, this.value))" name="kor_Origin" class="frm_input" type="number"></td>
                                <td class="math"><input oninput="this.value = Math.max(0, Math.min(100, this.value))" name="math_Origin" class="frm_input" type="number"></td>
                                <td class="eng"><input oninput="this.value = Math.max(0, Math.min(100, this.value))" name="eng_Origin" class="frm_input" type="number"></td>
                                <td class="tam1"><input oninput="this.value = Math.max(0, Math.min(50, this.value))" name="tam1_Origin" class="frm_input" type="number"></td>
                                <td class="tam2"><input oninput="this.value = Math.max(0, Math.min(50, this.value))" name="tam2_Origin" class="frm_input" type="number"></td>
                                <td class="his"><input oninput="this.value = Math.max(0, Math.min(50, this.value))" name="his_Origin" class="frm_input" type="number"></td>
                            </tr>
                            <tr>
                                <th>이름</th>
                                <td class="studentNm">
                                    <?if($bid){?>
                                        <select name="selStudent" id="selStudent" style="border:1px solid #d3d3d3;height: 45px;width:100%;padding:5px;text-align:center;" <?if($_SESSION['mb_profile'] != 'C40000001' && $_SESSION['mb_profile'] != 'C40000002') echo 'class="isauto"';?>>
                                            <option value="">선택하세요.</option>
                                            <?
                                                $memsql = sql_query("SELECT * FROM g5_member WHERE mb_signature = '{$bid}' AND mb_profile = 'C40000003'");
                                                foreach($memsql as $mm => $me){
                                            ?>
                                                <option value="<?=$me['mb_no']?>" data-id="<?=$me['mb_id']?>" <?if($_SESSION['mb_profile'] == 'C40000003' && $_SESSION['mb_no'] == $me['mb_no']) echo 'selected';?>><?=$me['mb_name']?></option>
                                            <?}?>
                                        </select>
                                    <?}else{?>
                                        캠퍼스 먼저 선택해주세요.
                                    <?}?>
                                </td>
                                <th>최고표점</th>
                                <td class="kor"><input type="number" class="frm_input chScore" name="kor_TopRate"></td>
                                <td class="math"><input type="number" class="frm_input chScore" name="math_TopRate"></td>
                                <td></td>
                                <td class="tam1"><input type="number" class="frm_input chScore" name="tam1_TopRate"></td>
                                <td class="tam2"><input type="number" class="frm_input chScore" name="tam2_TopRate"></td>
                                <td></td>
                            </tr>
                            <tr>
                                <th>학교</th>
                                <td class="school"></td>
                                <th>표준점수</th>
                                <td><input type="number" name="kor_Pscore" class="frm_input chScore"></td>
                                <td><input type="number" name="math_Pscore" class="frm_input chScore"></td>
                                <td></td>
                                <td><input type="number" name="tam1_Pscore" class="frm_input chScore"></td>
                                <td><input type="number" name="tam2_Pscore" class="frm_input chScore"></td>
                                <td></td>
                            </tr>
                            <tr>
                                <th>학년</th>
                                <td class="layer"></td>
                                <th>백분위</th>
                                <td><input type="text" name="kor_Sscore" class="frm_input chScore" oninput="this.value = Math.max(0, Math.min(100, this.value))"></td>
                                <td><input type="text" name="math_Sscore" class="frm_input chScore" oninput="this.value = Math.max(0, Math.min(100, this.value))"></td>
                                <td></td>
                                <td><input type="text" name="tam1_Sscore" class="frm_input chScore" oninput="this.value = Math.max(0, Math.min(100, this.value))"></td>
                                <td><input type="text" name="tam2_Sscore" class="frm_input chScore" oninput="this.value = Math.max(0, Math.min(100, this.value))"></td>
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
                <div style="display: flex;flex-direction:column;row-gap:7px;background:white;padding:10px 5px;">
                <div style="display: flex;align-items:center;gap:10px;">
                    <div style="font-weight:800;">모&nbsp;&nbsp;&nbsp;집 : </div>
                    <div>
                        <button type="button" data-value="" class="ctype btn-n active" onclick="viewTypeChange(event)">전체</button>
                        <?
                            $gsql = sql_query("SELECT * FROM g5_cmmn_code WHERE upperCode ='C30000000' AND useYN = 1");
                            foreach($gsql as $gs => $g){
                        ?>
                            <button type="button" data-value="<?=$g['code']?>" class="ctype btn-n" onclick="viewTypeChange(event)"><?=$g['codeName']?></button>
                        <?}?>
                    </div>
                </div>
                <div style="display: flex;align-items:center;gap:10px;">
                    <div style="font-weight:800;">지&nbsp;&nbsp;&nbsp;역 : </div>
                    <div>
                        <button type="button" data-value="" class="areaCode btn-n active" onclick="viewArea(event,'')">전체</button>
                        <?
                            $asql = sql_query("SELECT * FROM g5_cmmn_code WHERE upperCode ='C10000000' AND useYN = 1");
                            foreach($asql as $as => $a){
                        ?>
                            <button type="button" data-value="<?=$a['code']?>" class="areaCode btn-n" onclick="viewArea(event,'<?=$a['codeName']?>')"><?=$a['codeName']?></button>
                        <?}?>
                    </div>
                </div>
                <div style="display: flex;align-items:center;gap:10px;">
                    <div style="font-weight:800;">검&nbsp;&nbsp;&nbsp;색 : </div>
                    <div style="display: flex;justify-content:center;align-items:center;gap:10px;min-width:500px;">
                        <td style="padding:10px;"><input type="text" name="textCol" id="textCol" placeholder="대학명" class="frm_input textSearch" style="width: 100%;padding:0 10px;" value="<?=$textCol?>"></td>
                        <td style="padding:10px;"><input type="text" name="textSub" id="textSub" placeholder="학과명" class="frm_input textSearch" style="width: 100%;padding:0 10px;" value="<?=$textSub?>"></td>
                        <td style="padding:10px;"><input type="button" class="search-btn" id="searchEnter" value="" style="width:50px !important;" onclick="viewColleges('',1)"></td>
                    </div>
                </div>
                <div>
                    <h2 style="margin:5px 0 0px !important;">전체 : <span class="totalCnt"></span>
                     <!-- / 현재 페이지 : <span class="now-page"></span> -->
                    </h2>
                </div>
            </div>
            </form>
        </section>
        <!-- } 지원대학 끝 -->
    </div>
    <div id="smb_my_list">
        <!-- 최근 주문내역 시작 { -->
        <section id="smb_my_od" style="margin-bottom: 0px;">
            <div class="mb20">
            <div class="tbl_wrap collegeInfos border-tb" style="height: 74vh;overflow-y:auto;">
                    <table class="tbl_head01 tbl_2n_color" style="margin-bottom: 0px;border-collapse: separate !important;border-spacing: 0 !important;">
                        <colgroup>
                        </colgroup>
                        <thead style="position: sticky;top:0;z-index:2;background:rgba(227, 244, 248) !important;">
                            <tr style="border-bottom:1px solid #e4e4e4 !important;">
                                <td style="width:60px;"></td>
                                <td colspan="2">추천대학</td>
                                <td colspan="6">대학정보</td>
                                <td>수능</td>
                                <td class="cutline" colspan="5">커트라인</td>
                                <td colspan="2">실기</td>
                            </tr>
                            <tr>
                                <td style="width:60px;"></td>
                                <td>선생님</td>
                                <td>본인</td>
                                <td>지역</td>
                                <td>형태</td>
                                <td>군</td>
                                <td>대학명</td>
                                <td>학과명</td>
                                <td>인원</td>
                                <td>환산점수</td>
                                <td class="cutline">수능</td>
                                <td class="cutline">내신</td>
                                <td class="cutline">실기</td>
                                <td class="cutline">기타</td>
                                <td class="cutline">총점</td>
                                <td>점수</td>
                                <td>계산</td>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
        <!-- <div class="paging" style="text-align:center; margin:20px 0;"></div> -->
    </div>
</div>
<div id="custom-tooltip" style="display:none; position:absolute; background:#fff; border:1px solid #ccc; padding:8px; font-size:13px; z-index:9999; box-shadow:0 2px 8px rgba(0,0,0,0.2);"></div>

<div id="collegePopup">
    <div class="mb20" id="collegeDiv">
        
    </div>
</div>

<script>
    let topRate = "";
    let curpage = 1;
    let areas = [];
    let coll;
    let json ="";
    let transDatas = [];
    $("#viewhideOutput").on('click',function(){
        $(".outputScore").toggleClass('viewType');
        $(this).toggleClass('viewType');
    });
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
                console.log(eval("(" + data + ");"));
                topRate = eval("(" + data + ");");
            }
        });

        $.ajax({
            url: "/bbs/searchTransScores.php",
            type: "POST",
            data: {},
            async: false,
            error: function(data) {
                alert('에러가 발생하였습니다.');
                return false;
            },
            success: function(data) {
                transDatas = eval("(" + data + ");");
            }
        });

        if("<?=$_SESSION['mb_profile']?>" == 'C40000003'){
            viewScores('<?=$_SESSION['mb_no']?>');
            viewColleges('<?=$_SESSION['mb_no']?>');
        }
    });

    function viewTypeChange(e){
        document.querySelectorAll('.ctype').forEach((el,i,arr)=>{
            if(el == e.currentTarget){
                el.classList.add('active');
            } else {
                el.classList.remove('active');
            }
        });
        if($("#selStudent").val()){
            viewScores($("#selStudent").val());
            viewColleges($("#selStudent").val());
        }
    }

    function viewArea(e,area){
        areas = [];
        e.currentTarget.classList.toggle('active');
        if(document.querySelectorAll('.areaCode').length - 1 == document.querySelectorAll('.areaCode.active').length || area == ''){
            document.querySelectorAll('.areaCode').forEach((el,i,arr)=>{
                if(el.textContent == '전체'){
                    el.classList.add('active');
                } else {
                    el.classList.remove('active');
                }
            });
        } else {
            if(document.querySelectorAll('.areaCode').length - 1 == document.querySelectorAll('.areaCode.active').length || area == ''){
                document.querySelectorAll('.areaCode').forEach((el,i,arr)=>{
                    if(el.textContent == '전체'){
                        el.classList.add('active');
                    } else {
                        el.classList.remove('active');
                    }
                });
            } else {
                document.querySelectorAll('.areaCode').forEach((el,i,arr)=>{
                    if(el.textContent == '전체'){
                        el.classList.remove('active');
                    }
                });
            }
        }
        document.querySelectorAll('.areaCode.active').forEach((el,i,arr)=>{
            if(el.dataset.value){
                areas.push("'" + el.dataset.value + "'");
            }
        });
    }
    function fsearch_submit(e){
        
    }

    $("#bid").on('change',function(){
        $("#fsearch").submit();
    });

    $("#selStudent").on('change',function(){
        let vl = $(this).val();
        if(vl){
            viewScores(vl);
            viewColleges(vl);
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
            
            $("#tam1_Code").val(data['scoreData'][month]['data']['탐구영역1']['subCode'] ? data['scoreData'][month]['data']['탐구영역1']['subCode']  : '');
            $("#tam2_Code").val(data['scoreData'][month]['data']['탐구영역2']['subCode'] ? data['scoreData'][month]['data']['탐구영역2']['subCode']  : '');
            
            $("input[name='kor_Origin']").removeClass('isauto');
            $("input[name='math_Origin']").removeClass('isauto');
            $("input[name='eng_Origin']").removeClass('isauto');
            $("input[name='tam1_Origin']").removeClass('isauto');
            $("input[name='tam2_Origin']").removeClass('isauto');
            $("input[name='his_Origin']").removeClass('isauto');


            $("#korSelect").val(data['scoreData'][month]['data']['국어']['subject'] ? data['scoreData'][month]['data']['국어']['subject'] : '');
            $("#mathSelect").val(data['scoreData'][month]['data']['수학']['subject'] ? data['scoreData'][month]['data']['수학']['subject'] : '');
            $("#tam1Select").val(data['scoreData'][month]['data']['탐구영역1']['subject'] ? data['scoreData'][month]['data']['탐구영역1']['subject'] : '');
            $("#tam2Select").val(data['scoreData'][month]['data']['탐구영역2']['subject'] ? data['scoreData'][month]['data']['탐구영역2']['subject'] : '');

            
            $("input[name='korSub']").val(data['scoreData'][month]['data']['국어']['subject'] ? data['scoreData'][month]['data']['국어']['subject'] : '');
            $("input[name='mathSub']").val(data['scoreData'][month]['data']['수학']['subject'] ? data['scoreData'][month]['data']['수학']['subject'] : '');
            $("input[name='tam1Sub']").val(data['scoreData'][month]['data']['탐구영역1']['subject'] ? data['scoreData'][month]['data']['탐구영역1']['subject'] : '');
            $("input[name='tam2Sub']").val(data['scoreData'][month]['data']['탐구영역2']['subject'] ? data['scoreData'][month]['data']['탐구영역2']['subject'] : '');
            

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
            if(topRate['topRateData'][month]['data'][data['scoreData'][month]['data']['탐구영역1']['subject']]){
                $("input[name='tam1_TopRate']").val(topRate['topRateData'][month]['data'][data['scoreData'][month]['data']['탐구영역1']['subject']]['topRate'] ? topRate['topRateData'][month]['data'][data['scoreData'][month]['data']['탐구영역1']['subject']]['topRate'] : 0);
            } else {
                $("input[name='tam1_TopRate']").val(0);
            }

            if(topRate['topRateData'][month]['data'][data['scoreData'][month]['data']['탐구영역1']['subject']]){
                $("input[name='tam2_TopRate']").val(topRate['topRateData'][month]['data'][data['scoreData'][month]['data']['탐구영역2']['subject']]['topRate'] ? topRate['topRateData'][month]['data'][data['scoreData'][month]['data']['탐구영역2']['subject']]['topRate'] : 0);
            }else {
                $("input[name='tam2_TopRate']").val(0);
            }

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
            if(coll){
                calcJuScore(coll.data);
            }
        } else {
            rePage();
        }
    }

    function viewScores(val){
        $.ajax({
            url: "/bbs/searchScore.php",
            type: "POST",
            data: {
                mb_no : val,
            },
            async: false,
            error: function(data) {
                alert('에러가 발생하였습니다.');
                return false;
            },
            success: function(data) {
                json = eval("(" + data + ");");
                console.log(json);
                showView(json);
            }
        });
    }

    $(".textSearch").on('keydown',function(e){
        if(e.keyCode == 13){
            $("#searchEnter").click();
        }
    })

    function viewColleges(val,page){
        if(!page){
            page = 1;
        } else {
            curpage = page;
        }
        if(!val){
            val = $("#selStudent option:selected").val();
        }

        if(!val){
            setTimeout(() => {
                swal("경고!","학생 먼저 선택해주세요.","info");
                $("#viewhideOutput").removeClass('viewType');
                $(".outputScore ").removeClass('viewType');

            }, 10);
            return false;
        }
        $(".collegeInfos table tbody").html('');
        $(".paging").html('');
        $(".totalCnt").html('0');
        $(".now-page").html('0');
        $.ajax({
            url: "/bbs/searchCollege.php",
            type: "POST",
            data: {
                mb_no : val,
                page : page,
                rows : 20,
                area: areas,
                code : $(".ctype.active").data('value'),
                textCol : $("#textCol").val(),
                textSub : $("#textSub").val(),
            },
            async: false,
            error: function(data) {
                alert('에러가 발생하였습니다.');
                return false;
            },
            success: function(data) {
                coll = eval("(" + data + ");");
                console.log(coll);
                // drawPaging(val,coll['paging'].page, coll['paging'].total_page, 'viewColleges');
                $(".totalCnt").html(coll['paging'].total_count + '개');
                // $(".now-page").html(coll['paging'].page + ' of ' + coll['paging'].total_page);
                coll.data.forEach(function(row, idx){
                    let no = (coll['paging'].page - 1) * 20 + (idx + 1);
                    addCollegeRow(no,row.subIdx,row.teacher,row.student,row.areaNm,row.collegeType,row.gun,row.collegeNm,row.subjectNm,row.person,row.pSub,row.silgi);
                });
                if(coll.data.length>0){
                    calcJuScore(coll.data);
                }
            }
        });
    }

    function addCollegeRow(no,subIdx,teacher,student,area,type,gun,college,subject,person,silgi,silgiexist){
        if(!person){
            person = '-';
        }

        let html = `
            <tr>
                <td style="width:60px;">${no}</td>
                <td`;
        if('<?=$_SESSION['mb_profile']?>' == 'C40000003'){
            html += ` class="checkAuto"`;
        }
        html += `><input type="checkbox" onclick="addCollege(this,${subIdx})" style="cursor:pointer;"`;
        
        if(teacher==1){
            html += ` checked`;
        }
        html += `></td>
                <td`;
        if('<?=$_SESSION['mb_profile']?>' != 'C40000003'){
            html += ` class="checkAuto"`;
        }
        html += `><input type="checkbox" onclick="addCollege(this,${subIdx})" style="cursor:pointer;"`;
        
        if(student==1){
            html += ` checked`;
        }
        html += `></td>
                <td>${area}</td>
                <td>${type}</td>
                <td>${gun}</td>
                <td class="subDetail" data-tooltip='${no-1}'>${college}</td>
                <td>${subject}</td>
                <td>${person}</td>
                <td class="changeScore${no}"></td>
                <td class="cutline"></td>
                <td class="cutline"></td>
                <td class="cutline"></td>
                <td class="cutline"></td>
                <td class="cutline"></td>
                <td>0</td>
                <td>`;
        if(silgi){
            html += `<button type="button" class="btn-n`;
            if(silgiexist > 0){
                html += ` active`;
            }
            html += `" onclick="showSilgi('${subIdx}','${area}','${gun}','${college}','${subject}','${silgi}')">계산</button>`;
        } 
        html += `</td>
            </tr>
        `;
        $(".collegeInfos table tbody").append(html);
        if('<?=$_SESSION['mb_profile']?>' == 'C40000003'){
            $(".cutline").addClass('remove-view');
        }
    }

    $(document).on("mouseenter", ".subDetail", function (e) {
        let thData = coll.data[$(this).data("tooltip")];
        
        let html = `
        <h2 style="margin-bottom:5px;">${thData['collegeNm']} - ${thData['subjectNm']}</h2>
        <table style="border-collapse: collapse;text-align:center;min-width:300px;">
            <tr>
                <th style="border:1px solid #ccc;padding:5px;min-width:50px;"></th>
                <th style="border:1px solid #ccc;padding:5px;min-width:50px;">국어</th>
                <th style="border:1px solid #ccc;padding:5px;min-width:50px;">수학</th>
                <th style="border:1px solid #ccc;padding:5px;min-width:50px;">영어</th>
                <th style="border:1px solid #ccc;padding:5px;min-width:50px;">과탐/사탐</th>
                <th style="border:1px solid #ccc;padding:5px;min-width:50px;">한국사</th>
            </tr>
            <tr>
                <td style="border:1px solid #ccc;padding:5px;">비율</td>
                <td style="border:1px solid #ccc;padding:5px;">${thData['juKorrate'] ? thData['juKorrate'] : '-'}</td>
                <td style="border:1px solid #ccc;padding:5px;">${thData['juMathrate'] ? thData['juMathrate'] : '-'}</td>
                <td style="border:1px solid #ccc;padding:5px;">${thData['juEngrate'] ? thData['juEngrate'] : '-'}</td>
                <td style="border:1px solid #ccc;padding:5px;">${thData['juTamrate'] ? thData['juTamrate'] : '-'}</td>
                <td style="border:1px solid #ccc;padding:5px;">${thData['juHisAdd'] ? thData['juHisAdd'] : '-'}</td>
            </tr>
            <tr>
                <td style="border:1px solid #ccc;padding:5px;">선/필</td>
                <td style="border:1px solid #ccc;padding:5px;">${thData['juKorSelect'] ? thData['juKorSelect'] : '-'}</td>
                <td style="border:1px solid #ccc;padding:5px;">${thData['juMathSelect'] ? thData['juMathSelect'] : '-'}</td>
                <td style="border:1px solid #ccc;padding:5px;">${thData['juEngSelect'] ? thData['juEngSelect'] : '-'}</td>
                <td style="border:1px solid #ccc;padding:5px;">${thData['juTamSelect'] ? thData['juTamSelect'] : '-'}</td>
                <td style="border:1px solid #ccc;padding:5px;">${thData['juHisSelect'] ? thData['juHisSelect'] : '-'}</td>
            </tr>
            <tr>
                <td style="border:1px solid #ccc;padding:5px;">기준</td>
                <td style="border:1px solid #ccc;padding:5px;">${thData['juChar'] ? thData['juChar'] : '-'}</td>
                <td style="border:1px solid #ccc;padding:5px;">${thData['juChar'] ? thData['juChar'] : '-'}</td>
                <td style="border:1px solid #ccc;padding:5px;">${thData['engList'] ? '상세확인' : '-'}</td>
                <td style="border:1px solid #ccc;padding:5px;">${thData['juTamChar'] ? thData['juTamChar'] : '-'}</td>
                <td style="border:1px solid #ccc;padding:5px;">${thData['histList'] ? '상세확인' : '-'}</td>
            </tr>
            <tr>
                <td style="border:1px solid #ccc;padding:5px;">제외</td>
                <td style="border:1px solid #ccc;padding:5px;">-</td>
                <td style="border:1px solid #ccc;padding:5px;">-</td>
                <td style="border:1px solid #ccc;padding:5px;">-</td>
                <td style="border:1px solid #ccc;padding:5px;">${thData['juTamSub'] ? thData['juTamSub'] : '-'}</td>
                <td style="border:1px solid #ccc;padding:5px;">-</td>
            </tr>
            <tr style="background-color:#eee;font-weight:bold;">
                <td style="border:1px solid #ccc;padding:5px;" colspan="6">총점 : ${thData['juTotal'] ? thData['juTotal'] : '-'} / 수능 : ${thData['juSrate'] ? thData['juSrate'] : '-'} / 실기 : ${thData['juPrate'] ? thData['juPrate'] : '-'}</td>
            </tr>
        </table>`;

        $("#custom-tooltip").html(html).show();
    }).on("mousemove", ".subDetail", function (e) {
        const tooltip = $("#custom-tooltip");
        const tooltipWidth = tooltip.outerWidth();
        const tooltipHeight = tooltip.outerHeight();
        const winWidth = $(window).width();
        const winHeight = $(window).height();

        let left;
        if($("#wrapper").hasClass('full')){
            left = e.pageX - 50;
        } else {
            left = e.pageX - 250;
        }
        
        let top = e.pageY - 20;

        if (e.clientX + tooltipWidth + 20 > winWidth) {
            left = e.pageX - tooltipWidth - 10;
        }
        if (e.clientY + tooltipHeight + 40 > winHeight) {
            top = e.pageY - tooltipHeight - 20;
        }

        tooltip.css({ left, top });
    }).on("mouseleave", ".subDetail", function () {
        $("#custom-tooltip").hide();
    });


    function showSilgi(subIdx,area,gun,college,subject,sub){
        let silg = ""
        $.ajax({
            url: "/bbs/searchCollegeSilgi.php",
            type: "POST",
            data: {
                subIdx : subIdx,
                id : $("#selStudent option:selected").data('id'),
            },
            async: false,
            error: function(data) {
                alert('저장 실패! 관리자에게 문의하세요.');
            },
            success: function(data) {
                silg = eval("(" + data + ");");
            }
        });
        const count = Object.keys(silg['data']).length;
        
        let subs = sub.split(',');
        let html = `<div style="display: flex;justify-content: center;align-items: center;gap: 15px;font-size: 2em;font-weight: 800;">
                    [실기] [${area}, ${gun}] ${college} ${subject}
                </div>
        <div>
            <table class="tbl_frm01 tbl_wrap" style="margin-top:20px;border-collapse:collapse;">
                <colgroup>
                    <col width="60%">
                    <col width="20%">
                    <col width="20%">
                </colgroup>
                <thead>
                    <tr style="border:1px solid #e4e4e4;font-size:1.5em;">
                        <th style="font-weight:800;text-align:left;background-color:#d3d3d3;border-right:1px solid white;">실기</th>
                        <th style="font-weight:800;text-align:center;background-color:#d3d3d3;border-right:1px solid white;">기록</th>
                        <th style="font-weight:800;text-align:center;background-color:#d3d3d3;">점수</th>
                    <tr>
                </thead>
                <tbody>
        `;
        if(count > 0){
            for(let j = 0; j < count; j++){
                html += `
                    <tr style="font-size:1.2em;">
                        <td style="border:1px solid #e4e4e4;">${silg['data'][j]['subject']}</td>
                        <td style="border:1px solid #e4e4e4;"><input name="${silg['data'][j]['subject']}" type="text" class="frm_input" value="${silg['data'][j]['recode']}"></td>
                        <td style="border:1px solid #e4e4e4;">${silg['data'][j]['score']}</td>
                    </tr>
                `;
            }
        } else {
            for(let i = 0; i < subs.length; i++){
                html += `
                    <tr style="font-size:1.2em;">
                        <td style="border:1px solid #e4e4e4;">${subs[i]}</td>
                        <td style="border:1px solid #e4e4e4;"><input name="${subs[i]}" type="text" class="frm_input"></td>
                        <td style="border:1px solid #e4e4e4;">0</td>
                    </tr>
                `;
            }
        }
        html += `
                <tbody>
            </table>
        </div>
        <div style="position:absolute;bottom:20px;right:20px;display:flex;gap:15px;">
            <button id="calcSilgi" type="button" style="width:120px;height:50px;font-size:1.5em;" class="btn-n iswrite"`;
        if(count > 0){
            html += `onclick="calcSilgi(${subIdx},'update')"`;
        } else {
            html += `onclick="calcSilgi(${subIdx},'add')"`;
        }
        html += ` >계산</button>
            <button type="button" style="width:120px;height:50px;font-size:1.5em;" class="btn-n" id="closePopup">닫기</button>
        </div>`;
        $("#collegeDiv").html(html);

        $('#popupBackground').fadeIn(); // 배경 표시
        $('#collegePopup').fadeIn(); // 팝업 표시
        $('#closePopup, #popupBackground').click(function() {
            $('#popupBackground').fadeOut(); // 배경 숨기기
            $('#collegePopup').fadeOut(); // 팝업 숨기기
        });
    }

    function calcSilgi(idx,type){
        let datas = [];

        document.querySelectorAll("#collegeDiv table tbody input[type='text']").forEach((el,i,arr)=>{
            let recode = el.value;
            let score = el.parentNode.nextElementSibling.textContent;
            let subject = el.parentNode.previousElementSibling.textContent;
            datas.push({
                'subject':subject,
                'recode':recode,
                'score':score
            });
        });
        
        $.ajax({
            url: "/bbs/collegeSilgi_update.php",
            type: "POST",
            data: {
                datas : datas,
                type : type,
                subIdx : idx,
                id : $("#selStudent option:selected").data('id'),
            },
            async: false,
            error: function(data) {
                alert('저장 실패! 관리자에게 문의하세요.');
            },
            success: function(data) {
                if(data == 'success'){
                    let msg = "";
                    if(type == 'add'){
                        msg = "실기점수가 등록되었습니다.";
                    } else {
                        msg = "실기점수가 수정되었습니다.";
                    }
                    swal('성공!',msg,'success');
                    setTimeout(() => {
                        swal.close();
                        $("#closePopup").click();
                        viewColleges($("#selStudent option:selected").val(),curpage);
                    }, 1500);
                }
            }
        });
    }

    function addCollege(el,idx){
        let type = "";
        if($(el).is(':checked')){
            type = "add";
        } else {
            type = "remove";
        }
        $.ajax({
            url: "/bbs/inteCollege_update.php",
            type: "POST",
            data: {
                type : type,
                idx : idx,
                id : $("#selStudent option:selected").data('id'),
            },
            async: false,
            error: function(data) {
                alert('저장 실패! 관리자에게 문의하세요.');
            },
            success: function(data) {
                if(data == 'success'){
                    let msg = "";
                    if(type == 'add'){
                        msg = "관심대학 등록되었습니다.";
                    } else {
                        msg = "관심대학에서 삭제되었습니다.";
                    }
                    swal('성공!',msg,'success');
                    setTimeout(() => {
                        swal.close();
                    }, 1500);
                }
            }
        });
    }

    function rePage(){
            $("#korSelect").val('');
            $("#mathSelect").val('');
            $("#tam1Select").val('');
            $("#tam2Select").val('');

            $("#kor_Code").val('');
            $("#math_Code").val('');
            
            $("#tam1_Code").val('');
            $("#tam2_Code").val('');
            

            $("input[name='korSub']").val('');
            $("input[name='mathSub']").val('');
            $("input[name='engSub']").val('');
            $("input[name='tam1Sub']").val('');
            $("input[name='tam2Sub']").val('');
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
    }

    const cache = {};
    $('input[name*="_Origin"]').on('change', function () {
        const fullName = $(this).attr('name'); // 예: "user_Origin"
        const prefix = $(this).attr('name').split('_')[0]; // '_' 앞 부분만 추출
        const subjectCode = $(`#${prefix}_Code`).val();
        const month = $("#selMonth").val();
        const score = $(this).val();
        if(prefix == 'kor' || prefix == 'math' || prefix == 'tam1' || prefix == 'tam2'){
            if(!$(`#${prefix}Select`).val()){
                swal("","과목을 먼저 선택해주세요.","warning");
                $(this).val('');
                return false;
            }
            $(`input[name='${prefix}_TopRate']`).val(topRate['topRateData'][month]['data'][$(`#${prefix}Select`).val()]['topRate']);
        }

        // const month = "<?=$month?>";
        
        const key = `${subjectCode}-${month}-${score}`; // origin 값 포함!
        if (cache[key]) {
            if(prefix != 'eng' && prefix != 'his'){
                    if(cache[key].pscore){
                        $(`input[name='${prefix}_Pscore']`).val(cache[key].pscore);
                        $(`input[name='${prefix}_Sscore']`).val(cache[key].sscore);
                    }
                }
                $(`input[name='${prefix}_Grade']`).val(cache[key].gGrade);
                calcJuScore(coll.data);
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
                calcJuScore(coll.data);
            }
        });
        
    });

    $(".chScore").on('change',function(){
        if(coll.data.length>0){
            calcJuScore(coll.data);
        }
    });

    function reselect(e,type){
        let selectedOption = e.currentTarget.options[e.currentTarget.selectedIndex];
    
        let value = selectedOption.value;
        let dataValue = selectedOption.dataset.value;
        
        $(`input[name='${type}Sub']`).val(value);
        $(`input[name*='${type}_'`).val(0);
        $(`#${type}_Code`).val(dataValue);
        $(`#${type}_Grade`).val('');
    }

    function resetScore(){
        let monthSel = $("#selMonth").val();
        $("#selMonth").val(monthSel);
        if($("#selMonth").val()){
            showView(json);
        }
    }

</script>
<!-- } 마이페이지 끝 -->

<?php
include_once("./_tail.php");

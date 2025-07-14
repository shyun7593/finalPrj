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
    .outputScore table tr th{
        background-color: rgba(31, 119, 180,0.1) !important;
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
</style>
<!-- 마이페이지 시작 { -->
<div id="smb_my">
    <div id="smb_my_list" style="width: 100%;">
        <section id="smb_my_od" style="margin:unset;">
                <input type="hidden" id="kor_Code" name="kor_Code">
                <input type="hidden" id="math_Code" name="math_Code">
                <input type="hidden" id="eng_Code" name="eng_Code">
                <input type="hidden" id="tam1_Code" name="tam1_Code">
                <input type="hidden" id="tam2_Code" name="tam2_Code">
                <input type="hidden" id="his_Code" name="his_Code">
                <div class="tbl_wrap outputScore border-tb" style="border:2px solid #828282 !important;border-radius:5px;position:fixed;top:5px;right:5px;z-index:3;<?if($_SESSION['mb_profile'] == 'C40000003') echo "display:none;";?>">
                    <button type="button" id="viewhideOutput" style="width:30px;height:30px;top:-3px;left:-12px;position: absolute;border: unset;border-radius: 50%;color: white;background: #0c2233;transform: rotate(90deg);"><i id="hide_btn" class="xi-caret-up"></i></button>
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
                                                <option value="<?=$m['code']?>" <?if($selMonth == $m['code']) echo "selected";?>><?=$m['codeName']?></option>
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
                        <td style="padding:10px;"><input type="text" name="text" id="text" placeholder="대학명, 학과명" class="frm_input" style="width: 100%;padding:0 10px;" value="<?=$text?>"></td>
                        <td style="padding:10px;"><input type="button" class="search-btn" id="searchEnter" value="" onclick="viewColleges('',1)"></td>
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
            <div class="tbl_wrap collegeInfos border-tb" style="height: 75vh;overflow-y:auto;">
                    <table class="tbl_head01 tbl_2n_color" style="margin-bottom: 0px;">
                        <colgroup>
                        </colgroup>
                        <thead style="position: sticky;top:0;z-index:2;background:rgba(227, 244, 248) !important;">
                            <tr>
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
<div id="collegePopup">
    <div class="mb20" id="collegeDiv">
        
    </div>
</div>


<script>
    let topRate = "";
    let curpage = 1;
    let areas = [];
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
                topRate = eval("(" + data + ");");
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

    let json ="";

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
                showView(json);
            }
        });
    }

    $("#text").on('keydown',function(e){
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
                texts : $("#text").val(),
            },
            async: false,
            error: function(data) {
                alert('에러가 발생하였습니다.');
                return false;
            },
            success: function(data) {
                coll = eval("(" + data + ");");
                drawPaging(val,coll['paging'].page, coll['paging'].total_page, 'viewColleges');
                $(".totalCnt").html(coll['paging'].total_count + '개');
                // $(".now-page").html(coll['paging'].page + ' of ' + coll['paging'].total_page);
                coll.data.forEach(function(row, idx){
                    let no = (coll['paging'].page - 1) * 20 + (idx + 1);
                    addCollegeRow(no,row.subIdx,row.teacher,row.student,row.areaNm,row.collegeType,row.gun,row.collegeNm,row.subjectNm,row.person,row.pSub,row.silgi);
                });
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
                <td>${college}</td>
                <td>${subject}</td>
                <td>${person}</td>
                <td></td>
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
                        <td style="border:1px solid #e4e4e4;">${json['data'][j]['subject']}</td>
                        <td style="border:1px solid #e4e4e4;"><input name="${json['data'][j]['subject']}" type="text" class="frm_input" value="${json['data'][j]['recode']}"></td>
                        <td style="border:1px solid #e4e4e4;">${json['data'][j]['score']}</td>
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
                        viewColleges($("#selStudent option:selected").data('id'),curpage);
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

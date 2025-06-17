<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

$g5['title'] = '대학정보';
include_once('./_head.php');


$cnt = sql_fetch("SELECT 
    COUNT(*) as 'cnt'
FROM g5_college gc JOIN g5_college_subject gcs on gcs.collegeIdx = gc.cIdx");

$sql_add = " 1=1 ";

if($bid){
    $sql_add .= " AND gb.idx = {$bid} ";
}

if($text){
    $sql_add .= " AND (gm.mb_name like '%{$text}%' OR replace(gm.mb_hp,'-','') like '%{$text}%' OR gm.mb_1 like '%{$text}%')";
}

$res = sql_query("select 
                    *, 
                    (SELECT COUNT(*) FROM g5_susi gs WHERE gs.sSubIdx = gcs.sIdx) as 'su', 
                    (SELECT COUNT(*) FROM g5_jungsi gj WHERE gj.jSubIdx = gcs.sIdx) as 'ju'
                from g5_college gc
                    LEFT JOIN g5_college_subject gcs on
                    gc.cIdx = gcs.collegeIdx
                where 
                    {$sql_add}
                ORDER BY gc.cName, gcs.sName");

$query_string = http_build_query(array(
    'bid' => $_GET['bid'],
    'text' => $_GET['text'],
));
?>

<!-- 등급관리 시작 { -->
<div id="smb_my" style="display: grid;grid-template-columns: 1fr;">
<div id="smb_my_list">
        <!-- 최근 주문내역 시작 { -->
        <section id="smb_my_od">
            <h2>대학 리스트<span style="font-size: small;">&nbsp;&nbsp;&nbsp; 대학 수 : <?= $cnt['cnt']?></h2>
            <div class="smb_my_more" style="cursor:pointer;">
                <!-- <a onclick="popupBranch('insert','')">등록</a> -->
            </div>
            <div style="display: grid;grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));gap: 20px;margin-top:20px;">
                <?
                    foreach($res as $k => $v){
                ?>
                    <div style="height:150px;border:2px solid #e4e4e480;border-radius:20px;box-shadow:0 5px 10px darkgray;" class="college_hov">
                        <div style="display: grid;grid-template-columns:1fr 2fr 0.5fr;height:100%;align-items:center;padding:10px 20px;gap:10px;">
                            <div>
                                <img src="<?=$v['c_url']?>" style="max-width:110px;"/>
                            </div>
                            <div style="height:100%;padding:10px;display: flex;flex-direction: column;justify-content: space-between;">
                                <div>
                                    <div style="font-weight: 900;font-size:1.4em;"><?=$v['cName']?></div>
                                    <div style="color: gray;font-size:1.1em"><?=$v['sName']?></div>
                                </div>
                                <div style="display: flex;gap:15px;align-items:center;">
                                    <?if($v['su']>0){?>
                                    <div style="width: 45%;background: #87ceeb3b;border-radius: 15px;text-align: center;font-weight: bold;color:#639daf;">
                                        수시
                                    </div>
                                    <?}?>
                                    <?if($v['ju']>0){?>
                                    <div style="width: 45%;background: #ffc0cb70;border-radius: 15px;text-align: center;font-weight: bold;color:hotpink;">
                                        정시
                                    </div>
                                    <?}?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?}?>
            </div>
        </section>
        <!-- } 최근 주문내역 끝 -->
    </div>
	
</div>

<div id="memberPopup">
    <div class="mb20" id="memberDiv">
        <div class="tbl_frm01 tbl_wrap">
            <table>
                <colgroup>
                    <col width="15%">
                    <col width="35%">
                    <col width="15%">
                    <col width="35%">
                </colgroup>
                <tbody>
                    <tr>
                        <th>아이디</th>
                        <td>
                            <input type="text" class="frm_input" id="mb_id" name="mb_id" value="" autocomplete="off" style="width: 100%;pointer-events:none;background-color:#e4e4e4;">
                        </td>
                        <th>비밀번호</th>
                        <td>
                            <button id="resetBtn">비밀번호 초기화</button>
                        </td>
                    </tr>
                    <tr>
                        <th>이름</th>
                        <td>
                            <input type="text" class="frm_input" id="mb_name" name="mb_name" value="" autocomplete="off" style="width: 100%;">
                            <input type="hidden" id="mb_no" name="mb_no">
                        </td>
                        <th>연락처</th>
                        <td>
                            <input type="text" class="frm_input" id="mb_hp" name="mb_hp" value="" autocomplete="off" style="width: 100%;">
                        </td>
                    </tr>
                    <tr class="onlyStudent">
                        <th>학교</th>
                        <td>
                            <input type="text" class="frm_input" id="mb_1" name="mb_1" value="" autocomplete="off" style="width: 100%;">
                        </td>
                        <th>학년</th>
                        <td>
                            <select class="frm_input" style="width: 100%;" id="mb_2" name="mb_2">
                                <option value="">선택하세요.</option>
                                <option value="1">1학년</option>
                                <option value="2">2학년</option>
                                <option value="3">3학년</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>성별</th>
                        <td>
                            <select class="frm_input" name="mb_sex" id="mb_sex" style="width: 100%;">
                                <option value="M">남자</option>
                                <option value="F">여자</option>
                            </select>
                        </td>
                        <th>생년월일</th>
                        <td>
                            <input type="text" name="mb_birth" id="mb_birth" required class="frm_input" maxlength="8" pattern="\d{8}" size="20" maxLength="8" placeholder="생년월일(ex.19801212)">
                        </td>
                    </tr>
                    <tr>
                        <th>소속</th>
                        <td>
                            <select class="frm_input" name="mb_signature" id="mb_signature" style="width: 100%;">
                                <option value="">선택하세요.</option>
                            <?
                                $bsql = sql_query("SELECT * FROM g5_branch WHERE branchActive = 1 ORDER BY branchName");
                                foreach($bsql as $bs => $b){
                            ?>
                                <option value="<?=$b['idx']?>"><?=$b['branchName']?></option>
                            <?}?>
                            </select>
                        </td>
                        <th>권한</th>
                        <td>
                            <select class="frm_input" name="mb_profile" id="mb_profile" style="width: 100%;">
                                <?
                                    $ggsql = sql_query("SELECT * FROM g5_cmmn_code WHERE upperCode = 'C40000000' AND useYn = 1 ORDER BY code");
                                    foreach($ggsql as $ggs => $g){?>
                                      <option value="<?=$g['code']?>"><?=$g['codeName']?></option>  
                                    <?}
                                ?>
                            </select>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <button id="closePopup">닫기</button>
    <button id="memberBtn">수정</button>
    <button id="memberDel">삭제</button>
</div>

<div id="branchPopup">
    <div class="mb20" id="branchDiv">
        <div class="tbl_frm01 tbl_wrap">
            <table>
                <colgroup>
                    <col width="15%">
                    <col width="35%">
                    <col width="15%">
                    <col width="35%">
                </colgroup>
                <tbody>
                    <tr>
                        <th>지점명</th>
                        <td>
                            <input type="text" class="frm_input" id="branchName" name="branchName" value="" autocomplete="off" style="width:100%">
                            <input type="hidden" id="branchPopIdx" name="branchPopIdx">
                            <input type="hidden" id="btype" name="btype">
                        </td>
                        <th>활성여부</th>
                        <td>
                            <select class="frm_input" name="branchActive" id="branchActive" style="width: 100%;">
                                <option value="1">활성</option>
                                <option value="0">비활성</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>담당자</th>
                        <td>
                            <input type="text" class="frm_input" id="branchManager" name="branchManager" value="" autocomplete="off" style="width: 100%;">
                        </td>
                        <th>연락처</th>
                        <td>
                            <input type="text" class="frm_input" id="branchHp" name="branchHp" value="" autocomplete="off" style="width: 100%;">
                        </td>
                    </tr>
                    <tr>
                        <th>비고</th>
                        <td colspan="3">
                            <textarea id="branchMemo" name="branchMemo"></textarea>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <button id="closePopup">닫기</button>
    <button id="branchBtn">저장</button>
</div>



<script>

document.getElementById("mb_birth").addEventListener("blur", function() {
  const val = this.value;
  if (!/^\d{8}$/.test(val)) {
    swal('','생년월일은 8자리 숫자로 입력해주세요. (예: 19981202)','warning');
    $("#mb_birth").val('');
    return;
  }

  const year = parseInt(val.slice(0, 4), 10);
  const month = parseInt(val.slice(4, 6), 10) - 1;
  const day = parseInt(val.slice(6, 8), 10);
  const date = new Date(year, month, day);

  if (
    date.getFullYear() !== year ||
    date.getMonth() !== month ||
    date.getDate() !== day
  ) {
    swal("","유효하지 않은 날짜입니다.","warning");
    $("#mb_birth").val('');
  }
});

function updateMember(no){
        $("#mb_no").val(no);
        $.ajax({
            url: "/bbs/searchMember.php",
            type: "POST",
            data: {
                mbno : no,
            },
            async: false,
            error: function(data) {
                alert('에러가 발생하였습니다.');
                return false;
            },
            success: function(data) {
                json = eval("(" + data + ");");
                $.each(json.list, function(key, state) {
                    obj = state;
                    if(obj.mb_level != 0){
                        obj.mb_level = '1';
                    }
                    $("#mb_name").val(obj.mb_name);
                    $("#mb_hp").val(obj.mb_hp);
                    $("#mb_birth").val(obj.mb_birth);
                    $("#mb_sex").val(obj.mb_sex);
                    $("#mb_profile").val(obj.mb_profile);
                    $("#mb_1").val(obj.mb_1);
                    $("#mb_2").val(obj.mb_2);
                    $("#mb_signature").val(obj.mb_signature);
                    $("#mb_level").val(obj.mb_level);
                    $("#mb_id").val(obj.mb_id);
                    setTimeout(() => {
                        if(obj.mb_profile == 'C40000001' || obj.mb_profile == 'C40000002'){
                            $(".onlyStudent").css('display','none');
                        } else if(obj.mb_profile == 'C40000003' || obj.mb_profile == 'C40000004'){
                            $(".onlyStudent").css('display','');
                        }
                    }, 0);
                });
            }
        });
        $('#popupBackground').fadeIn(); // 배경 표시
        $('#memberPopup').fadeIn(); // 팝업 표시
    }
    $("#mb_profile").on('change',function(){
        let nowS = $(this).val();
        switch(nowS){
            case 'C40000001' : case 'C40000002':
                $(".onlyStudent").css('display','none');
                break;
            case 'C40000003' : case 'C40000004':
                $(".onlyStudent").css('display','');
                break;
        }
    });

    $('#closePopup, #popupBackground').click(function() {
        $('#popupBackground').fadeOut(); // 배경 숨기기
        $('#memberPopup').fadeOut(); // 팝업 숨기기
        $('#branchPopup').fadeOut(); // 팝업 표시
        popValueNull();
    });

    function popValueNull() {
        $("#branchName").val("");
        $("#branchManager").val("");
        $("#branchHp").val("");
        $("#branchMemo").val("");
        $("#branchPopIdx").val("");
        $("#btype").val("");
        $("#mb_no").val("");
    }

    $("#memberBtn").on('click',function(){
        swal({
            title : '수정하시겠습니까?',
            text : '',
            type : "info",
            showCancelButton : true,
            confirmButtonClass : "btn-danger",
            cancelButtonText : "아니오",
            confirmButtonText : "예",
            closeOnConfirm : false,
            closeOnCancel : true
            },
            function(isConfirm){
                if(isConfirm){
                    $.ajax({
                        url: "/bbs/login_check.php",
                        type: "POST",
                        data: {
                            mb_no : $("#mb_no").val(),
                            mb_name : $("#mb_name").val(),
                            mb_hp: $("#mb_hp").val(),
                            mb_profile: $("#mb_profile").val(),
                            mb_sex: $("#mb_sex").val(),
                            mb_1: $("#mb_1").val(),
                            mb_2: $("#mb_2").val(),
                            mb_signature: $("#mb_signature").val(),
                            mb_birth: $("#mb_birth").val(),
                            mb_level : $("#mb_level").val(),
                            type : 'update',
                        },
                        async: false,
                        error: function(data) {
                            alert('에러가 발생하였습니다.');
                            return false;
                        },
                        success: function(data) {
                            if(data == 'success'){
                                swal('성공!','성공적으로 수정되었습니다.','success');
                                setTimeout(() => {
                                    swal.close();
                                    location.reload();
                                }, 1500);
                            } else {
                                console.log(data);
                            }
                        }
                    });
                }
            }
        );
    });

    $("#resetBtn").on('click',function(){
        swal({
            title : '비밀번호를 초기화 하시겠습니까?',
            text : '',
            type : "warning",
            showCancelButton : true,
            confirmButtonClass : "btn-danger",
            cancelButtonText : "아니오",
            confirmButtonText : "예",
            closeOnConfirm : false,
            closeOnCancel : true
            },
            function(isConfirm){
                if(isConfirm){
                    $.ajax({
                        url: "/bbs/login_check.php",
                        type: "POST",
                        data: {
                            mb_no : $("#mb_no").val(),
                            type : 'password',
                        },
                        async: false,
                        error: function(data) {
                            alert('에러가 발생하였습니다.');
                            return false;
                        },
                        success: function(data) {
                            if(data == 'success'){
                                swal('성공!','성공적으로 수정되었습니다.','success');
                                setTimeout(() => {
                                    swal.close();
                                    location.reload();
                                }, 1500);
                            } else {
                                console.log(data);
                            }
                        }
                    });
                }
            }
        );
    });
    function popupBranch(type,no) {
       $("#btype").val(type);
        if(type == 'update'){
            $("#idx").val(no);
            $.ajax({
                url: "/bbs/searchBranch.php",
                type: "POST",
                data: {
                    idx : no,
                },
                async: false,
                error: function(data) {
                    alert('에러가 발생하였습니다.');
                    return false;
                },
                success: function(data) {
                    json = eval("(" + data + ");");
                    $.each(json.list, function(key, state) {
                        obj = state;
                        $("#branchName").val(obj.branchName);
                        $("#branchHp").val(obj.branchHp);
                        $("#branchManager").val(obj.branchManager);
                        $("#branchMemo").val(obj.branchMemo);
                        $("#branchActive").val(obj.branchActive);
                        $('#branchPopIdx').val(obj.branchIdx);
                        // $('#selectJaje').append($('<option>', {
                        //     value: obj.idx,
                        //     text: obj.ojName
                        // }));
                    });
                    console.log(json);
                }
            });
        }

        $('#popupBackground').fadeIn(); // 배경 표시
        $('#branchPopup').fadeIn(); // 팝업 표시
    }

    function updateBranch(no){
        
        $('#popupBackground').fadeIn(); // 배경 표시
        $('#branchPopup').fadeIn(); // 팝업 표시
    }

    $("#branchBtn").on('click',function(){
        swal({
            title : '수정하시겠습니까?',
            text : '',
            type : "info",
            showCancelButton : true,
            confirmButtonClass : "btn-danger",
            cancelButtonText : "아니오",
            confirmButtonText : "예",
            closeOnConfirm : false,
            closeOnCancel : true
            },
            function(isConfirm){
                if(isConfirm){
                    $.ajax({
                        url: "/bbs/update_branch.php",
                        type: "POST",
                        data: {
                            branchName : $("#branchName").val(),
                            branchManager : $("#branchManager").val(),
                            branchHp: $("#branchHp").val(),
                            branchMemo: $("#branchMemo").val(),
                            branchActive : $("#branchActive").val(),
                            branchPopIdx : $("#branchPopIdx").val(),
                            type : $("#btype").val(),
                        },
                        async: false,
                        error: function(data) {
                            alert('에러가 발생하였습니다.');
                            return false;
                        },
                        success: function(data) {
                            if(data == 'success'){
                                swal('성공!','성공적으로 등록되었습니다.','success');
                                setTimeout(() => {
                                    swal.close();
                                    location.reload();
                                }, 1500);
                            } else {
                                console.log(data);
                            }
                        }
                    });
                }
            }
        );
    });

    $("#memberDel").on('click',function(){
        swal({
            title : '삭제하시겠습니까?',
            text : '연관된 모든 데이터는 삭제됩니다.',
            type : "warning",
            showCancelButton : true,
            confirmButtonClass : "btn-danger",
            cancelButtonText : "아니오",
            confirmButtonText : "예",
            closeOnConfirm : false,
            closeOnCancel : true
            },
            function(isConfirm){
                if(isConfirm){
                    $.ajax({
                        url: "/bbs/update_branch.php",
                        type: "POST",
                        data: {
                            branchName : $("#branchName").val(),
                            branchManager : $("#branchManager").val(),
                            branchHp: $("#branchHp").val(),
                            branchMemo: $("#branchMemo").val(),
                            branchActive : $("#branchActive").val(),
                            branchPopIdx : $("#branchPopIdx").val(),
                            type : $("#btype").val(),
                        },
                        async: false,
                        error: function(data) {
                            alert('에러가 발생하였습니다.');
                            return false;
                        },
                        success: function(data) {
                            if(data == 'success'){
                                swal('성공!','성공적으로 등록되었습니다.','success');
                                setTimeout(() => {
                                    swal.close();
                                    location.reload();
                                }, 1500);
                            } else {
                                console.log(data);
                            }
                        }
                    });
                }
            }
        );
    });


    function fsearch_submit(e) {
    }
    $("#bid").on("change",function(){
        $("#text").val('');
        $("#fsearch").submit();
    });

</script>
<!-- } 마이페이지 끝 -->

<?php
include_once("./_tail.php");
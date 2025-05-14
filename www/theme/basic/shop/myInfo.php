<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

$g5['title'] = '';
include_once('./_head.php');

$sql = " select *
            from g5_member gm
            LEFT JOIN g5_branch gb on
            gb.idx = gm.mb_signature
            LEFT JOIN g5_cmmn_code gcc on
            gcc.code = gm.mb_profile
            where gm.mb_id = '{$member['mb_id']}'
            ORDER BY gm.mb_no
            ";
$res = sql_fetch($sql);

?>
<!-- 등급관리 시작 { -->
<div id="smb_my">
    <div id="smb_my_list" style="display: grid;grid-template-columns: 1fr 2.5fr;column-gap: 20px;">
        <!-- 최근 주문내역 시작 { -->
        <section id="smb_my_od">
            <div id="wrapper_title">내 정보</div>
            <div class="tbl_wrap border-tb">
                <input type="hidden" name="o_mb_name" id="o_mb_name" value="<?=$res['mb_name']?>">
                <input type="hidden" name="o_mb_birth" id="o_mb_birth" value="<?=$res['mb_birth']?>">
                <input type="hidden" name="o_mb_hp" id="o_mb_hp" value="<?=$res['mb_hp']?>">
                <input type="hidden" name="o_mb_sex" id="o_mb_sex" value="<?=$res['mb_sex']?>">
                <input type="hidden" name="o_mb_1" id="o_mb_1" value="<?=$res['mb_1']?>">
                <input type="hidden" name="o_mb_2" id="o_mb_2" value="<?=$res['mb_2']?>">
                <table class="tbl_head01" style="margin:1px 0;">
                    <colgroup width="20%">
                    <colgroup width="30%">
                    <colgroup width="20%">
                    <colgroup width="30%">
                    <tbody>
                        <tr style="text-align: center;height:60px;">
                            <th>아이디</th>
                            <td><?= $res['mb_id'] ?></td>
                            <th>등급</th>
                            <td>
                            <?=$res['codeName']?>
                            </td>
                        </tr>
                        <tr style="text-align: center;">
                            <th>비밀번호</th>
                            <td><input type="button" onclick="changePass()" name="act_button" value="비밀번호 변경" style="cursor:pointer;height:40px;" class="btn-n active"></td>
                            <th>소속</th>
                            <td>
                                <?=$res['branchName']?>
                            </td>
                        </tr>
                        <?if($res['mb_profile'] == 'C40000003' || $res['mb_profile'] == 'C40000004'){?>
                        <tr style="text-align: center;">
                            <th>학교</th>
                            <td>
                                <input type="text" name="mb_1" style="width: 100%;" id="mb_1"  value="<?=$res['mb_1']?>" class="frm_input">
                            </td>
                            <th>학년</th>
                            <td>
                                <input type="text" name="mb_2" style="width: 100%;" id="mb_2"  value="<?=$res['mb_2']?>" class="frm_input">
                            </td>
                        </tr>
                        <?}?>
                        <tr style="text-align: center;">
                            <th>이름</th>
                            <td colspan="3"><input type="text" class="frm_input" id="mb_name" name="mb_name" value="<?=$res['mb_name']?>" autocomplete="off" style="width: 100%;"></th>
                        </tr>
                        <tr style="text-align: center;">
                            <th>생년월일</th>
                            <td colspan="3">
                                <input type="text" name="mb_birth" style="width: 100%;" id="mb_birth"  value="<?=$res['mb_birth']?>" required class="frm_input required" maxlength="8" pattern="\d{8}" size="20" maxLength="8" placeholder="생년월일(ex.19801212)">
                            </td>
                        </tr>
                        <tr style="text-align: center;">
                            <th>성별</th>
                            <td colspan="3">
                                <select require class="frm_input " name="mb_sex" id="mb_sex" style="width: 100%;">
                                    <option value="M" <?if($res['mb_sex'] == 'M') echo 'selected';?>>남자</option>
                                    <option value="F" <?if($res['mb_sex'] == 'F') echo 'selected';?>>여자</option>
                                </select>
                            </td>
                        </tr>
                        <tr style="text-align: center;">
                            <th>휴대폰번호</th>
                            <td colspan="3">
                                <input type="text" name="mb_hp" id="mb_hp" required class="frm_input required" style="width: 100%;" size="20" value="<?=$res['mb_hp']?>" maxLength="12" placeholder="휴대폰번호('-' 제외)">
                            </td>
                        </tr>
                        <tr style="text-align: center;">
                            
                        </tr>
                    </tbody>
                </table>
            </div>
            <div style="display: flex;justify-content:right;gap:10px;margin-top:10px;">
                <input type="button" id="resetInfo" value="취소" style="cursor:pointer;height:40px;width:100px;" class="btn-n btn-green">
                <input type="button" id="updateInfo" value="수정" style="cursor:pointer;height:40px;width:100px;" class="btn-n active">
            </div>
            <?if($_SESSION['mb_profile'] == 'C40000001'){?>
                <div id="wrapper_title">데이터 업데이트</div>
                <div style="margin-top: 20px;">
                    <input type="button" id="updateGradeCut" value="등급컷" style="cursor:pointer;height:40px;width: 100px;" class="btn-n active">
                </div>
            <?}?>
        </section>
        <?if($_SESSION['mb_profile'] == 'C40000004' || $_SESSION['mb_profile'] == 'C40000003'){?>
        <div id="smb_my_list" style="overflow: hidden;">
            <input type="hidden" id="showMemoMem">
            <input type="hidden" id="showMemoMonth">
            <!-- 최근 주문내역 시작 { -->
            <section id="smb_my_od" style="height: 100%;">
                <div id="wrapper_title">상담이력</div>
                <div id="memoArea" style="height: 520px;">
                    
                </div>
            </section>
            <!-- } 최근 주문내역 끝 -->
        </div>
        <?}?>
    </div>
    <?if($_SESSION['mb_profile'] == 'C40000004' || $_SESSION['mb_profile'] == 'C40000003'){?>
    <div id="smb_my_list" class="studentScore">
            <!-- 성적 정보 시작 { -->
        <section id="smb_my_od">
            
            <div class="tbl_wrap" >
                <table class="tbl_head01">
                    <tr style="text-align: center;">
                        <td>검색할 학생을 눌러주세요.</td>
                    </tr>
                </table>
            </div>
            <div class="tbl_wrap" >
                
            </div>
        </section>
        <!-- } 성적 정보 끝 -->
    </div>
    <?}?>
</div>

<script>
    $(document).ready(function(){
        document.querySelectorAll("#wrapper_title")[0].setAttribute('style','display:none');
        if('<?=$_SESSION['mb_profile']?>' == 'C40000004' || '<?=$_SESSION['mb_profile']?>' == 'C40000003'){
            viewStudent();
            viewMemberInfo();
        }
    })
const pattern = /[a-zA-Z0-9]/; // 영문자 또는 숫자
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

$("#resetInfo").on('click',function(){
    $("#mb_name").val($('#o_mb_name').val());
    $("#mb_birth").val($('#o_mb_birth').val());
    $("#mb_hp").val($('#o_mb_hp').val());
    $("#mb_sex").val($('#o_mb_sex').val());
    $("#mb_1").val($('#o_mb_1').val());
    $("#mb_2").val($('#o_mb_2').val());
});

$("#updateInfo").on('click',function(){
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
                            mb_no : '<?=$res['mb_no']?>',
                            mb_name : $("#mb_name").val(),
                            mb_hp: $("#mb_hp").val(),
                            mb_profile: '<?=$res['mb_profile']?>',
                            mb_sex: $("#mb_sex").val(),
                            mb_1: $("#mb_1").val(),
                            mb_2: $("#mb_2").val(),
                            mb_signature: '<?=$res['mb_signature']?>',
                            mb_birth: $("#mb_birth").val(),
                            mb_level : '<?=$res['mb_level']?>',
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

function changePass(){
    setTimeout(() => {
        $("#changePass").focus();
    }, 0);
    swal({
            title: '비밀번호 변경',
            text: "변경할 비밀번호를 입력하세요",
            html: true,
            text:  `
                    <div style='display:flex; align-items:center;'>
                        <input id='changePass' type='password' class='swal-input' style='width:100%;margin-bottom: 10px; display:block;' placeholder='변경할 비밀번호를 입력해 주세요.' autocomplete='off'>
                    </div>
                    `,
            showCancelButton: true,
            closeOnConfirm: false,
            confirmButtonText : '변경',
            cancelButtonText : "취소",
            allowOutsideClick: false
        }, function(isConfirm) {
            if(isConfirm){
                if($("#changePass").val() == ''){
                    swal.showInputError("입력은 필수 값 입니다.");
                    return false;
                } else{
                    
                    if(!pattern.test($("#changePass").val())){
                        swal.showInputError("문자 또는 숫자는 필수입니다.");
                        return false;
                    }
                    
                    $.ajax({
                        url: "/bbs/login_check.php",
                        type: "POST",
                        data: {
                            mb_no : '<?=$res['mb_no']?>',
                            mb_password : $("#changePass").val(),
                            type : 'chpassword',
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
            }else{
                return false;
            }
            
        });
}


$("#updateGradeCut").on('click',function(){
    swal({
        title : '<?=date('Y')?> 등급컷',
        text : '수정하시겠습니까?',
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
                swal('업데이트 중입니다.','시간이 오래걸리니 잠시만 기다려주세요.','info');
                $('.sa-button-container').css('display','none');
                $.ajax({
                    url: "/api/updateGradeCut.php",
                    type: "POST",
                    data: {
                        gradeYear: <?=date('Y')?>,
                    },
                    contentType: "application/x-www-form-urlencoded",
                    async: true,
                    error: function(xhr, status, error) {
                        console.log("에러 상태:", status, "에러 메시지:", error);
                        $('.sa-button-container').css('display','flex');
                        swal('관리자에게 문의하세요.','에러가 발생하였습니다.','error');
                        return false;
                    },
                    success: function(data) {
                        $('.sa-button-container').css('display','flex');
                        if(data == 'success'){
                            swal('성공!','성공적으로 수정되었습니다.','success');
                        } else {
                            swal('관리자에게 문의하세요.','에러가 발생하였습니다.','error');
                            console.log(data);
                        }
                    }
                });
            }
        }
    );
});

function viewStudent(){
        // let html = "<div>hi</div>";
        // $(".studentScore").html(html);
        
        $.ajax({
            url: "/bbs/searchScore.php",
            type: "POST",
            data: {
                mb_no : '<?=$member['mb_no']?>',
            },
            dataType: 'json',
            async: false,
            error: function(data) {
                alert('에러가 발생하였습니다.');
                return false;
            },
            success: function(data) {
                console.log(data);
                const count = Object.keys(data['monthList']).length;

                const getValue = (monthCode, subject, field) => {
                    return data['scoreData'][monthCode]?.data?.[subject]?.[field] ?? '-';
                };

                let html = `
                    <section id="smb_my_od">
	        <div id="wrapper_title" style="padding-top:0px;">성적 정보</div>
            <div class="tbl_wrap" >
                <table class="tbl_head01 tbl_one_color">
                    <tr style="text-align: center;">
                        <th>소속</th>
                        <td>${data['info']['branch']}</td>
                        <th>이름</th>
                        <td>${data['info']['memberName']}</td>
                        <th>학교</th>
                        <td>${data['info']['school']}</td>
                        <th>학년</th>
                        <td>${data['info']['layer']}</td>
                        <th>성별</th>
                        <td>${data['info']['gender']}</td>
                    </tr>
                </table>
            </div>
            <div class="tbl_wrap" >
                <table class="tbl_head01 tbl_2n_color">
                    <thead>
                        <th>구분</th>
                        <th colspan="5">국어</th>
                        <th colspan="5">수학</th>
                        <th colspan="2">영어</th>
                        <th colspan="5">탐구Ⅰ</th>
                        <th colspan="5">탐구Ⅱ</th>
                        <th colspan="2">한국사</th>
                        <th colspan="3">제2외국어</th>
                    </thead>
                    <tbody>
                        <tr style="text-align: center; background-color:#eeeeee69">
                            <td>구분</td>
                            <td>과목</td>
                            <td>원</td>
                            <td>표</td>
                            <td>백</td>
                            <td>등</td>
                            <td>과목</td>
                            <td>원</td>
                            <td>표</td>
                            <td>백</td>
                            <td>등</td>
                            <td>원</td>
                            <td>등</td>
                            <td>과목</td>
                            <td>원</td>
                            <td>표</td>
                            <td>백</td>
                            <td>등</td>
                            <td>과목</td>
                            <td>원</td>
                            <td>표</td>
                            <td>백</td>
                            <td>등</td>
                            <td>원</td>
                            <td>등</td>
                            <td>과목</td>
                            <td>원</td>
                            <td>등</td>
                        </tr>`;
                    for(let i = 0; i < count; i++){
                        let monthArr = Object.values(data['monthList'])[i];
                        const code = monthArr['code'];
                        
                            html +=`
                            <tr style="text-align: center;">
                                <td>${monthArr['codeName']}</td>
                                <td>${getValue(code, '국어', 'subject')}</td>
                                <td>${getValue(code, '국어', 'origin')}</td>
                                <td>${getValue(code, '국어', 'pscore')}</td>
                                <td>${getValue(code, '국어', 'sscore')}</td>
                                <td>${getValue(code, '국어', 'grade')}</td>
                                <td>${getValue(code, '수학', 'subject')}</td>
                                <td>${getValue(code, '수학', 'origin')}</td>
                                <td>${getValue(code, '수학', 'pscore')}</td>
                                <td>${getValue(code, '수학', 'sscore')}</td>
                                <td>${getValue(code, '수학', 'grade')}</td>
                                <td>${getValue(code, '영어', 'origin')}</td>
                                <td>${getValue(code, '영어', 'grade')}</td>
                                <td>${getValue(code, '탐구영역1', 'subject')}</td>
                                <td>${getValue(code, '탐구영역1', 'origin')}</td>
                                <td>${getValue(code, '탐구영역1', 'pscore')}</td>
                                <td>${getValue(code, '탐구영역1', 'sscore')}</td>
                                <td>${getValue(code, '탐구영역1', 'grade')}</td>
                                <td>${getValue(code, '탐구영역2', 'subject')}</td>
                                <td>${getValue(code, '탐구영역2', 'origin')}</td>
                                <td>${getValue(code, '탐구영역2', 'pscore')}</td>
                                <td>${getValue(code, '탐구영역2', 'sscore')}</td>
                                <td>${getValue(code, '탐구영역2', 'grade')}</td>
                                <td>${getValue(code, '한국사', 'origin')}</td>
                                <td>${getValue(code, '한국사', 'grade')}</td>
                                <td>${getValue(code, '제2외국어/한문', 'subject')}</td>
                                <td>${getValue(code, '제2외국어/한문', 'origin')}</td>
                                <td>${getValue(code, '제2외국어/한문', 'grade')}</td>
                            </tr>`;
                        
                    }
                    
                    html += `</tbody>
                </table>
            </div>
	    </section>
                `;
            $(".studentScore").html(html);
            }
        });
        // $("#student").val(id);
        // $("#fsearch").submit();
    }

    function viewMemberInfo(){
        let mbId = '<?=$member['mb_no']?>';
        $("#showMemoMem").val();
        $.ajax({
            url: "/bbs/searchMemberDatas.php",
            type: "POST",
            data: {
                mbIdx : mbId,
            },
            async: false,
            error: function(data) {
                alert('에러가 발생하였습니다.');
                return false;
            },
            success: function(data) {
                json = eval("(" + data + ");");
                console.log(json);
                showMemoView(json,$("#showMemoMonth").val());
            }
        });
    }

    function showMemoView(data,month){
        if(!month){
            month = 'C00000000';
        }
        const memoData = json.memoData;
        console.log(memoData);
        let memoMonn = [];
        let html1 = `
            <div id="memoMonth" style="margin-bottom:15px;">
                <button type="button" class="btn-n" value="C00000000" onclick="showMonthMemo(event)">등록상담</button>
                <?$buttons = sql_query("SELECT * FROM g5_cmmn_code WHERE upperCode = (SELECT code FROM g5_cmmn_code WHERE codeName = '모의고사')");
                foreach($buttons as $bt => $b){?>
                    <button type="button" class="btn-n" value="<?=$b['code']?>" onclick="showMonthMemo(event)"><?=$b['codeName']?></button>
                <?}?>
            </div>
            <div style="display:flex;flex-direction:row;gap:15px;overflow-x:scroll;height:100%;padding-bottom:10px;">
                    <div class="noview">
                        상담이력이 없습니다.
                    </div>
            `;
            
                for (const tag in memoData) {
                    const tagData = memoData[tag].data;
                    memoMonn.push(tag);
                    for (const idx in tagData) {
                        const item = tagData[idx];
                        if(tag != month){
                            html1 += `<div class="${tag}" style="display:none;flex-direction:column;background-color:white;border-radius:15px;padding:20px;gap:10px;border:1px solid #e4e4e4;">`;
                        } else{
                            html1 += `<div class="${tag}" style="display:flex;flex-direction:column;background-color:white;border-radius:15px;padding:20px;gap:10px;border:1px solid #e4e4e4;">`;
                        }
                        html1 += `
                            <input type="hidden" value="${item.gmIdx}">
                            <input type="text" readonly style="border:unset;font-size:1.4em;font-weight:bold;padding:2px 5px;" value="${item.title}">
                            <div style="height:1px;border-top:1px solid #e4e4e4;"></div>
                            <textarea readonly style="border:1px solid #e4e4e4;border-radius:10px;height:370px;resize:none;padding:10px;font-size:1em;width:400px;">${item.memo}</textarea>
                            <p style="color:#b3b3b3;font-size:0.8em;">작성자 : ${item.regName} ${item.regDate}</p>`;

                        if(item.updName){
                            html1+=`
                            <p style="color:#b3b3b3;font-size:0.8em;">수정자 : ${item.updName} ${item.updDate}</p>
                            `;
                        }

                html1+=`
                </div>
                `;
                    }
                }                
            
            html1 += `
            </div>
            `;
        $("#memoArea").html(html1);
        setTimeout(() => {
            document.querySelectorAll("#memoMonth button").forEach((el,i,arr)=>{
                if(el.value == month){
                    el.classList.add('active');
                    el.click();
                }
                if(memoMonn.includes(el.value)){
                    el.classList.add('iswrite');
                }
            });
        }, 0);
    }
    function showMonthMemo(e){
        $("#showMemoMonth").val($(e.currentTarget).val());
        document.querySelectorAll("#memoMonth button").forEach((el,i,arr)=>{
            if(el == e.currentTarget){
                el.classList.add('active');
                $(`.${el.value}`).css('display','flex');
                if($(`.${el.value}`).children().length == 0){
                    $('.noview').css('display','flex');
                } else {
                    $('.noview').css('display','none');
                }
            } else {
                el.classList.remove('active');
                $(`.${el.value}`).css('display','none');
            }
        });
    }
</script>
<!-- } 마이페이지 끝 -->

<?php
include_once("./_tail.php");
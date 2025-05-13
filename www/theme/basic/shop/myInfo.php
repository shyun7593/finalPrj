<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

$g5['title'] = '내 정보';
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
        </section>
        <section id="smb_my_od">
        </section>
        <!-- } 최근 주문내역 끝 -->
    </div>
</div>

<script>
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

</script>
<!-- } 마이페이지 끝 -->

<?php
include_once("./_tail.php");

<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/style.css">', 0);
?>
<style>
    body{
        /* background-color: #c1bdba; */
        /* background-image: url("/img/final_login_background.png");
        background-repeat: no-repeat; */
        background-image: url('/img/final_login_background.png');
        height: 100%;
    }
    #mb_login{
        width: 600px;
        padding: 40px;
    }
    .mbskin, .mb_log_cate{
        background-color: rgba(255, 255, 255, 0.99) !important;
    }
    .mbskin_box{
        background-color: unset !important;
        border: unset !important;
    }
    #login_fs{
        padding: 20px 0 !important;
    }
    

</style>
<!-- 로그인 시작 { -->
 <div style="display: grid;grid-template-columns:1.5fr 2fr;height:100vh;">
    <div></div>
    <div style="width: 100%;height:100%;">
        <div id="mb_login" class="mbskin" style="margin:20em auto;border-radius:10px;height:fit-content;">
        <img style="width: 150px;margin-bottom:30px;" src="/img/final_logo.png">

        <div class="mbskin_box" style="font-size: 1.5em;">
            <div class="mb_log_cate">
                <h2 class="cursor login-view active" style="border-right: 1px solid white;" id="loginFormView" onclick="viewLogin()"><span>로그인</span></h2>
                <h2 class="cursor login-view" id="regFormView" onclick="viewLogin()"><span>회원가입</span></h2>
            </div>
            <form name="flogin" action="<?php echo $login_action_url ?>" onsubmit="return flogin_submit(this);" method="post">
            <input type="hidden" name="url" value="<?php echo $login_url ?>">
            
            <fieldset id="login_fs">
                <legend>회원로그인</legend>
                <div style="color:black; text-align:left;margin-bottom:5px;">아이디</div>
                <label for="login_id" class="sound_only">회원아이디<strong class="sound_only"> 필수</strong></label>
                <input type="text" name="mb_id" id="login_id" required class="frm_input required" size="20" maxLength="20" placeholder="아이디">
                
                <div style="color:black; text-align:left;margin-bottom:5px;">비밀번호</div>
                <label for="login_pw" class="sound_only">비밀번호<strong class="sound_only"> 필수</strong></label>
                <input type="password" name="mb_password" id="login_pw" required class="frm_input required" size="20" maxLength="20" placeholder="비밀번호">
                <div class="mgb-10"></div>

                <div id="regForm" class="btn-view">
                    <label for="mb_name" class="sound_only">이름<strong class="sound_only"> 필수</strong></label>
                    <input type="text" name="mb_name" id="mb_name" required class="frm_input required" size="20" maxLength="8" placeholder="이름">
                    <div class="mgb-10"></div>
                    <label for="birth" class="sound_only">생년월일<strong class="sound_only"> 필수</strong></label>
                    <input type="text" name="mb_birth" id="mb_birth" required class="frm_input required" size="20" maxLength="8" placeholder="생년월일(ex.19801212)">
                    <div class="mgb-10"></div>
                    <label for="mb_hp" class="sound_only">휴대폰번호<strong class="sound_only"> 필수</strong></label>
                    <input type="text" name="mb_hp" id="mb_hp" required class="frm_input required" size="20" maxLength="12" placeholder="휴대폰번호('-' 제외)">
                    <div class="mgb-10"></div>
                    <label for="mb_sex" class="sound_only">성별<strong class="sound_only"> 필수</strong></label>
                    <select require class="frm_input " name="mb_sex" id="mb_sex">
                        <option value="M" selected>남자</option>
                        <option value="F">여자</option>
                    </select>
                </div>
                <div class="mgb-20"></div>
                <button type="button" style="background-color: #000 !important;padding:15px 0;height:auto;" onclick="doAct('login')" class="btn_submit btn-view active" id="login-btn">로그인</button>
                <button type="button" style="background-color: #000 !important;padding:15px 0;height:auto;" onclick="doAct('reg')" class="btn_submit btn-view" id="reg-btn">회원가입</button>
                
                <!-- <div id="login_info">
                    <div class="login_if_auto chk_box">
                        <input type="checkbox" name="auto_login" id="login_auto_login" class="selec_chk">
                        <label for="login_auto_login"><span></span> 자동로그인</label>  
                    </div>
                    <div class="login_if_lpl">
                        <a href="<?php echo G5_BBS_URL ?>/password_lost.php">아이디/비밀번호 찾기</a>  
                    </div>
                </div> -->
            </fieldset> 
            </form>
        </div>
    </div>
</div>
<script>
jQuery(function($){
    $("#login_auto_login").click(function(){
        if (this.checked) {
            this.checked = confirm("자동로그인을 사용하시면 다음부터 회원아이디와 비밀번호를 입력하실 필요가 없습니다.\n\n공공장소에서는 개인정보가 유출될 수 있으니 사용을 자제하여 주십시오.\n\n자동로그인을 사용하시겠습니까?");
        }
    });
});

$("#login_id, #login_pw").on('keydown',function(e){
    if(document.querySelector('.btn_submit.active').id == 'login-btn'){
        if(e.keyCode == 13){
            doAct('login');
        }
    }
});

function flogin_submit(f)
{
    if( $( document.body ).triggerHandler( 'login_sumit', [f, 'flogin'] ) !== false ){
        return true;
    }
    return false;
}

function doAct(type){
    if(type == 'reg'){
        if(
            !$("#login_id").val() ||
            !$("#login_pw").val() ||
            !$("#mb_sex").val() ||
            !$("#mb_hp").val() ||
            !$("#mb_birth").val() ||
            !$("#mb_name").val()
        ){
            swal('','모든 입력값은 필수 입니다.','error');
            setTimeout(() => {
                   swal.close();
            }, 1200);
            return false;
        }
    }
    $.ajax({
        url: "<?php echo $login_action_url ?>",
        type: "POST",
        data: {
            mb_id : $("#login_id").val(),
            mb_password : $("#login_pw").val(),
            mb_sex: $("#mb_sex").val(),
            mb_hp: $("#mb_hp").val(),
            mb_birth: $("#mb_birth").val(),
            mb_name: $("#mb_name").val(),
            type : type,
        },
        async: false,
        error: function(data) {
            alert('에러가 발생하였습니다.');
            return false;
        },
        success: function(data) {
            if(type == 'reg'){
                switch(data){
                    case 'success':
                        swal('','회원가입에 성공했습니다.','success');
                        setTimeout(() => {
                            swal.close();
                            location.reload();
                        }, 1200);
                        break;
                    case 'exist':
                        swal('','이미 존재하는 아이디입니다.','error');
                        setTimeout(() => {
                            swal.close();
                        }, 1200);
                        break;
                }
            } else {
                switch(data){
                    case 'success':
                        location.href = "/shop";        
                        break;
                    case 'exist':
                        swal('','이미 존재하는 아이디입니다.','error');
                        setTimeout(() => {
                            swal.close();
                        }, 1200);
                        break;
                    case 'wrong':
                        swal('','아이디 또는 비밀번호가 틀립니다.','error');
                        setTimeout(() => {
                            swal.close();
                        }, 1200);
                        break;
                    case 'deined':
                        swal('','차단된 아이디 입니다.','error');
                        setTimeout(() => {
                            swal.close();
                        }, 1200);
                        break;
                    case 'outed':
                        swal('','탈퇴한 아이디 입니다.','error');
                        setTimeout(() => {
                            swal.close();
                        }, 1200);
                        break;
                }
            }
        }
    });
}


function viewLogin(){
    $("#regFormView").toggleClass('active');

    $("#loginFormView").toggleClass('active');
    
    $("#regForm").toggleClass('active');

    $("#login-btn").toggleClass('active');

    $("#reg-btn").toggleClass('active');
}


</script>
<!-- } 로그인 끝 -->


<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$outlogin_skin_url.'/style.css">', 0);
?>

<!-- 로그인 후 외부로그인 시작 -->
<aside id="ol_after">
   
    <h2>나의 회원정보</h2>
    <div id="ol_after_hd" class="ol" style="height: 55px;display:flex;align-items:center;justify-content:center;">
        <div style="font-size: 18px;font-weight: 700;">메뉴</div>
        <!-- <button type="button" class="menu_close"><i class="fa fa-times" aria-hidden="true"></i><span class="sound_only">카테고리닫기</span></button> -->
        <button type="button" class="menu_close"><i class="fa fa-bars" aria-hidden="true"></i><span class="sound_only">카테고리닫기</span></button>
    </div>

</aside>

<script>
// 탈퇴의 경우 아래 코드를 연동하시면 됩니다.
function member_leave()
{
    if (confirm("정말 회원에서 탈퇴 하시겠습니까?"))
        location.href = "<?php echo G5_BBS_URL ?>/member_confirm.php?url=member_leave.php";
}
</script>
<!-- 로그인 후 외부로그인 끝 -->

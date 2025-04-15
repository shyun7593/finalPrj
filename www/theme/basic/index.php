<?php
if (!defined('_INDEX_')) define('_INDEX_', true);
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
// session_unset(); // 모든 세션변수를 언레지스터 시켜줌
// session_destroy(); // 세션해제함

Header("Location:/".G5_BBS_DIR . '/login.php?url=' . urlencode(correct_goto_url(G5_ADMIN_URL)));

if (G5_IS_MOBILE) {
    include_once(G5_THEME_MOBILE_PATH.'/index.php');
    return;
}

if(G5_COMMUNITY_USE === false) {
    include_once(G5_THEME_SHOP_PATH.'/index.php');
    return;
}

include_once(G5_THEME_PATH.'/head.php');
?>

<?php
include_once(G5_THEME_PATH.'/tail.php');
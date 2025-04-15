<?php
include_once('./_common.php');

if($_SESSION['mb_level'] == 0){
    session_unset(); // 모든 세션변수를 언레지스터 시켜줌
    session_destroy(); // 세션해제함
    alert("승인이 필요합니다.");
    goto_url(G5_URL);
}

if (G5_IS_MOBILE) {
    include_once(G5_THEME_MSHOP_PATH.'/index.php');
    return;
}

if(! defined('_INDEX_')) define('_INDEX_', TRUE);

include_once(G5_THEME_SHOP_PATH.'/shop.head.php');
?>





<?php
include_once(G5_THEME_SHOP_PATH.'/shop.tail.php');
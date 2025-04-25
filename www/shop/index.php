<?php
include_once('./_common.php');

if (G5_IS_MOBILE) {
    if($_SESSION['mb_profile'] == 'C40000004' || $_SESSION['mb_profile'] == 'C40000003'){
        require_once(G5_THEME_MSHOP_PATH.'/myscore.php');
    } else {
        require_once(G5_THEME_MSHOP_PATH.'/student.php');
    }
    return;
}

if(defined('G5_THEME_PATH')) {
    if($_SESSION['mb_profile'] == 'C40000004' || $_SESSION['mb_profile'] == 'C40000003'){
        require_once(G5_THEME_SHOP_PATH.'/myscore.php');
    } else {
        require_once(G5_THEME_SHOP_PATH.'/student.php');
    }
    return;
}

define("_INDEX_", TRUE);

include_once(G5_SHOP_PATH.'/shop.head.php');
?>


<?php
include_once(G5_SHOP_PATH.'/shop.tail.php');
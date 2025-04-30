<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

$q = isset($_GET['q']) ? clean_xss_tags($_GET['q'], 1, 1) : '';
// $nowUrl=explode('/',$_SERVER['PHP_SELF']);
// $last = $nowUrl[count($nowUrl) - 1]; // 마지막 요소 (파일명)

// $last_no_php = str_replace('.php', '', $last); // .php 제거
// if($last_no_php != 'index'){
//     $cnt = sql_fetch("SELECT COUNT(*) as 'cnt' FROM g5_menu WHERE me_code='{$_SESSION['mb_profile']}' AND me_use = 1 AND me_link = '{$last_no_php}'");
//     if($cnt['cnt'] == 0){
//         alert('접속 권한이 없습니다.','/shop');
//     }
// }

if (!isset($_SESSION['mb_noticeCnt']) || time() - ($_SESSION['mb_noticeCnt_time'] ?? 0) > 300) {
    $readCnt = sql_fetch("SELECT COUNT(*) as 'cnt' FROM g5_notice_read WHERE memIdx = '{$_SESSION['mb_no']}'");
    $_SESSION['mb_readNotice'] = $readCnt['cnt'];
    $nsql = sql_fetch("SELECT COUNT(*) as 'cnt' FROM g5_notice");;
    $_SESSION['mb_noticeCnt'] = $nsql['cnt'];
    $_SESSION['mb_noticeCnt_time'] = time();
}

if($_SESSION['mb_readNotice'] < $_SESSION['mb_noticeCnt']){
    $newNotice = "<div style='border-radius: 50%;background: red;width:8px;height:8px;'></div>&nbsp;";
} else {
    $newNotice = "";
}

$conte=explode('/',$_SERVER['PHP_SELF']);
$nowUrl = str_replace('.php', '', $conte[count($conte) - 1]);
if($nowUrl == 'index'){
    if($_SESSION['mb_profile'] == 'C40000004' || $_SESSION['mb_profile'] == 'C40000003'){
        $nowUrl = 'myscore';
    } else {
        $nowUrl = 'student';
    }
}

if($nowUrl == 'noticeView'){
    $nowUrl = 'notice';
}
if(G5_IS_MOBILE) {
    include_once(G5_THEME_MSHOP_PATH.'/shop.head.php');
    return;
}


include_once(G5_THEME_PATH.'/head.sub.php');
include_once(G5_LIB_PATH.'/outlogin.lib.php');
include_once(G5_LIB_PATH.'/poll.lib.php');
include_once(G5_LIB_PATH.'/visit.lib.php');
include_once(G5_LIB_PATH.'/connect.lib.php');
include_once(G5_LIB_PATH.'/popular.lib.php');
include_once(G5_LIB_PATH.'/latest.lib.php');

add_javascript('<script src="'.G5_JS_URL.'/owlcarousel/owl.carousel.min.js"></script>', 10);
add_stylesheet('<link rel="stylesheet" href="'.G5_JS_URL.'/owlcarousel/owl.carousel.css">', 0);
?>

<!-- 상단 시작 { -->
 <div id="new-hd">
<div id="hd">
    <h1 id="hd_h1"><?php echo $g5['title'] ?></h1>
    <div id="skip_to_container"><a href="#container">본문 바로가기</a></div>

    <?php if(defined('_INDEX_')) { // index에서만 실행
        include G5_BBS_PATH.'/newwin.inc.php'; // 팝업레이어
	} ?>
	
    <div id="hd_menu">
        <img class="hd_img" src="/img/final_logo.png">
        <div id="memberBox">
            <div id="memberInfo">
                <div style="font-weight:800;display:flex;justify-content:space-between;align-items:center;">
                    <div style="display: flex;align-items:center;gap:5px;">
                        <img src="/img/login_img.png" width="20px" height="20px" style="border-radius: 50%;padding:5px;background-color:white;">
                        <a class="myInfo" href="" style="font-size: 1.3em;color:#e1e1e1"><?=$_SESSION['mb_name'].'님'?></a>
                    </div>
                    <div>
                        <a style="background: white;border-radius: 5px;padding: 2px 5px;color: #000;" href="/bbs/logout.php">Logout</a>
                    </div>
                </div>
            </div>
        </div>
		<?php include_once(G5_THEME_SHOP_PATH.'/category.php'); // 분류 ?>
		<ul class="hd_menu" style="width: 100%;">
            <? 
                $menus= sql_query("SELECT * FROM g5_menu WHERE me_code='{$_SESSION['mb_profile']}' AND me_use = 1 ORDER BY me_order");
                foreach($menus as $mu => $m){
            ?>
                <li style="width: 100%;cursor:pointer;" class="<?if($m['me_link'] == $nowUrl) echo ' active';?>"><a style="padding:12px 0;display: flex;justify-content: center;align-items: center;" href="<?= $m['me_link'] ?>"><?if($m['me_link'] == 'notice') echo $newNotice;?><?= $m['me_name']?></a></li>
            <?}?>
        </ul>
    </div> 
    <div style="position:absolute; top:0px; right:30px;height:50px;line-height:50px;">
        <a style="font-size: 1.083em;color:white;margin-right:20px;cursor:pointer;" onclick="viewMyInfo()">내 정보</a>
        
    </div>
</div>
<?php
    $wrapper_class = array();
    if( defined('G5_IS_COMMUNITY_PAGE') && G5_IS_COMMUNITY_PAGE ){
        $wrapper_class[] = 'is_community';
    }
?>
<!-- 전체 콘텐츠 시작 { -->
<div id="wrapper" class="<?php echo implode(' ', $wrapper_class); ?>">
    <!-- #container 시작 { -->
    <div id="container">
    <div id="popupBackground"></div>
    
        <!-- .shop-content 시작 { -->
        <div class="<?php echo implode(' ', $content_class); ?>">
            <?php if ((!$bo_table || $w == 's' ) && !defined('_INDEX_')) { ?><div id="wrapper_title"><?php echo $g5['title'] ?></div><?php } ?>
            <!-- 글자크기 조정 display:none 되어 있음 시작 { -->
            <div id="text_size">
                <button class="no_text_resize" onclick="font_resize('container', 'decrease');">작게</button>
                <button class="no_text_resize" onclick="font_default('container');">기본</button>
                <button class="no_text_resize" onclick="font_resize('container', 'increase');">크게</button>
            </div>
            <!-- } 글자크기 조정 display:none 되어 있음 끝 -->
<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

function get_mshop_category($ca_id, $len)
{
    global $g5;

    $sql = " select ca_id, ca_name from {$g5['g5_shop_category_table']}
                where ca_use = '1' ";
    if($ca_id)
        $sql .= " and ca_id like '$ca_id%' ";
    $sql .= " and length(ca_id) = '$len' order by ca_order, ca_id ";

    return $sql;
}

$mshop_categories = get_shop_category_array(true);
?>

<div id="category" class="menu">
    <div class="menu_wr">
        <?php echo outlogin('theme/shop_basic'); // 외부 로그인 ?>
        <ul id="cate_tnb">
            <? 
                $menus= sql_query("SELECT * FROM g5_menu WHERE me_code='{$_SESSION['mb_profile']}' AND me_mobile_use = 1 ORDER BY me_order");
                foreach($menus as $mu => $m){
            ?>
                <li style="width: 100%;" class="<?if($m['me_link'] == $nowUrl) echo ' active';?>"><a style="padding:12px 0;" href="<?= $m['me_link'] ?>"><?= $m['me_name']?></a></li>
            <?}?>
        </ul> 
    </div>
</div>
<script>
jQuery(function ($){

    $("button.sub_ct_toggle").on("click", function() {
        var $this = $(this);
        $sub_ul = $(this).closest("li").children("ul.sub_cate");

        if($sub_ul.length > 0) {
            var txt = $this.text();

            if($sub_ul.is(":visible")) {
                txt = txt.replace(/닫기$/, "열기");
                $this
                    .removeClass("ct_cl")
                    .text(txt);
            } else {
                txt = txt.replace(/열기$/, "닫기");
                $this
                    .addClass("ct_cl")
                    .text(txt);
            }

            $sub_ul.toggle();
        }
    });
});
</script>

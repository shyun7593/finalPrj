<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

$g5['title'] = '게시판';
include('./_head.php');

$res = sql_fetch("SELECT gn.*,(SELECT mb_name FROM g5_member WHERE mb_id = gn.regId) as 'regName',(SELECT mb_name FROM g5_member WHERE mb_id = gn.updId) as 'updName' FROM g5_notice gn WHERE idx = '{$note}'");

if($res['idx']){
    $isRead = sql_fetch("SELECT COUNT(*) as 'cnt' FROM g5_notice_read WHERE noticeIdx = '{$note}' AND memIdx = '{$_SESSION['mb_no']}'");
    if($isRead['cnt'] == 0){
        sql_query("INSERT INTO g5_notice_read SET noticeIdx = '{$note}', memIdx = '{$_SESSION['mb_no']}'");
        $readCnt = sql_fetch("SELECT COUNT(*) as 'cnt' FROM g5_notice_read WHERE memIdx = '{$_SESSION['mb_no']}'");
        $_SESSION['mb_readNotice'] = $readCnt['cnt'];
    }
}

?>
<style>
    .noticeV tr:not(:last-child){
        border-bottom: 1px solid #e1e1e1;
    }
</style>
<!-- 등급관리 시작 { -->
<div id="smb_my">
    <div id="smb_my_list">
        <!-- 최근 주문내역 시작 { -->
        <section id="smb_my_od" style="margin:unset;height:1030px;">
            <div class="mb20" style="margin-bottom: 10px;">
                <div id="isfixed" style="display: none;align-items:center;gap:5px;padding:10px 0;justify-content:right;">
                    <label for="fixed">고정</label>
                    <input type="checkbox" id="fixed" name="fixed" <?if($res['isFixed'] == 1) echo 'checked';?>>
                    <label for="orderNum">순번</label>
                    <input class="orderNumb <?if($res['isFixed'] == 0) echo 'isauto';?>" type="number" id="orderNum" name="orderNum" style="width: 50px;text-align:center;padding:1px;" class="" value="<?=$res['ordered']?>">
                </div>
                <div style="border:1px solid #e1e1e1;border-radius:10px;padding:10px;margin-bottom:5px;">
                    <input type="text" name="noticeTitle" id="noticeTitle" style="height: 40px;padding:5px 10px;width:100%;border:unset;font-size:1.5em;font-weight:800;padding-bottom:10px;pointer-events:none;" value="<?=$res['title']?>">
                    <div class="writer" style="font-size: 0.9em;padding : 5px 0 0 15px;">작성자 : <?=$res['regName'] . ' ' . $res['regDate']?></div>
                    <?if($res['updName']){?>
                        <div class="writer" style="font-size: 0.9em;padding : 5px 0 0 15px;">수정자 : <?=$res['updName'] . ' ' . $res['updDate']?></div>
                    <?}?>
                </div>
                <div class="noticeHead" style="font-size: 1.2em;height:700px;border:1px solid #e1e1e1;padding:5px 10px; border-radius:10px;">
                    <div id="viewer" style="font-size: 1.1em;"></div>

                    <!-- JSON or HTML로 저장된 editor content -->
                </div>
            </div>
            <div class="normalView" style="text-align: center;">
                <?if($_SESSION['ss_mb_id'] == $res['regId'] || $_SESSION['mb_profile'] == 'C40000001'){?>
                    <button type="button" class="btn-n btn-large active" onclick="viewUpdateForm()">수정하기</button>    
                <?}?>
                <button type="button" class="btn-n btn-large" onclick="goBack()">뒤로가기</button>
            </div>
            <div class="updateView" style="display: none;text-align: center;">
                <button type="button" class="btn-n btn-large active" onclick="saveNotice('<?=$res['idx']?>')">저장하기</button>
                <button type="button" class="btn-n btn-large" onclick="resetUpdate()">취소</button>
            </div>
        </section>
        <!-- } 최근 주문내역 끝 -->
    </div>
</div>
<script>
    let viewer = toastui.Editor.factory({
        el: document.querySelector('#viewer'),
        viewer: true,
        initialValue: <?= json_encode($res['contents']) ?>
    });
    function goBack(){
        location.href = './notice?stype=<?=$stype?>&text=<?=$text?>&page=<?=$page?>';
    }

    function resetUpdate(){
        location.reload();
    }

    function viewUpdateForm(){
        $(".writer").css('display','none');
        $(".normalView").css('display','none');
        $(".updateView").css('display','');
        $("#noticeTitle").css('pointer-events','all');
        $("#noticeTitle").css('border','1px solid #e1e1e1');
        $("#isfixed").css('display','flex');
        viewer = new toastui.Editor({
            el: document.querySelector('#viewer'),
            height: '680px',
            initialEditType: 'wysiwyg',
            previewStyle: 'vertical',
            toolbarItems: [
            ['heading', 'bold', 'italic', 'strike'],
            ['hr', 'quote'],
            ['ul', 'ol', 'task'],
            ['table'], // ✅ 테이블 지원
            ['link']
            ]
        });
    }

    $("#fixed").on('click',function(){
       if($(this).is(':checked')) {
        $(".orderNumb").removeClass('isauto');
       } else {
        $(".orderNumb").addClass('isauto');
       }
    });
    function saveNotice(idx){
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
                        url: "/bbs/updateNotice.php",
                        type: "POST",
                        data: {
                            noticeIdx : idx,
                            noticeType : 'update',
                            fixed: $("#fixed").is(':checked'),
                            orderNum: $("#orderNum").val() ? $("#orderNum").val() : 0,
                            noticeTitle: $("#noticeTitle").val(),
                            contents: viewer.getHTML(),
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
    }
</script>
<!-- } 마이페이지 끝 -->

<?php
include_once("./_tail.php");
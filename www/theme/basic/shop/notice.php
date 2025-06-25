<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

$g5['title'] = '게시판';
include_once('./_head.php');

$sql_add = " AND 1=1 ";
if($text){
    switch($stype){
        case 'title':
            $sql_add .= " AND (gn.title like '%{$text}%')";
            break;
        case 'contents':
            $sql_add .= " AND (gn.contents like '%{$text}%')";
            break;
        default:
            break;
    }
}

$fcnt = sql_fetch("select COUNT(*) as 'cnt' from g5_notice gn WHERE gn.isFixed = 1");

$ncnt = sql_fetch("select 
                        COUNT(*) as 'cnt'
                    from g5_notice gn WHERE gn.isFixed = 0 {$sql_add}");

$ccc = $ncnt['cnt'];

$row = sql_fetch($sql);
$total_count = $ncnt['cnt'];
$rows = "15" - $fcnt['cnt'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$orderN = $ccc - $rows * ($page-1);

$nsql2 = " select 
gn.*,
(SELECT gm.mb_name FROM g5_member gm WHERE gm.mb_id = gn.regId) as 'regName'
from g5_notice gn WHERE gn.isFixed = 0 {$sql_add} order by gn.idx desc
limit {$from_record}, {$rows}";
$nres2 = sql_query($nsql2);
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
            <h2>게시판 리스트<span style="font-size: small;">&nbsp;&nbsp;&nbsp; 총 게시글 : <?= $ncnt['cnt']?></span></h2>
            <?if($_SESSION['mb_profile'] == 'C40000001'){?>
            <div class="smb_my_more" style="cursor:pointer;">
                <a onclick="popupNotice('insert','')">작성</a>
            </div>
            <?}?>
            <form id="fsearch" name="fsearch" onsubmit="return fsearch_submit(this);" class="local_sch01 local_sch" method="get">
                <div class="tbl_wrap border-tb" style="margin-bottom: 15px;">
                    <table class="tbl_head01">
                        <colgroup width="10%">
                        <colgroup width="10%">
                        <colgroup width="75%">
                        <colgroup width="5%">
                        <tbody>
                            <tr>
                                <td style="text-align: center;font-size:1.2em;font-weight:800;padding:10px;">검색</td>
                                <td style="padding:10px;">
                                    <select style="border:1px solid #e4e4e4;height: 45px;width:100%;padding:5px;" name="stype" id="stype" <?if($_SESSION['mb_profile'] == "C40000002") echo "class='isauto';"?>>
                                        <option value="" <?if(!$stype) echo "selected";?>>선택</option>
                                        <option value="title" <?if($stype == 'title') echo "selected";?>>제목</option>
                                        <option value="contents" <?if($stype == 'contents') echo "selected";?>>작성자</option>
                                    </select>
                                </td>
                                <td style="padding:10px;"><input type="text" name="text" id="text" placeholder="제목, 작성자" class="frm_input" style="width: 100%;" value="<?=$text?>"></td>
                                <td style="padding:10px;"><input type="submit" class="search-btn" value=""></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </form>
            <div class="tbl_wrap border-tb">
                <table class="tbl_head01">
                    <colgroup width="5%">
                    <colgroup width="75%">
                    <colgroup width="10%">
                    <colgroup width="10%">
                    <thead>
                        <th>순번</th>
                        <th>제목</th>
                        <th>작성자</th>
                        <th>작성일</th>
                    </thead>
                            <tbody class="noticeV">
                                <?
                                $nsql1 = " select 
                                    gn.*,
                                    (SELECT gm.mb_name FROM g5_member gm WHERE gm.mb_id = gn.regId) as 'regName'
                                from g5_notice gn WHERE gn.isFixed = 1 order by gn.ordered, gn.regDate";
                                $nres1 = sql_query($nsql1);
                                foreach ($nres1 as $ns1 => $n1) {
                                    $myRead = sql_fetch("SELECT COUNT(*) as 'cnt' FROM g5_notice_read WHERE memIdx = '{$_SESSION['mb_no']}' AND noticeIdx = '{$n1['idx']}'");
                                    if($myRead['cnt'] > 0){
                                        $newT = "";
                                    } else {
                                        $newT = "<div style='color: white;background: red;width: 16px;height: 18px;text-align: center;line-height: 16px;'>N</div>&nbsp;";
                                    }
                                ?>

                        <tr style="text-align: center;background:#d2d2e1;" class="fix-onaction" onclick="noticeView('<?=$n1['idx']?>')">
                            <td><img src="/img/pin-icon.png" width="20px"></td>
                            <td style="text-align:left;padding-left: 10px;display:flex;align-items:center;"><?= $newT.$n1['title'] ?></td>
                            <td><?= $n1['regName'] ?></td>
                            <td><?=substr($n1['regDate'],0,-9)?></td>
                        </tr>
                    <?}?>
                    <?
                    foreach ($nres2 as $ns2 => $n2) {
                        $myRead2 = sql_fetch("SELECT COUNT(*) as 'cnt' FROM g5_notice_read WHERE memIdx = '{$_SESSION['mb_no']}' AND noticeIdx = '{$n2['idx']}'");
                        if($myRead2['cnt'] > 0){
                            $newT2 = "";
                        } else {
                            $newT2 = "<div style='color: white;background: red;width: 16px;height: 18px;text-align: center;line-height: 16px;'>N</div>&nbsp;";
                        }
                    ?>

                        <tr style="text-align: center;" class="onaction" onclick="noticeView('<?=$n2['idx']?>')">
                            <td><?=$orderN?></td>
                            <td style="text-align:left;padding-left: 10px;display:flex;align-items:center;"><?= $newT2.$n2['title'] ?></td>
                            <td><?= $n2['regName'] ?></td>
                            <td><?=substr($n2['regDate'],0,-9)?></td>
                        </tr>
                    <?$orderN--;}?>
                    </tbody>
                </table>
            </div>
        </section>
        <!-- } 최근 주문내역 끝 -->
    </div>
</div>
<?php

	// 페이징 링크 생성
	echo get_paging(
		G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'],
		$page,
		$total_page,
		'?' . $qstr .
			'&amp;stype=' . rawurlencode($stype) .
			'&amp;page=' . rawurlencode($page) .
			'&amp;text=' . rawurlencode($text)
	);
	?>


<div id="noticePopup">
    <div class="mb20" id="noticeDiv">
        <input type="hidden" id="noticeIdx" value="">
        <input type="hidden" id="noticeType" value="">
        <h2 style="font-size:2em;font-weight:800;padding-bottom:10px;">공지사항 작성</h2>
        <div style="display: flex;align-items:center;gap:5px;padding:10px 0;justify-content:right;">
            <label for="fixed">고정</label>
            <input type="checkbox" id="fixed" name="fixed">
            <label for="orderNum">순번</label>
            <input class="orderNumb isauto" type="number" id="orderNum" name="orderNum" style="width: 50px;text-align:center;padding:1px;" class="">
        </div>
        <div class="noticeHead" style="padding:10px 10px 10px 0;border:1px solid #e4e4e4;border-bottom:0px;">
            <div style="font-size: 1.2em;text-align:center;">제목</div>
            <input type="text" name="noticeTitle" id="noticeTitle" style="height: 40px;padding:5px 10px;">
        </div>
        <div class="noticeHead" style="font-size: 1.2em;padding:10px 10px 10px 0;border:1px solid #e4e4e4;">
            <div style="text-align:center;">내용</div>
            
            <!-- 에디터 영역 -->
             <div style="width: 100%;height:700px;resize:none;">

                <div id="editor" style="width: 100%;resize:none;"></div>
                
                <!-- 숨겨진 textarea (form으로 전송용) -->
                <textarea name="contents" id="contents" style="display:none;"></textarea>
             </div>
<!-- 
            <div><textarea name="contents" id="contents" style="width: 100%;height:700px;resize:none;padding:5px 10px;"></textarea></div> -->
        </div>
    </div>
    <div>
        <button id="closePopup">닫기</button>
        <button id="noticeBtn">저장</button>
    </div>
</div>



<script>
const editor = new toastui.Editor({
    el: document.querySelector('#editor'),
    height: '700px',
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
    $('#closePopup, #popupBackground').click(function() {
        $('#popupBackground').fadeOut(); // 배경 숨기기
        $('#noticePopup').fadeOut(); // 팝업 표시
        popValueNull();
    });

    $("#fixed").on('click',function(){
       if($(this).is(':checked')) {
        $(".orderNumb").removeClass('isauto');
       } else {
        $(".orderNumb").addClass('isauto');
       }
    });

    function popValueNull() {
        $("#noticeIdx").val("");
        $("#noticeType").val("");
        $("#fixed").prop('checked',false);
        $("#orderNum").val("");
        $("#noticeTitle").val("");
        editor.setHTML('');

    }

    $("#noticeBtn").on('click',function(){
        let noticeType = $("#noticeType").val();
        let noticeIdx = $("#noticeIdx").val();
        let texts = "";
        switch(noticeType){
            case 'insert':
                texts = '작성';
                break;
            case 'update':
                texts = '수정';
                break;
        }
        if(!$("#noticeTitle").val()){
            swal('','제목은 필수 입력 입니다.','warning');
            setTimeout(() => {
                swal.close();
            }, 1200);
            return false;
        }
        if($("#fixed").is(':checked') && !$("#orderNum").val()){
            swal('','순번을 입력해주세요.','warning');
            setTimeout(() => {
                swal.close();
            }, 1200);
            return false;
        }
        swal({
            title : texts + '하시겠습니까?',
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
                            noticeIdx : noticeIdx,
                            noticeType : noticeType,
                            fixed: $("#fixed").is(':checked'),
                            orderNum: $("#orderNum").val() ? $("#orderNum").val() : 0,
                            noticeTitle: $("#noticeTitle").val(),
                            contents: editor.getHTML(),
                        },
                        async: false,
                        error: function(data) {
                            alert('에러가 발생하였습니다.');
                            return false;
                        },
                        success: function(data) {
                            if(data == 'success'){
                                swal('성공!','성공적으로 '+texts+'되었습니다.','success');
                                setTimeout(() => {
                                    swal.close();
                                    location.reload();
                                }, 1500);
                            } else {
                                
                            }
                        }
                    });
                }
            }
        );
    });

   
    function popupNotice(type,no) {
       $("#noticeType").val(type);
        $('#popupBackground').fadeIn(); // 배경 표시
        $('#noticePopup').fadeIn(); // 팝업 표시
    }

    
    function fsearch_submit(e) {
    }
    $("#stype").on("change",function(){
        $("#text").val('');
    });

    function noticeView(idx){
        location.href = './noticeView?note='+idx+'&stype=<?=$stype?>&text=<?=$text?>&page=<?=$page?>';
    }
</script>
<!-- } 마이페이지 끝 -->

<?php
include_once("./_tail.php");
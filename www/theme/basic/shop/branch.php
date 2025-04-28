<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

$g5['title'] = '학원관리';
include_once('./_head.php');

if($_SESSION['mb_profile'] == 'C40000003' || $_SESSION['mb_profile'] == 'C40000004'){
    goto_url('/index');
}

$sql_add = " 1=1 ";

if($bid){
    $sql_add .= " AND gb.idx = {$bid} ";
}

if($text){
    $sql_add .= " AND (gm.mb_name like '%{$text}%' OR replace(gm.mb_hp,'-','') like '%{$text}%' OR gm.mb_1 like '%{$text}%')";
}

$bcnt = sql_fetch("select COUNT(*) as 'cnt'
                        from g5_branch");

$mcnt = sql_fetch("select COUNT(*) as 'cnt'
                        from g5_member gm 
                        LEFT JOIN g5_branch gb on
                        gm.mb_signature = gb.idx
                        where 
                        {$sql_add}
                        AND gm.mb_id NOT IN ( '{$member['mb_id']}')
                        AND gm.mb_profile in ('C40000003','C40000004')
                        AND gm.mb_id != 'admin'");

$query_string = http_build_query(array(
    'bid' => $_GET['bid'],
    'text' => $_GET['text'],
));
?>

<!-- 마이페이지 시작 { -->
<div id="smb_my" style="display: grid;grid-template-columns:1fr 2.5fr;column-gap:20px;">
    <div id="smb_my_list">
        <!-- 최근 주문내역 시작 { -->
        <section id="smb_my_od">
            <h2>상담 내역</h2>
            <!-- <div class="smb_my_more" style="cursor:pointer;">
                <a onclick="popupBranch('insert','')">등록</a>
            </div> -->
            <!-- <div class="tbl_wrap border-tb">
                <table class="tbl_head01">
                    <colgroup width="30%">
                    <colgroup width="30%">
                    <colgroup width="30%">
                    <thead>
                        <th>지점명</th>
                        <th>담당자</th>
                        <th>관리</th>
                    </thead>
                            <tbody>
                                <?
                                $msql = " select *
                        from g5_branch";
                                $mres = sql_query($msql);
                                foreach ($mres as $ms => $m) {
                                    $act = "";
                                    switch($m['branchActive']){
                                        case '1':
                                            $act = "<span style='color:blue;'>활성</span>";
                                            break;
                                        case '0':
                                            $act = "<span style='color:red;'>비활성</span>";
                                            break;
                                    }
                                    
                                ?>

                        <tr style="text-align: center;" class="onaction" onclick="popupBranch('update','<?=$m['idx']?>')">
                            <td><?= $m['branchName'] ?></td>
                            <td><?= $m['branchManager'] ?></td>
                            <td><?=$act?></td>
                        </tr>
                    <? }
                    ?>
                    </tbody>
                </table>
            </div> -->
        </section>
        <!-- } 최근 주문내역 끝 -->
    </div>
    <div id="smb_my_list">
        <!-- 최근 주문내역 시작 { -->
        <section id="smb_my_od">
            <h2>사용자 리스트<span style="font-size: small;">&nbsp;&nbsp;&nbsp; 총 회원수 : <?= $mcnt['cnt'] ?></span></h2>
            <form id="fsearch" name="fsearch" onsubmit="return fsearch_submit(this);" class="local_sch01 local_sch" method="get">
                <div class="tbl_wrap border-tb" style="margin-bottom: 15px;">
                    <table class="tbl_head01">
                        <colgroup width="10%">
                        <colgroup width="20%">
                        <colgroup width="65%">
                        <colgroup width="5%">
                        <tbody>
                            <tr>
                                <td style="text-align: center;font-size:1.2em;font-weight:800;padding:10px;">검색</td>
                                <td style="padding:10px;">
                                    <select style="border:1px solid #e4e4e4;height: 45px;width:100%;padding:5px;" name="bid" id="bid">
                                        <option value="" <?if(!$bid) echo "selected";?>>지점선택</option>
                                        <?
                                            $bsql = sql_query("SELECT * FROM g5_branch WHERE branchActive = 1");
                                            foreach($bsql as $bs => $b){?>
                                            <option value="<?=$b['idx']?>" <?if($bid == $b['idx']) echo "selected";?>><?=$b['branchName']?></option>
                                            <?}
                                        ?>
                                    </select>
                                </td>
                                <td style="padding:10px;"><input type="text" name="text" id="text" placeholder="이름, 학교, 휴대폰 번호 등" class="frm_input" style="width: 100%;" value="<?=$text?>"></td>
                                <td style="padding:10px;"><input type="submit" class="search-btn" value=""></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </form>
            <div class="tbl_wrap border-tb">
                <table class="tbl_head01">
                    <colgroup width="12.5%">
                    <colgroup width="12.5%">
                    <colgroup width="12.5%">
                    <colgroup width="12.5%">
                    <colgroup width="12.5%">
                    <colgroup width="12.5%">
                    <colgroup width="12.5%">
                    <colgroup width="12.5%">
                    <thead>
                        <th>아이디</th>
                        <th>소속</th>
                        <th>이름</th>
                        <th>학교</th>
                        <th>학년</th>
                        <th>성별</th>
                        <th>휴대폰번호</th>
                        <th>생년월일</th>
                    </thead>
                    <tbody>
                        <?
                        $msql = " select *
                            from g5_member gm
                            LEFT JOIN g5_branch gb on
                            gb.idx = gm.mb_signature
                            where 
                            {$sql_add}
                            AND gm.mb_id NOT IN ( '{$member['mb_id']}')
                            AND gm.mb_id != 'admin'
                            AND gm.mb_profile in ('C40000003','C40000004')";
                        if($mcnt['cnt']>0){

                        
                        $mres = sql_query($msql);

                        foreach ($mres as $ms => $m) {
                            $gender = '';
                            switch ($m['mb_sex']) {
                                case 'M':
                                    $gender = '남';
                                    break;
                                case 'F':
                                    $gender = '여';
                                    break;
                            }
                        ?>
                        <tr style="text-align: center;" class="onaction" onclick="updateMember('<?=$m['mb_no']?>')">
                            
                            <td><?= $m['mb_id'] ?></td>
                            <td><?= $m['branchName'] ?></td>
                            <td><?= $m['mb_name'] ?></td>
                            <td><?= $m['mb_1'] ?></td>
                            <td><?= $m['mb_2'] ?></td>
                            <td><?= $gender ?></td>
                            <td><?= hyphen_hp_number($m['mb_hp']) ?></td>
                            <td><?= hyphen_birth_number($m['mb_birth']) ?></td>
                        </tr>
                    <?}}else{?>
                        <tr style="text-align: center;">
                            <td colspan="8">검색 결과가 없습니다.</td>
                        </tr>
                    <?}?>
                    </tbody>
                </table>
            </div>
        </section>
        <!-- } 최근 주문내역 끝 -->
    </div>
</div>



<div id="memberPopup">
    <div class="mb20" id="memberDiv">
        <div class="tbl_frm01 tbl_wrap">
            <table>
                <colgroup>
                    <col width="15%">
                    <col width="35%">
                    <col width="15%">
                    <col width="35%">
                </colgroup>
                <tbody>
                    <tr>
                        <th>아이디</th>
                        <td>
                            <input type="text" class="frm_input" id="mb_id" name="mb_id" value="" autocomplete="off" style="width: 100%;pointer-events:none;background-color:#e4e4e4;">
                        </td>
                        <th>승인여부</th>
                        <td>
                            <select class="frm_input" name="mb_level" id="mb_level" style="width: 100%;pointer-events:none;background-color:#e4e4e4;">
                                <option value="0">미승인</option>
                                <option value="1">승인</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>이름</th>
                        <td>
                            <input type="text" class="frm_input" id="mb_name" name="mb_name" value="" autocomplete="off" style="width: 100%;">
                            <input type="hidden" id="mb_no" name="mb_no">
                        </td>
                        <th>연락처</th>
                        <td>
                            <input type="text" class="frm_input" id="mb_hp" name="mb_hp" value="" autocomplete="off" style="width: 100%;">
                        </td>
                    </tr>
                    <tr>
                        <th>학교</th>
                        <td>
                            <input type="text" class="frm_input" id="mb_1" name="mb_1" value="" autocomplete="off" style="width: 100%;">
                        </td>
                        <th>학년</th>
                        <td>
                            <select class="frm_input" style="width: 100%;" id="mb_2" name="mb_2">
                                <option value="">선택하세요.</option>
                                <option value="1">1학년</option>
                                <option value="2">2학년</option>
                                <option value="3">3학년</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>성별</th>
                        <td>
                            <select class="frm_input" name="mb_sex" id="mb_sex" style="width: 100%;">
                                <option value="M">남자</option>
                                <option value="F">여자</option>
                            </select>
                        </td>
                        <th>생년월일</th>
                        <td>
                            <input type="text" name="mb_birth" id="mb_birth" required class="frm_input" maxlength="8" pattern="\d{8}" size="20" maxLength="8" placeholder="생년월일(ex.19801212)">
                        </td>
                    </tr>
                    <tr>
                        <th>소속</th>
                        <td>
                            <select class="frm_input" name="mb_signature" id="mb_signature" style="width: 100%;">
                                <option value="">선택하세요.</option>
                            <?
                                $bsql = sql_query("SELECT * FROM g5_branch WHERE branchActive = 1");
                                foreach($bsql as $bs => $b){
                            ?>
                                <option value="<?=$b['idx']?>"><?=$b['branchName']?></option>
                            <?}?>
                            </select>
                        </td>
                        <th>권한</th>
                        <td>
                            <select class="frm_input" name="mb_profile" id="mb_profile" style="width: 100%;pointer-events:none;background-color:#e4e4e4;">
                                <?
                                    $ggsql = sql_query("SELECT * FROM g5_cmmn_code WHERE upperCode = 'C40000000' ORDER BY code");
                                    foreach($ggsql as $ggs => $g){?>
                                      <option value="<?=$g['code']?>"><?=$g['codeName']?></option>  
                                    <?}
                                ?>
                            </select>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <button id="closePopup">닫기</button>
    <button id="memberBtn">수정</button>
    <button id="resetBtn">비밀번호 초기화</button>
    <?php
	// 배열을 쉼표로 구분된 문자열로 변환
	if (is_array($selectedPartners)) {
		$selectedPartners = implode(',', array_map(function ($item) {
			return "'" . trim($item) . "'";
		}, $selectedPartners));
	}

	// + 기호를 URL에서 올바르게 전달하기 위해 rawurlencode() 사용
	$selectedPartnersEncoded = rawurlencode($selectedPartners);

	// 페이징 링크 생성
	echo get_paging(
		$config['cf_write_pages'],
		$page,
		$total_page,
		'?' . $qstr .
			'&amp;bid=' . rawurlencode($bid) .
			'&amp;page=' . rawurlencode($page) .
			'&amp;text=' . rawurlencode($text)
	);?>
</div>

<script>
    function member_leave() {
        return confirm('정말 회원에서 탈퇴 하시겠습니까?')
    }

    function out_cd_check(fld, out_cd) {
        if (out_cd == 'no') {
            alert("옵션이 있는 상품입니다.\n\n상품을 클릭하여 상품페이지에서 옵션을 선택한 후 주문하십시오.");
            fld.checked = false;
            return;
        }

        if (out_cd == 'tel_inq') {
            alert("이 상품은 전화로 문의해 주십시오.\n\n장바구니에 담아 구입하실 수 없습니다.");
            fld.checked = false;
            return;
        }
    }

    function fwishlist_check(f, act) {
        var k = 0;
        var length = f.elements.length;

        for (i = 0; i < length; i++) {
            if (f.elements[i].checked) {
                k++;
            }
        }

        if (k == 0) {
            alert("상품을 하나 이상 체크 하십시오");
            return false;
        }

        if (act == "direct_buy") {
            f.sw_direct.value = 1;
        } else {
            f.sw_direct.value = 0;
        }

        return true;
    }

    function updateMember(no){
        $("#mb_no").val(no);
        $.ajax({
            url: "/bbs/searchMember.php",
            type: "POST",
            data: {
                mbno : no,
            },
            async: false,
            error: function(data) {
                alert('에러가 발생하였습니다.');
                return false;
            },
            success: function(data) {
                json = eval("(" + data + ");");
                $.each(json.list, function(key, state) {
                    obj = state;
                    if(obj.mb_level != 0){
                        obj.mb_level = 1;
                    }
                    $("#mb_name").val(obj.mb_name);
                    $("#mb_hp").val(obj.mb_hp);
                    $("#mb_birth").val(obj.mb_birth);
                    $("#mb_sex").val(obj.mb_sex);
                    $("#mb_profile").val(obj.mb_profile);
                    $("#mb_1").val(obj.mb_1);
                    $("#mb_2").val(obj.mb_2);
                    $("#mb_signature").val(obj.mb_signature);
                    $("#mb_level").val(obj.mb_level);
                    $("#mb_id").val(obj.mb_id);
                    // $('#selectJaje').append($('<option>', {
                    //     value: obj.idx,
                    //     text: obj.ojName
                    // }));
                });
                console.log(json);
            }
        });
        $('#popupBackground').fadeIn(); // 배경 표시
        $('#memberPopup').fadeIn(); // 팝업 표시
    }

    $('#closePopup, #popupBackground').click(function() {
        $('#popupBackground').fadeOut(); // 배경 숨기기
        $('#memberPopup').fadeOut(); // 팝업 숨기기
        popValueNull();
    });

    function popValueNull() {
        $("#mb_no").val("");
    }

   
    $("#memberBtn").on('click',function(){
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
                            mb_no : $("#mb_no").val(),
                            mb_name : $("#mb_name").val(),
                            mb_hp: $("#mb_hp").val(),
                            mb_profile: $("#mb_profile").val(),
                            mb_sex: $("#mb_sex").val(),
                            mb_1: $("#mb_1").val(),
                            mb_2: $("#mb_2").val(),
                            mb_signature: $("#mb_signature").val(),
                            mb_birth: $("#mb_birth").val(),
                            mb_level : $("#mb_level").val(),
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

    $("#resetBtn").on('click',function(){
        swal({
            title : '비밀번호를 초기화 하시겠습니까?',
            text : '',
            type : "warning",
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
                            mb_no : $("#mb_no").val(),
                            type : 'password',
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

    function fsearch_submit(e) {
    }

    $("#bid").on("change",function(){
        $("#text").val('');
        $("#fsearch").submit();
    });
</script>
<!-- } 마이페이지 끝 -->

<?php
include_once("./_tail.php");

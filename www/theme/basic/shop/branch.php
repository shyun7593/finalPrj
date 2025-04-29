<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

$g5['title'] = '학원관리';
include_once('./_head.php');

if($_SESSION['mb_profile'] == 'C40000003' || $_SESSION['mb_profile'] == 'C40000004'){
    goto_url('/index');
}

$sql_add = " 1=1 ";

switch($_SESSION['mb_profile']){
    case 'C40000001':
        $bid = "";
        break;
    case 'C40000002':
        $bid = $_SESSION['mb_signature'];
        break;
}

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
        <input type="hidden" id="showMemoMem">
        <input type="hidden" id="showMemoMonth">
        <!-- 최근 주문내역 시작 { -->
        <section id="smb_my_od">
            <h2>상담 내역</h2>
            <div id="memoArea">
                <div style="display:flex;flex-direction:column;background-color:white;border-radius:15px;padding:20px;gap:10px;border:1px solid #e4e4e4;align-items:center;">
                    학생을 클릭하면 상담내용이 보입니다.
                </div>
            </div>
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
                                    <select style="border:1px solid #e4e4e4;height: 45px;width:100%;padding:5px;" name="bid" id="bid" <?if($_SESSION['mb_profile'] == "C40000002") echo "class='isauto';"?>>
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
                        <tr style="text-align: center;" class="onaction memberRow" onclick="viewMemberInfo(event,'<?=$m['mb_no']?>')">
                            
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

    function fsearch_submit(e) {
    }

    $("#bid").on("change",function(){
        $("#text").val('');
        $("#fsearch").submit();
    });

    function viewMemberInfo(e,mbId){
        $("#showMemoMem").val(mbId);
        document.querySelectorAll(".memberRow").forEach((el,i,arr)=>{
            if(el == e.currentTarget){
                el.classList.add('isactive');
            } else {
                el.classList.remove('isactive');
            }
        });
        $.ajax({
            url: "/bbs/searchMemberDatas.php",
            type: "POST",
            data: {
                mbIdx : mbId,
            },
            async: false,
            error: function(data) {
                alert('에러가 발생하였습니다.');
                return false;
            },
            success: function(data) {
                json = eval("(" + data + ");");
                console.log(json);
                showMemoView(json,$("#showMemoMonth").val());
            }
        });
    }

    function showMemoView(data,month){
        if(!month){
            month = 'C00000000';
        }
        const memoData = json.memoData;
        let memoMonn = [];
        let html1 = `
            <div id="memoMonth" style="margin-bottom:15px;">
                <button type="button" class="btn-n" value="C00000000" onclick="showMonthMemo(event)">등록상담</button>
                <?$buttons = sql_query("SELECT * FROM g5_cmmn_code WHERE upperCode = (SELECT code FROM g5_cmmn_code WHERE codeName = '모의고사')");
                foreach($buttons as $bt => $b){?>
                    <button type="button" class="btn-n" value="<?=$b['code']?>" onclick="showMonthMemo(event)"><?=$b['codeName']?></button>
                <?}?>
            </div>
            <div style="display:flex;flex-direction:column;gap:15px;">`;
            html1 += `
                <h2 style="margin:unset !important;padding: 0 5px;">메모 작성</h2>
                <div style="display:flex;flex-direction:column;background-color:white;border-radius:15px;padding:20px;gap:10px;border:1px solid #e4e4e4;">
                    <input type="hidden" value="">
                    <input type="text" style="font-size:1.4em;font-weight:bold;border:1px solid #e4e4e4;padding:2px 5px;" value="" placeholder="제목">
                    <div style="height:1px;border-top:1px solid #e4e4e4;"></div>
                    <textarea placeholder="상담내용 작성" style="border:1px solid #e4e4e4;border-radius:10px;height:200px;resize:none;padding:10px;font-size:1em;"></textarea>
                    <div>
                        <button type="button" class="btn-n btn-green btn-bold btn-large" onclick="clickMemo(event,'save')">저장</button>
                    </div>
                </div>
                `;
                for (const tag in memoData) {
                    const tagData = memoData[tag].data;
                    memoMonn.push(tag);
                    for (const idx in tagData) {
                        const item = tagData[idx];
                        if(tag != month){
                            html1 += `<div class="${tag}" style="display:none;flex-direction:column;background-color:white;border-radius:15px;padding:20px;gap:10px;border:1px solid #e4e4e4;">`;
                        } else{
                            html1 += `<div class="${tag}" style="display:flex;flex-direction:column;background-color:white;border-radius:15px;padding:20px;gap:10px;border:1px solid #e4e4e4;">`;
                        }
                        html1 += `
                            <input type="hidden" value="${item.gmIdx}">
                            <input type="text" onclick="updateMemo(event)" style="border:unset;font-size:1.4em;font-weight:bold;padding:2px 5px;" value="${item.title}">
                            <div style="height:1px;border-top:1px solid #e4e4e4;"></div>
                            <textarea style="pointer-events:none;border:1px solid #e4e4e4;border-radius:10px;height:200px;resize:none;padding:10px;font-size:1em;">${item.memo}</textarea>
                            <p style="color:#b3b3b3;font-size:0.8em;">작성자 : ${item.regName} ${item.regDate}</p>`;

                        if(item.updName){
                            html1+=`
                            <p style="color:#b3b3b3;font-size:0.8em;">수정자 : ${item.updName} ${item.updDate}</p>
                            `;
                        }

                html1+=`
                    <div style="display:none;">
                        <button type="button" class="btn-n btn-green btn-bold btn-large" onclick="clickMemo(event,'update')">수정</button>
                        <button type="button" class="btn-n btn-bold btn-large" onclick="cancleMemo(event)">취소</button>
                    </div>
                </div>
                `;
                    }
                }                
            
            html1 += `
            </div>
            `;
        $("#memoArea").html(html1);
        setTimeout(() => {
            document.querySelectorAll("#memoMonth button").forEach((el,i,arr)=>{
                if(el.value == month){
                    el.classList.add('active');
                }
                if(memoMonn.includes(el.value)){
                    el.classList.add('iswrite');
                }
            });
        }, 0);
    }

    let memoCont = '';
    let memoTitle = '';
    let memoIdx = '';

    function updateMemo(e){
        $(e.currentTarget).parent().find('textarea').css('pointer-events','all');
        $(e.currentTarget).parent().find('div').eq(1).css('display','');
        
        if(memoIdx != $(e.currentTarget).parent().find('input').eq(0).val()){
            memoIdx = $(e.currentTarget).parent().find('input').eq(0).val();
            memoCont = $(e.currentTarget).parent().find('textarea').val();
            memoTitle = $(e.currentTarget).parent().find('input').eq(1).val();
        }
    }

    function cancleMemo(e){
        $(e.currentTarget).parent().css('display','none');
        $(e.currentTarget).parent().parent().find('textarea').css('pointer-events','none');
        $(e.currentTarget).parent().parent().find('textarea').val(memoCont);
        $(e.currentTarget).parent().parent().find('input').eq(1).val(memoTitle);
        memoCont = '';
        memoTitle = '';
        memoIdx = '';
    }

    function clickMemo(e,type){
        let memoMonth = $("#memoMonth > button.active").val();
        let memoIdx = $(e.currentTarget).parent().parent().find('input').eq(0).val();
        let message = '';
        let memoContent = $(e.currentTarget).parent().parent().find('textarea').val();
        let memoTitles = $(e.currentTarget).parent().parent().find('input').eq(1).val();

        if(type == 'update'){
            message = '수정';
        } else if(type == 'save'){
            message = '저장';
        }

        swal({
            title : message + '하시겠습니까?',
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
                        url: "/bbs/updateMemo.php",
                            type: "POST",
                            data: {
                                type : type,
                                memoIdx : memoIdx,
                                memoMonth : memoMonth,
                                memoTitle : memoTitles,
                                memoCont : memoContent,
                                mbIdx : $("#showMemoMem").val(),
                            },
                            async: false,
                            error: function(data) {
                                alert('에러가 발생하였습니다.');
                                return false;
                            },
                            success: function(data) {
                                json = eval("(" + data + ");");
                                
                                swal('성공',message+'되었습니다.','success');
                                setTimeout(() => {
                                    swal.close();
                                }, 1200);
                                showMemoView(json,$("#showMemoMonth").val());
                                
                            }
                    });
                }
            }
        );
        
        
    }

    function showMonthMemo(e){
        $("#showMemoMonth").val($(e.currentTarget).val());
        document.querySelectorAll("#memoMonth button").forEach((el,i,arr)=>{
            if(el == e.currentTarget){
                el.classList.add('active');
                $(`.${el.value}`).css('display','flex');
            } else {
                el.classList.remove('active');
                $(`.${el.value}`).css('display','none');
            }
        });
    }

</script>
<!-- } 마이페이지 끝 -->

<?php
include_once("./_tail.php");

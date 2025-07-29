<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

$g5['title'] = '상담관리';
include_once('./_head.php');

if($_SESSION['mb_profile'] == 'C40000003' || $_SESSION['mb_profile'] == 'C40000004'){
    goto_url('/index');
}

$sql_add = " 1=1 ";
if(!$bid){

    switch($_SESSION['mb_profile']){
        case 'C40000001':
            $bid = "";
            break;
        case 'C40000002':
            $bid = $_SESSION['mb_signature'];
            break;
    }
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
                        AND gm.mb_profile = 'C40000003'
                        AND gm.mb_id != 'admin'");

$query_string = http_build_query(array(
    'bid' => $_GET['bid'],
    'text' => $_GET['text'],
));
?>
<style>
    .memoPlace{
        padding: 10px;
        width: 100%;
        border: 1px solid #e4e4e4;
        border-radius: 15px;
    }

    #memberMemo{
        width: 100%;
        min-height: 500px;
        padding: 5px 10px;
        resize: none;
        font-size:1.2em;
        border:none !important;
    }
    .memoWrite{
        margin-top: 5px;
        color: #bdb9b9;
    }
    .memoBtn{
        margin-top:5px;
        display: flex;
        align-items: center;
        justify-content: end;
        gap:10px;
    }
    .memoBtn button{
        font-size:1.2em;
        min-width: 100px;
    }
</style>
<!-- 마이페이지 시작 { -->
<div id="smb_my" style="display:grid;grid-template-columns:3fr 1fr;column-gap:20px;">
    <div id="smb_my_list">
        <!-- 최근 주문내역 시작 { -->
        <section id="smb_my_od" style="margin-bottom:0;">
            <div style="display: flex;">
                <h2>사용자 리스트<span style="font-size: small;">&nbsp;&nbsp;&nbsp; 총 회원수 : <?= $mcnt['cnt'] ?></span></h2>
                <div style="position: absolute;right: 0;top: -5px;">
                    <input type="hidden" name="down" value="">
                    <input type="button" name="act_button" value="명단 다운로드" onclick="excelDown();" style="cursor:pointer;" class="btn-n active">
                </div>
            </div>
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
                                        <option value="" <?if(!$bid) echo "selected";?>>전체</option>
                                        <?
                                            $bsql = sql_query("SELECT * FROM g5_branch WHERE branchActive = 1 ORDER BY branchName");
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
            <div class="tbl_wrap border-tb" style="height:78vh;overflow-y:auto;">
                <table class="tbl_head01">
                    <colgroup width="*">
                    <thead class="top-Fix">
                        <th>소속</th>
                        <th>이름</th>
                        <th>학교</th>
                        <th>학년</th>
                        <th>성별</th>
                        <th>휴대폰번호</th>
                        <th>생년월일</th>
                        <th>성적</th>
                        <th>상담</th>
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
                            AND gm.mb_profile ='C40000003'";
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
                        <!-- <tr style="text-align: center;" class="onaction memberRow" onclick="viewMemberInfo(event,'<?=$m['mb_no']?>'),viewStudent('<?=$m['mb_no']?>')"> -->
                        <!-- <tr style="text-align: center;" class="onaction memberRow" onclick="viewMemo(event,'<?=$m['mb_no']?>','<?=$m['mb_name']?>')"> -->
                        <tr style="text-align: center;" class="onaction memberRow">
                            <td><?= $m['branchName'] ?></td>
                            <td><?= $m['mb_name'] ?></td>
                            <td><?= $m['mb_1'] ?></td>
                            <td><?= $m['mb_2'] ?></td>
                            <td><?= $gender ?></td>
                            <td><?= hyphen_hp_number($m['mb_hp']) ?></td>
                            <td><?= hyphen_birth_number($m['mb_birth']) ?></td>
                            <td>
                            <?
                                    $scres = sql_query("WITH RECURSIVE dateMonth AS (
                                                            SELECT code,codeName
                                                            FROM g5_cmmn_code gcc 
                                                            WHERE upperCode = 'C60000000' AND useYN = 1
                                                        )
                                                        SELECT 
                                                            d.code,
                                                            d.codeName,
                                                            (SELECT COUNT(*) FROM g5_member_score gms WHERE gms.scoreMonth = d.code AND gms.memId = '{$m['mb_id']}') as 'cnt'
                                                        FROM dateMonth d
                                                        GROUP BY d.code
                                                        ORDER BY d.code;");
                                    foreach($scres as $k => $s){
                                ?>
                                    <button type="button" style="pointer-events: none;" class="btn-n <?if($s['cnt'] > 0) echo 'iswrite';?>" value="<?=$s['code']?>"><?=$s['codeName']?></button>
                                <?}?>
                            </td>
                            <!-- <td>
                            <?
                                    $meres = sql_fetch("SELECT * FROM g5_memo gm WHERE gm.memberIdx = {$m['mb_no']}");
                                ?>
                                <button type="button" style="pointer-events: none;" class="btn-n <?if($meres['idx'] && $meres['memo']) echo 'iswrite';?>">상담내역</button>
                            </td> -->
                            <td class="memosArea">
                                <?
                                    $meres = sql_query("WITH RECURSIVE dateMonth AS (
                                                SELECT code,codeName
                                                FROM g5_cmmn_code gcc 
                                                WHERE (upperCode = 'C60000000' OR code = 'C00000000') AND useYN = 1 AND code != 'C60000004'
                                            )
                                            SELECT 
                                                *
                                            FROM dateMonth d
                                            LEFT JOIN g5_member_note gmn on
                                                gmn.tag = d.code AND mbIdx = {$m['mb_no']}
                                            GROUP BY d.code
                                            ORDER BY d.code");
                                    foreach($meres as $k => $me){
                                        if($me['codeName'] == '모의고사'){
                                            $me['codeName'] = '등록상담';
                                        }
                                ?>
                                    <button type="button" class="btn-n <?if($me['idx']) echo 'iswrite';?>"  onclick="viewMemberInfo(event,'<?=$m['mb_no']?>','<?=$me['code']?>','<?=$m['mb_name']?>')" value="<?=$me['code']?>"><?=$me['codeName']?></button>
                                <?}?>
                            </td>
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
    <div>
        <div id="smb_my_list" style="position:sticky;top:20px;">
            <input type="hidden" id="showMemoMem">
            <input type="hidden" id="showMemoMonth">
            <input type="hidden" id="memoType" value="add">
            <input type="hidden" id="memberIdx">
            <input type="hidden" id="memoIdx">
            <!-- 최근 주문내역 시작 { -->
            <section id="smb_my_od">
                <h2><span class="memberName"></span>상담 내역</h2>
                <div id="memoArea">
                    <div style="display:flex;flex-direction:column;background-color:white;border-radius:15px;padding:20px;gap:10px;border:1px solid #e4e4e4;align-items:center;">
                        학생을 클릭하면 상담내용이 보입니다.
                    </div>
                </div>
            </section>
            <!-- } 최근 주문내역 끝 -->
        </div>
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
<iframe id="excel" name="excel" style="display:none"></iframe>
<script>
	function excelDown() {
		var search = '<?= $_SERVER["QUERY_STRING"] ?>';
		if (!confirm("목록을 XLS파일로 다운로드 받으시겠습니까?")) {
			return false;
		}

		$("#excel").attr("src", "/bbs/student_xls.php?" + search);
	}
</script>

<script>
    let editor = [];
    function fsearch_submit(e) {
    }

    $("#bid").on("change",function(){
        $("#text").val('');
        $("#fsearch").submit();
    });


    function viewMemberInfo(e,mbId,month,memNm){
        document.querySelectorAll(".memberRow").forEach((el,i,arr)=>{
            if(el == e.currentTarget.parentNode.parentNode){
                el.classList.add('isactive');
            } else {
                el.classList.remove('isactive');
            }
        });
        $("#showMemoMem").val(mbId);
        $(".memberName").html(memNm + ' ');
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
                showMemoView(json,month);
                const target = document.getElementById('fsearch');
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }
        });
    }

    function showMemoView(data,month){
        if(!month){
            month = 'C00000000';
        }
        const memoData = json.memoData;
        let memoMonn = [];
        let memoText = [];
        editor = [];
        
        let html1 = `
            <div id="memoMonth" style="margin-bottom:15px;">
                <button type="button" class="btn-n" value="C00000000" onclick="showMonthMemo(event)">상담</button>
                <?$buttons = sql_query("SELECT * FROM g5_cmmn_code WHERE upperCode = (SELECT code FROM g5_cmmn_code WHERE codeName = '모의고사') AND useYN = 1 AND code != 'C60000004'");
                foreach($buttons as $bt => $b){?>
                    <button type="button" class="btn-n" value="<?=$b['code']?>" onclick="showMonthMemo(event)"><?=$b['codeName']?></button>
                <?}?>
            </div>
            <div style="display:flex;flex-direction:column;gap:15px;">`;
            
                let viewty = "";
                for (const tag in memoData) {
                    if(month == tag){
                            viewty = "flex";
                        } else {
                            viewty = "none";
                        }
                    const tagData = memoData[tag].data;
                    if(tagData.gmIdx){
                        memoText[tag] = tagData.memo;
                        memoMonn.push(tag);
                        html1 += `
                        <div class="${tag}" style="display:${viewty};flex-direction:column;background-color:white;border-radius:15px;padding:20px;gap:10px;border:1px solid #e4e4e4;">
                            <input type="hidden" value="${tagData.gmIdx}">
                            <input type="text" class="memo-title" style="pointer-events:none;border:unset;font-size:1.4em;font-weight:bold;padding:2px 5px;" value="${tagData.title}">
                            <div style="height:1px;border-top:1px solid #e4e4e4;"></div>
                            <div id="noticeHead" style="font-size: 1.2em;height:700px;max-width:550px;border:1px solid #e1e1e1;padding:5px 10px; border-radius:10px;">
                                <div class="viewer${tag}" style="font-size: 1.1em;"></div>
                                <div class="editor${tag}" style="display:none;"></div>
                            </div>
                            <p style="color:#b3b3b3;font-size:0.8em;">작성자 : ${tagData.regName} ${tagData.regDate}</p>
                        `;
                        if(tagData.updName){
                            html1+=`
                            <p style="color:#b3b3b3;font-size:0.8em;">수정자 : ${tagData.updName} ${tagData.updDate}</p>
                            `;
                        }
                        html1 += `
                        <div class="updateArea${tag}">
                            <button type="button" class="btn-n btn-green btn-bold btn-large" onclick="updateInner(event,'${tag}')">수정</button>
                        </div>
                        <div class="savedArea${tag}" style="display:none;">
                            <button type="button" class="btn-n btn-green btn-bold btn-large" onclick="clickMemo(event,'update')">저장</button>
                            <button type="button" class="btn-n btn-bold btn-large" onclick="cancleMemo(event,'${tag}')">취소</button>
                        </div>
                    </div>
                        `;
                    } else {
                        memoText[tag] = '';
                        html1 += `
                        <div class="${tag}" style="display:${viewty};flex-direction:column;background-color:white;border-radius:15px;padding:20px;gap:10px;border:1px solid #e4e4e4;">
                            <input type="hidden" value="">
                            <input type="text" style="font-size:1.4em;font-weight:bold;border:1px solid #e4e4e4;padding:2px 5px;" value="" placeholder="제목">
                            <div style="height:1px;border-top:1px solid #e4e4e4;"></div>
                            <div id="noticeHead" style="font-size: 1.2em;height:700px;max-width:550px;border:1px solid #e1e1e1;padding:5px 10px; border-radius:10px;">
                                <div class="viewer${tag}" style="display:none;"></div>
                                <div class="editor${tag}" style=""></div>
                            </div>
                            <div>
                                <button type="button" class="btn-n btn-green btn-bold btn-large" onclick="clickMemo(event,'save')">저장</button>
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
                // editor 설정 (기존 textarea 자리에 editor${tag}로 만들어야 함)
                toastui.Editor.factory({
                    el: document.querySelector(`.viewer${el.value}`),
                    viewer: true,
                    initialValue: memoText[el.value] || ''
                });

                editor[`${el.value}`] = new toastui.Editor({
                    el: document.querySelector(`.editor${el.value}`),
                    initialEditType: 'wysiwyg',
                    previewStyle: 'vertical',
                    height: '650px',
                    initialValue: memoText[el.value] || '',
                    toolbarItems: [
                        ['heading', 'bold', 'italic', 'strike'],
                        ['hr', 'quote'],
                        ['ul', 'ol', 'task'],
                        ['table'],
                        ['link']
                    ]
                });

                if(el.value == month){
                    el.classList.add('active2');
                }
                if(memoMonn.includes(el.value)){
                    el.classList.add('iswrite');
                }
            });

            document.querySelectorAll('.toastui-editor-contents p>a').forEach((el,i,arr)=>{
                el.setAttribute("target","_blank");
            });
        }, 0);
    }

    function cancleMemo(e,month){
        document.querySelectorAll('.memberRow.isactive .memosArea button').forEach((el,i,arr)=>{
            if(el.value == month){
                el.click();
            }
        })
    }

    function updateInner(e,month){
        $(`.updateArea${month}`).css('display','none');
        $(`.savedArea${month}`).css('display','');
        $(`.editor${month}`).css('display','');
        $(`.viewer${month}`).css('display','none');
        $(`.${month} .memo-title`).css('pointer-events','all');
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

    function clickMemo(e,type){
        let memoMonth = $("#memoMonth > button.active2").val();
        let memoIdx = $(e.currentTarget).parent().parent().find('input').eq(0).val();
        let message = '';
        let memoContent = editor[`${memoMonth}`].getHTML();
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
                                showMemoView(json,memoMonth);
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
                el.classList.add('active2');
                $(`.${el.value}`).css('display','flex');
            } else {
                el.classList.remove('active2');
                $(`.${el.value}`).css('display','none');
            }
        });
    }

    function viewStudent(id){        
        $.ajax({
            url: "/bbs/searchScore.php",
            type: "POST",
            data: {
                mb_no : id,
            },
            dataType: 'json',
            async: false,
            error: function(data) {
                alert('에러가 발생하였습니다.');
                return false;
            },
            success: function(data) {
                const count = Object.keys(data['monthList']).length;

                const getValue = (monthCode, subject, field) => {
                    return data['scoreData'][monthCode]?.data?.[subject]?.[field] ?? '-';
                };

                let html = `
            
	        
            <div class="tbl_wrap" >
                <table class="tbl_head01 tbl_one_color">
                    <tr style="text-align: center;">
                        <th>소속</th>
                        <td>${data['info']['branch']}</td>
                        <th>이름</th>
                        <td>${data['info']['memberName']}</td>
                        <th>학교</th>
                        <td>${data['info']['school']}</td>
                        <th>학년</th>
                        <td>${data['info']['layer']}</td>
                        <th>성별</th>
                        <td>${data['info']['gender']}</td>
                    </tr>
                </table>
            </div>
            <div class="tbl_wrap" >
                <table class="tbl_head01 tbl_2n_color">
                    <thead>
                        <th>구분</th>
                        <th colspan="5">국어</th>
                        <th colspan="5">수학</th>
                        <th colspan="2">영어</th>
                        <th colspan="5">탐구Ⅰ</th>
                        <th colspan="5">탐구Ⅱ</th>
                        <th colspan="2">한국사</th>
                        <th colspan="3">제2외국어</th>
                    </thead>
                    <tbody>
                        <tr style="text-align: center; background-color:#eeeeee69">
                            <td>구분</td>
                            <td>과목</td>
                            <td>원</td>
                            <td>표</td>
                            <td>백</td>
                            <td>등</td>
                            <td>과목</td>
                            <td>원</td>
                            <td>표</td>
                            <td>백</td>
                            <td>등</td>
                            <td>원</td>
                            <td>등</td>
                            <td>과목</td>
                            <td>원</td>
                            <td>표</td>
                            <td>백</td>
                            <td>등</td>
                            <td>과목</td>
                            <td>원</td>
                            <td>표</td>
                            <td>백</td>
                            <td>등</td>
                            <td>원</td>
                            <td>등</td>
                            <td>과목</td>
                            <td>원</td>
                            <td>등</td>
                        </tr>`;
                    for(let i = 0; i < count; i++){
                        let monthArr = Object.values(data['monthList'])[i];
                        const code = monthArr['code'];
                        
                            html +=`
                            <tr style="text-align: center;">
                                <td>${monthArr['codeName']}</td>
                                <td>${getValue(code, '국어', 'subject')}</td>
                                <td>${getValue(code, '국어', 'origin')}</td>
                                <td>${getValue(code, '국어', 'pscore')}</td>
                                <td>${getValue(code, '국어', 'sscore')}</td>
                                <td>${getValue(code, '국어', 'grade')}</td>
                                <td>${getValue(code, '수학', 'subject')}</td>
                                <td>${getValue(code, '수학', 'origin')}</td>
                                <td>${getValue(code, '수학', 'pscore')}</td>
                                <td>${getValue(code, '수학', 'sscore')}</td>
                                <td>${getValue(code, '수학', 'grade')}</td>
                                <td>${getValue(code, '영어', 'origin')}</td>
                                <td>${getValue(code, '영어', 'grade')}</td>
                                <td>${getValue(code, '탐구영역1', 'subject')}</td>
                                <td>${getValue(code, '탐구영역1', 'origin')}</td>
                                <td>${getValue(code, '탐구영역1', 'pscore')}</td>
                                <td>${getValue(code, '탐구영역1', 'sscore')}</td>
                                <td>${getValue(code, '탐구영역1', 'grade')}</td>
                                <td>${getValue(code, '탐구영역2', 'subject')}</td>
                                <td>${getValue(code, '탐구영역2', 'origin')}</td>
                                <td>${getValue(code, '탐구영역2', 'pscore')}</td>
                                <td>${getValue(code, '탐구영역2', 'sscore')}</td>
                                <td>${getValue(code, '탐구영역2', 'grade')}</td>
                                <td>${getValue(code, '한국사', 'origin')}</td>
                                <td>${getValue(code, '한국사', 'grade')}</td>
                                <td>${getValue(code, '제2외국어/한문', 'subject')}</td>
                                <td>${getValue(code, '제2외국어/한문', 'origin')}</td>
                                <td>${getValue(code, '제2외국어/한문', 'grade')}</td>
                            </tr>`;
                        
                    }
                    
                    html += `</tbody>
                </table>
            </div>
                `;
            $("#studentScore").html(html);
            }
        });
        // $("#student").val(id);
        // $("#fsearch").submit();
    }

</script>
<!-- } 마이페이지 끝 -->

<?php
include_once("./_tail.php");

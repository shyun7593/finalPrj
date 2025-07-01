<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

$g5['title'] = '학생관리';
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

$cnt = sql_fetch("select COUNT(*) as 'cnt'
                        from g5_member gm 
                        LEFT JOIN g5_branch gb on
                        gm.mb_signature = gb.idx
                        where 
                        {$sql_add}
                        AND gm.mb_id NOT IN ( '{$member['mb_id']}')
                        AND gm.mb_profile in ('C40000003','C40000004')
                        AND gm.mb_id != 'admin'");
                        

$query_string = http_build_query(array(
    'text' => $_GET['text'],
    'bid' => $_GET['bid'],
));
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/billboard.js/3.15.1/billboard.pkgd.min.js" integrity="sha512-GwtYypdyozwd45WXjO4AFMfkxFR5IT17obTr6AwMSujATru34eQLMBFrRflUEcCiphKSjkMAfzcVwsW73rYtqQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/billboard.js/3.15.1/billboard.min.css" integrity="sha512-KjxUmY9HDOVZGrvwhoVaZJuy0gJroHlsQVQQQhXqVBWkx1qZyESNtF88JQxhuOHqx5N++AB8P9CM6pzM1F6cog==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<style>
    .teacherColl{
        font-weight: 800;
        background-color: #e4e4e4;
    }
    .myColl{

    }
</style>
<!-- 마이페이지 시작 { -->
<div id="smb_my">

	<div id="smb_my_list">
	    <!-- 학생 리스트 시작 { -->
	    <section id="smb_my_od">
	        <h2>학생 리스트<span style="font-size: small;">&nbsp;&nbsp;&nbsp; 총 회원수 : <?=$cnt['cnt']?></span></h2>
	        <!-- <div class="smb_my_more">
	            <a href="./orderinquiry.php">더보기</a>
	        </div> -->
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
                                    <select style="border:1px solid #d3d3d3;height: 45px;width:100%;padding:5px;" name="bid" id="bid" <?if($_SESSION['mb_profile'] == "C40000002") echo "class='isauto';"?>>
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
                <div class="tbl_wrap border-tb" style="height: 250px;overflow-y:scroll;">
                    <table class="tbl_head01">
                        <colgroup width="5%">
                        <colgroup width="12.5%">
                        <colgroup width="12.5%">
                        <colgroup width="12.5%">
                        <colgroup width="12.5%">
                        <colgroup width="12.5%">
                        <colgroup width="12.5%">
                        <colgroup width="12.5%">
                        <thead class="top-Fix">
                            <tr>
                                <th>순번</th>
                                <th>소속</th>
                                <th>이름</th>
                                <th>학교</th>
                                <th>학년</th>
                                <th>성별</th>
                                <th>연락처</th>
                                <th>내신</th>
                            </tr>
                        </thead>
                        <tbody id="searchStudent">
                        <?
                            $msql = " select *
                            from g5_member gm
                            LEFT JOIN g5_branch gb on
                            gb.idx = gm.mb_signature
                            where 
                            {$sql_add}
                            AND gm.mb_id NOT IN ( '{$member['mb_id']}')
                            AND gm.mb_profile in ('C40000003','C40000004')
                            AND gm.mb_id != 'admin'";
                            if($cnt['cnt']>0){
                            $mres = sql_query($msql);
                            $i=1;
                            foreach($mres as $ms => $m){
                                $gender = '';
                                switch($m['mb_sex']){
                                    case 'M':
                                        $gender = '남';
                                        break;
                                    case 'F':
                                        $gender = '여';
                                        break;
                                }
                                ?>
                                
                                    <tr style="text-align: center;" class="onaction" onclick="viewStudent('<?=$m['mb_no']?>',event),viewPractice('<?=$m['mb_id']?>'),viewCollege('<?=$m['mb_id']?>')">
                                        <td><?= $i?></td>
                                        <td><?= $m['branchName']?></td>
                                        <td><?= $m['mb_name']?></td>
                                        <td><?= $m['mb_1']?></td>
                                        <td><?= $m['mb_2']?></td>
                                        <td><?= $gender?></td>
                                        <td><?= hyphen_hp_number($m['mb_hp'])?></td>
                                        <td></td>
                                    </tr>
                                    <?$i++;}
                            }else{?>
                                <tr style="text-align: center;">
                                    <td colspan="8">검색 결과가 없습니다.</td>
                                </tr>
                            <?}
                        ?>
                        </tbody>
                    </table>
                </div>
            </form>
	    </section>
	    <!-- } 학생 리스트 끝 -->
	</div>
    
    <div id="smb_my_list" class="studentScore">
	    <!-- 성적 정보 시작 { -->
	    <section id="smb_my_od">
	        <h2>성적 정보</h2>
            <div class="tbl_wrap" >
                <table class="tbl_head01">
                    <tr style="text-align: center;">
                        <td>검색할 학생을 눌러주세요.</td>
                    </tr>
                </table>
            </div>
	    </section>
	    <!-- } 성적 정보 끝 -->
	</div>
    <div id="smb_my_list" class="studentPractice">
	    <!-- 성적 정보 시작 { -->
	    <section id="smb_my_od">
	        <h2>실기 정보</h2>
            <div class="tbl_wrap" >
                <table class="tbl_head01">
                    <tr style="text-align: center;">
                        <td>검색할 학생을 눌러주세요.</td>
                    </tr>
                </table>
            </div>
	    </section>
	    <!-- } 성적 정보 끝 -->
	</div>
 
    <div id="smb_my_list" style="width: 100%;">
        <!-- 지원대학 시작 { -->
        <section id="smb_my_od">
            <h2>지원대학</h2>

            <div class="tbl_wrap border-tb">
                <table class="tbl_head01 tbl_2n_color">
                    <colgroup width="10%">
                    <colgroup width="25%">
                    <colgroup width="15%">
                    <colgroup width="25%">
                    <colgroup width="25%">
                    <thead>
                        <tr class="headd">
                            <th style="color:black;font-weight:800;letter-spacing: 1.2px;top:0;z-index:15;min-width:100px;width:100px;background:rgba(31, 119, 180,0.1);border-right:1px solid #d3d3d3;"></th>
                            <th style="color:black;font-weight:800;letter-spacing: 1.2px;top:0;z-index:15;min-width:100px;width:100px;background:rgba(31, 119, 180,0.1);border-right:1px solid #d3d3d3;">학교명</th>
                            <th style="color:black;font-weight:800;letter-spacing: 1.2px;top:0;z-index:15;min-width:100px;width:100px;background:rgba(31, 119, 180,0.1);border-right:1px solid #d3d3d3;">본인점수</th>
                            <th style="color:black;font-weight:800;letter-spacing: 1.2px;top:0;z-index:15;min-width:100px;width:100px;background:rgba(31, 119, 180,0.1);border-right:1px solid #d3d3d3;">지원가능여부</th>
                            <th style="color:black;font-weight:800;letter-spacing: 1.2px;top:0;z-index:15;min-width:100px;width:100px;background:rgba(31, 119, 180,0.1);">저장시간</th>
                        </tr>
                    </thead>
                    <tbody id="hopeCollege">
                        
                    </tbody>
                </table>
            </div>
        </section>
        <!-- } 지원대학 끝 -->
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

    function fsearch_submit(e) {
    }

    function viewStudent(id,e){
        // let html = "<div>hi</div>";
        // $(".studentScore").html(html);
        
        document.querySelectorAll("#searchStudent tr").forEach((el,i,arr)=>{
            if(e.currentTarget == el){
                el.classList.add('isactive');
            } else {
                el.classList.remove('isactive');
            }
        });
        
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
                    <section id="smb_my_od" style="margin-bottom:20px;">
	        <h2>성적 정보</h2>
            <div class="tbl_wrap border-tb" >
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
                        <th style="letter-spacing: 1.2px;background:rgba(31, 119, 180,0.1) !important;border-right:1px solid #d3d3d3;"></th>
                        <th style="color:black;letter-spacing: 1.2px;background:rgba(31, 119, 180,0.1) !important;border-right:1px solid #d3d3d3;" colspan="5">국어</th>
                        <th style="color:black;letter-spacing: 1.2px;background:rgba(31, 119, 180,0.1) !important;border-right:1px solid #d3d3d3;" colspan="5">수학</th>
                        <th style="color:black;letter-spacing: 1.2px;background:rgba(31, 119, 180,0.1) !important;border-right:1px solid #d3d3d3;" colspan="2">영어</th>
                        <th style="color:black;letter-spacing: 1.2px;background:rgba(31, 119, 180,0.1) !important;border-right:1px solid #d3d3d3;" colspan="5">탐구Ⅰ</th>
                        <th style="color:black;letter-spacing: 1.2px;background:rgba(31, 119, 180,0.1) !important;border-right:1px solid #d3d3d3;" colspan="5">탐구Ⅱ</th>
                        <th style="color:black;letter-spacing: 1.2px;background:rgba(31, 119, 180,0.1) !important;border-right:1px solid #d3d3d3;" colspan="2">한국사</th>
                        <th style="color:black;letter-spacing: 1.2px;background:rgba(31, 119, 180,0.1) !important;" colspan="3">제2외국어</th>
                    </thead>
                    <tbody>
                        <tr style="text-align: center;background-color: rgba(31, 119, 180,0.1);font-weight: bold;">
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
	    </section>
                `;
            $(".studentScore").html(html);
            }
        });
        // $("#student").val(id);
        // $("#fsearch").submit();
    }

    function viewPractice(idx){
        $.ajax({
            url: "/bbs/searchRecode.php",
            type: "POST",
            data: {
                memberIdx: idx,
            },
            async: false,
            error: function(data) {
                alert('에러가 발생하였습니다.');
                return false;
            },
            success: function(data) {
                json = eval("(" + data + ");");
                if(!Array.isArray(json)){
                    let html = `
                    <section id="smb_my_od" style="margin-bottom:20px;">
	                <h2>실기 정보</h2>
                    <div style="display:grid;grid-template-columns:1fr 0.5fr 0.5fr;min-height:300px;">
                        <div class="tbl_wrap border-tb" style="border-bottom:unset;width:50vw;">
                            <table class="tbl_head01 tbl_2n_color" style="width: auto;">
                                <thead>
                                    <tr class="headd">
                                        <th style="color:black;font-weight:800;letter-spacing: 1.2px;top:0;z-index:15;min-width:100px;width:100px;background:rgba(31, 119, 180,0.1);border-right:1px solid #d3d3d3;" rowspan="2">날짜</th>
                                        <th style="color:black;font-weight:800;letter-spacing: 1.2px;top:0;z-index:15;min-width:50px;width:50px;background:rgba(31, 119, 180,0.1);border-right:1px solid #d3d3d3;" rowspan="2">순위</th>
                                        <th style="color:black;font-weight:800;letter-spacing: 1.2px;position:sticky;top:0;z-index:13;min-width:120px;background:rgba(31, 119, 180,0.1);border-right:1px solid #d3d3d3;" class='core' colspan="2">배근력</th>
                                        <th style="color:black;font-weight:800;letter-spacing: 1.2px;position:sticky;top:0;z-index:13;min-width:120px;background:rgba(31, 119, 180,0.1);border-right:1px solid #d3d3d3;" class='m10m' colspan="2">10m왕복</th>
                                        <th style="color:black;font-weight:800;letter-spacing: 1.2px;position:sticky;top:0;z-index:13;min-width:120px;background:rgba(31, 119, 180,0.1);border-right:1px solid #d3d3d3;" class='medicine' colspan="2">메디신</th>
                                        <th style="color:black;font-weight:800;letter-spacing: 1.2px;position:sticky;top:0;z-index:13;min-width:120px;background:rgba(31, 119, 180,0.1);border-right:1px solid #d3d3d3;" class='leftGul' colspan="2">좌전굴</th>
                                        <th style="color:black;font-weight:800;letter-spacing: 1.2px;position:sticky;top:0;z-index:13;min-width:120px;background:rgba(31, 119, 180,0.1);border-right:1px solid #d3d3d3;" class='stand' colspan="2">제멀</th>
                                        <th style="color:black;font-weight:800;letter-spacing: 1.2px;position:sticky;top:0;z-index:13;min-width:120px;background:rgba(31, 119, 180,0.1);border-right:1px solid #d3d3d3;" class='m20mBu' colspan="2">20m부저</th>
                                        <th style="color:black;font-weight:800;letter-spacing: 1.2px;position:sticky;top:0;z-index:13;min-width:120px;background:rgba(31, 119, 180,0.1);border-right:1px solid #d3d3d3;" class='situp' colspan="2">윗몸</th>
                                        <th style="color:black;font-weight:800;letter-spacing: 1.2px;position:sticky;top:0;z-index:13;min-width:120px;background:rgba(31, 119, 180,0.1);border-right:1px solid #d3d3d3;" class='sergent' colspan="2">서전트</th>
                                        <th style="color:black;font-weight:800;letter-spacing: 1.2px;position:sticky;top:0;z-index:13;min-width:60px;background:rgba(31, 119, 180,0.1);border-right:1px solid #d3d3d3;" rowspan="2">총점</th>
                                        <th style="color:black;font-weight:800;letter-spacing: 1.2px;position:sticky;top:0;z-index:13;min-width:60px;background:rgba(31, 119, 180,0.1);border-right:1px solid #d3d3d3;" rowspan="2">평균</th>
                                    </tr>
                                <tr class="sub-header">
                                    <th style="color:black;font-weight:800;letter-spacing: 1.2px;position:sticky;top:45px;min-width:60px;background:rgba(31, 119, 180,0.1);border-right:1px solid #d3d3d3" class='core'>기록</th>
                                    <th style="color:black;font-weight:800;letter-spacing: 1.2px;position:sticky;top:45px;min-width:60px;background:rgba(31, 119, 180,0.1);border-right:1px solid #d3d3d3" class='core'>점수</th>
                                    <th style="color:black;font-weight:800;letter-spacing: 1.2px;position:sticky;top:45px;min-width:60px;background:rgba(31, 119, 180,0.1);border-right:1px solid #d3d3d3" class='m10m'>기록</th>
                                    <th style="color:black;font-weight:800;letter-spacing: 1.2px;position:sticky;top:45px;min-width:60px;background:rgba(31, 119, 180,0.1);border-right:1px solid #d3d3d3" class='m10m'>점수</th>
                                    <th style="color:black;font-weight:800;letter-spacing: 1.2px;position:sticky;top:45px;min-width:60px;background:rgba(31, 119, 180,0.1);border-right:1px solid #d3d3d3" class='medicine'>기록</th>
                                    <th style="color:black;font-weight:800;letter-spacing: 1.2px;position:sticky;top:45px;min-width:60px;background:rgba(31, 119, 180,0.1);border-right:1px solid #d3d3d3" class='medicine'>점수</th>
                                    <th style="color:black;font-weight:800;letter-spacing: 1.2px;position:sticky;top:45px;min-width:60px;background:rgba(31, 119, 180,0.1);border-right:1px solid #d3d3d3" class='leftGul'>기록</th>
                                    <th style="color:black;font-weight:800;letter-spacing: 1.2px;position:sticky;top:45px;min-width:60px;background:rgba(31, 119, 180,0.1);border-right:1px solid #d3d3d3" class='leftGul'>점수</th>
                                    <th style="color:black;font-weight:800;letter-spacing: 1.2px;position:sticky;top:45px;min-width:60px;background:rgba(31, 119, 180,0.1);border-right:1px solid #d3d3d3" class='stand'>기록</th>
                                    <th style="color:black;font-weight:800;letter-spacing: 1.2px;position:sticky;top:45px;min-width:60px;background:rgba(31, 119, 180,0.1);border-right:1px solid #d3d3d3" class='stand'>점수</th>
                                    <th style="color:black;font-weight:800;letter-spacing: 1.2px;position:sticky;top:45px;min-width:60px;background:rgba(31, 119, 180,0.1);border-right:1px solid #d3d3d3" class='m20mBu'>기록</th>
                                    <th style="color:black;font-weight:800;letter-spacing: 1.2px;position:sticky;top:45px;min-width:60px;background:rgba(31, 119, 180,0.1);border-right:1px solid #d3d3d3" class='m20mBu'>점수</th>
                                    <th style="color:black;font-weight:800;letter-spacing: 1.2px;position:sticky;top:45px;min-width:60px;background:rgba(31, 119, 180,0.1);border-right:1px solid #d3d3d3" class='situp'>기록</th>
                                    <th style="color:black;font-weight:800;letter-spacing: 1.2px;position:sticky;top:45px;min-width:60px;background:rgba(31, 119, 180,0.1);border-right:1px solid #d3d3d3" class='situp'>점수</th>
                                    <th style="color:black;font-weight:800;letter-spacing: 1.2px;position:sticky;top:45px;min-width:60px;background:rgba(31, 119, 180,0.1);border-right:1px solid #d3d3d3" class='sergent'>기록</th>
                                    <th style="color:black;font-weight:800;letter-spacing: 1.2px;position:sticky;top:45px;min-width:60px;background:rgba(31, 119, 180,0.1);border-right:1px solid #d3d3d3" class='sergent'>점수</th>
                                </tr>
                            </thead>
                                <tbody>`;
                            let bae = 0;
                            let wangbok = 0;
                            let medicine = 0;
                            let leftfront = 0;
                            let stand = 0;
                            let bujue = 0;
                            let situp = 0;
                            let surgent = 0;
                            let dCnt = 0;
                            for (const tag in json['data']) {
                                json['data'][tag];
                                dCnt++;
                                html += `
                                    <tr class="connt">
                                        <td style="max-width:100px;text-align:center;">${json['data'][tag]['date']}</td>
                                        <td style="max-width:50px;text-align:center;">${json['data'][tag]['sRank']}</td>
                                        <td style="width:150px;text-align:center;" class="core core_Rank">${json['data'][tag]['core_Rank'] ? json['data'][tag]['core_Rank'] : '-'}</td>
                                        <td style="width:150px;text-align:center;" class="core core_score">${json['data'][tag]['core_score'] ? json['data'][tag]['core_score'] : '-'}</td>
                                        <td style="width:150px;text-align:center;" class="m10m m10m_Rank">${json['data'][tag]['10m_Rank'] ? json['data'][tag]['10m_Rank'] : '-'}</td>
                                        <td style="width:150px;text-align:center;" class="m10m m10m_score">${json['data'][tag]['10m_score'] ? json['data'][tag]['10m_score'] : '-'}</td>
                                        <td style="width:150px;text-align:center;" class="medicine medicine_Rank">${json['data'][tag]['medicine_Rank'] ? json['data'][tag]['medicine_Rank'] : '-'}</td>
                                        <td style="width:150px;text-align:center;" class="medicine medicine_score">${json['data'][tag]['medicine_score'] ? json['data'][tag]['medicine_score'] : '-'}</td>
                                        <td style="width:150px;text-align:center;" class="leftGul leftGul_Rank">${json['data'][tag]['left_Rank'] ? json['data'][tag]['left_Rank'] : '-'}</td>
                                        <td style="width:150px;text-align:center;" class="leftGul leftGul_score">${json['data'][tag]['left_score'] ? json['data'][tag]['left_score'] : '-'}</td>
                                        <td style="width:150px;text-align:center;" class="stand stand_Rank">${json['data'][tag]['stand_Rank'] ? json['data'][tag]['stand_Rank'] : '-'}</td>
                                        <td style="width:150px;text-align:center;" class="stand stand_score">${json['data'][tag]['stand_score'] ? json['data'][tag]['stand_score'] : '-'}</td>
                                        <td style="width:150px;text-align:center;" class="m20mBu m20mBu_Rank">${json['data'][tag]['20mBu_Rank'] ? json['data'][tag]['20mBu_Rank'] : '-'}</td>
                                        <td style="width:150px;text-align:center;" class="m20mBu m20mBu_score">${json['data'][tag]['20mBu_score'] ? json['data'][tag]['20mBu_score'] : '-'}</td>
                                        <td style="width:150px;text-align:center;" class="situp situp_Rank">${json['data'][tag]['situp_Rank'] ? json['data'][tag]['situp_Rank'] : '-'}</td>
                                        <td style="width:150px;text-align:center;" class="situp situp_score">${json['data'][tag]['situp_score'] ? json['data'][tag]['situp_score'] : '-'}</td>
                                        <td style="width:150px;text-align:center;" class="sergent sergent_Rank">${json['data'][tag]['surgent_Rank'] ? json['data'][tag]['surgent_Rank'] : '-'}</td>
                                        <td style="width:150px;text-align:center;" class="sergent sergent_score">${json['data'][tag]['surgent_score'] ? json['data'][tag]['surgent_score'] : '-'}</td>
                                        <td style="width:150px;text-align:center;" class="totals">${json['data'][tag]['total_Rank'] ? json['data'][tag]['total_Rank'] : '-'}</td>
                                        <td style="width:150px;text-align:center;" class="avg">${json['data'][tag]['total_Rev'] ? json['data'][tag]['total_Rev'] : '-'}</td>
                                    </tr>
                                `;
                                bae += Math.round(json['data'][tag]['core_score'],1);
                                wangbok += Math.round(json['data'][tag]['10m_score'],1);
                                medicine += Math.round(json['data'][tag]['medicine_score'],1);
                                leftfront += Math.round(json['data'][tag]['left_score'],1);
                                stand += Math.round(json['data'][tag]['stand_score'],1);
                                bujue += Math.round(json['data'][tag]['20mBu_score'],1);
                                situp += Math.round(json['data'][tag]['situp_score'],1);
                                surgent += Math.round(json['data'][tag]['surgent_score'],1);
                            }
                                        ``;

                            html += `</tbody>
                            </table>
                        </div>
                        <div id="radarChart"></div>
                        <div id="radarChart2"></div>
                    </div>
                    </section>`
                    $(".studentPractice").html(html);
                    var chart = bb.generate({
                    data: {
                        x: "x",
                        columns: [
                        ["x", "배근력", "10m왕복", "메디신", "좌전굴", "제멀","20m부저","윗몸","서전트"],
                        ["점수",
                         Math.round(bae/dCnt,1), 
                         Math.round(wangbok/dCnt,1), 
                         Math.round(medicine/dCnt,1), 
                         Math.round(leftfront/dCnt,1), 
                         Math.round(stand/dCnt,1), 
                         Math.round(bujue/dCnt,1), 
                         Math.round(situp/dCnt,1), 
                         Math.round(surgent/dCnt,1)]
                        ],
                        type: "radar", // for ESM specify as: radar()
                        labels: false
                    },
                    radar: {
                        axis: {
                        max: 100
                        },
                        level: {
                        depth: 4
                        },
                        direction: {
                        clockwise: true
                        }
                    },
                    bindto: "#radarChart"
                    });
                } else {
                    html = `
                    <section id="smb_my_od">
                    <h2>실기 정보</h2>
                    <div class="tbl_wrap" >
                        <table class="tbl_head01">
                            <tr style="text-align: center;">
                                <td>실기 정보가 없습니다.</td>
                            </tr>
                        </table>
                    </div>
                </section>
                    `
                    $(".studentPractice").html(html);
                }
            }
        });

    }
    
    function viewCollege(id){
        $.ajax({
            url: "/bbs/searchHopeCollege.php",
            type: "POST",
            data: {
                memberId: id,
            },
            async: false,
            error: function(data) {
                alert('에러가 발생하였습니다.');
                return false;
            },
            success: function(data) {
                json = eval("(" + data + ");");
                console.log(json);
                if(!Array.isArray(json)){
                    let html = ``;
                    for (const tag in json['data']) {
                        let cls = "";
                        let recommend = "";
                        if(json['data'][tag]['memId'] == json['data'][tag]['regId']){
                            cls = " class='myColl'";
                            recommend = "";
                        } else {
                            cls = " class='teacherColl'";
                            recommend = "선생님 추천";
                        }
                        html += `
                            <tr${cls}>
                                <td style="text-align:center;">${recommend}</td>
                                <td style="text-align:center;">[${json['data'][tag]['gun']}] [${json['data'][tag]['area']}] ${json['data'][tag]['cName']} ${json['data'][tag]['sName']}</td>
                                <td style="text-align:center;"></td>
                                <td style="text-align:center;"></td>
                                <td style="text-align:center;">${json['data'][tag]['regDate']}</td>
                            </tr>
                        `;
                    }
                   
                    $("#hopeCollege").html(html);
                } else {
                    html = `
                    <section id="smb_my_od">
                    <h2>실기 정보</h2>
                    <div class="tbl_wrap" >
                        <table class="tbl_head01">
                            <tr style="text-align: center;">
                                <td>실기 정보가 없습니다.</td>
                            </tr>
                        </table>
                    </div>
                </section>
                    `
                    $("#hopeCollege").html(html);
                }
            }
        });

    }


    $("#bid").on("change",function(){
        $("#text").val('');
        $("#fsearch").submit();
    });
</script>
<!-- } 마이페이지 끝 -->

<?php
include_once("./_tail.php");
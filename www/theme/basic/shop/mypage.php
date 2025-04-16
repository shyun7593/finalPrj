<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

$g5['title'] = '마이페이지';
include_once('./_head.php');

if(!$month){
    $month = '3';
}

$bcnt = sql_fetch("select COUNT(*) as 'cnt'
                        from g5_branch");

$mcnt = sql_fetch("select COUNT(*) as 'cnt'
                        from g5_member where mb_id NOT IN ( '{$member['mb_id']}')
                        AND mb_id != 'admin'");
?>

<!-- 마이페이지 시작 { -->
<div id="smb_my">

    <div id="smb_my_list">
        <!-- 성적입력 시작 { -->
        <section id="smb_my_od">
            <div style="display: flex; align-items:center;gap:30px;margin-bottom:10px;">
                <h2 style="margin: unset;">성적 입력</h2>
                <div style="display: flex;gap:10px;">
                    <button class="btn-n btn-green btn-bold" type="buttton" onclick="saveGrade()">저장</button>
                    <button class="btn-n btn-gray <?if($month == 'm_3') echo "active";?>" id="m_3" onclick="viewMonth(event)" type="buttton">3모</button>
                    <button class="btn-n btn-gray <?if($month == 'm_6') echo "active";?>" id="m_6" onclick="viewMonth(event)" type="buttton">6모</button>
                    <button class="btn-n btn-gray <?if($month == 'm_9') echo "active";?>" id="m_9" onclick="viewMonth(event)" type="buttton">9모</button>
                    <button class="btn-n btn-gray <?if($month == 'm_0') echo "active";?>" id="m_0" onclick="viewMonth(event)" type="buttton">수능가채점</button>
                    <button class="btn-n btn-gray <?if($month == 'm_1') echo "active";?>" id="m_1" onclick="viewMonth(event)" type="buttton">수능</button>
                </div>
            </div>


            <div class="tbl_wrap">
                <table class="tbl_head01">
                    <colgroup width="20%">
                    <colgroup width="20%">
                    <colgroup width="20%">
                    <colgroup width="20%">
                    <colgroup width="20%">
                    <thead>
                        <th>영역</th>
                        <th>원점수</th>
                        <th>표준점수</th>
                        <th>백분위</th>
                        <th>등급</th>
                    </thead>
                    <tbody>
                        <tr style="text-align: center;">
                            <td style="text-align: left;">
                                국어<br>
                                <select id="korean" name="korean" class="frm_input" style="width: 100%;">
                                    <option value="">선택하세요</option>
                                    <option value="kor1">화법과 작문</option>
                                    <option value="kor2">언어와 매체</option>
                                </select>
                            </td>
                            <td><br><input type="text" class="frm_input" style="width: 100%;" id="koreanOrigin" name="koreanOrigin"></td>
                            <td><br><input type="text" class="frm_input" style="width: 100%;" id="koreanSScore" name="koreanSScore"></td>
                            <td><br><input type="text" class="frm_input" style="width: 100%;" id="koreanPScore" name="koreanPScore"></td>
                            <td><br><input type="text" class="frm_input" style="width: 100%;" id="koreanGrade" name="koreanGrade"></td>
                        </tr>
                        <tr style="text-align: center;">
                            <td style="text-align: left;">
                                수학<br>
                                <select id="math" name="math" class="frm_input" style="width: 100%;">
                                    <option value="">선택하세요</option>
                                    <option value="math1">확률과 통계</option>
                                    <option value="math2">미적분</option>
                                    <option value="math3">기하</option>
                                </select>
                            </td>
                            <td><br><input type="text" class="frm_input" style="width: 100%;" id="mathOrigin" name="mathOrigin"></td>
                            <td><br><input type="text" class="frm_input" style="width: 100%;" id="mathSScore" name="mathSScore"></td>
                            <td><br><input type="text" class="frm_input" style="width: 100%;" id="mathPScore" name="mathPScore"></td>
                            <td><br><input type="text" class="frm_input" style="width: 100%;" id="mathGrade" name="mathGrade"></td>
                        </tr>
                        <tr style="text-align: center;">
                            <td style="text-align: left;">
                                영어
                            </td>
                            <td><input type="text" class="frm_input" style="width: 100%;" id="englishOrigin" name="englishOrigin"></td>
                            <td>-</td>
                            <td>-</td>
                            <td><input type="text" class="frm_input" style="width: 100%;" id="englishGrade" name="englishGrade"></td>
                        </tr>
                        <tr style="text-align: center;">
                            <td style="text-align: left;">
                                탐구영역1<br>
                                <select id="sub1" name="sub1" class="frm_input" style="width: 100%;">
                                    <option value="">탐구영역 과목1</option>
                                    <option value="sub1_A_1">생활과 윤리</option>
                                    <option value="sub1_A_2">윤리와 사상</option>
                                    <option value="sub1_A_3">한국 지리</option>
                                    <option value="sub1_A_4">세계 지리</option>
                                    <option value="sub1_A_5">동아시아사</option>
                                    <option value="sub1_A_6">세계사</option>
                                    <option value="sub1_A_7">정치와 법</option>
                                    <option value="sub1_A_8">경제</option>
                                    <option value="sub1_A_9">사회문화</option>
                                    <!-- 과학탐구영역 리스트 -->
                                    <option value="sub1_B_1">물리학Ⅰ</option>
                                    <option value="sub1_B_2">화학Ⅰ</option>
                                    <option value="sub1_B_3">생명과학Ⅰ</option>
                                    <option value="sub1_B_4">지구과학Ⅰ</option>
                                    <option value="sub1_B_5">물리Ⅱ</option>
                                    <option value="sub1_B_6">화학Ⅱ</option>
                                    <option value="sub1_B_7">생명과학Ⅱ</option>
                                    <option value="sub1_B_8">지구과학Ⅱ</option>
                                    <!-- 직업탐구영역 리스트 -->
                                    <option value="sub1_C_1">농생명 산업①</option>
                                    <option value="sub1_C_2">농생명 산업②</option>
                                    <option value="sub1_C_3">공업①</option>
                                    <option value="sub1_C_4">공업②</option>
                                    <option value="sub1_C_5">상업 정보①</option>
                                    <option value="sub1_C_6">상업 정보②</option>
                                    <option value="sub1_C_7">수산·해운①</option>
                                    <option value="sub1_C_8">수산·해운②</option>
                                    <option value="sub1_C_9">가사·실업①</option>
                                    <option value="sub1_C_10">가사·실업②</option>
                                </select>
                            </td>
                            <td><br><input type="text" class="frm_input" style="width: 100%;" id="sub1Origin" name="sub1Origin"></td>
                            <td><br><input type="text" class="frm_input" style="width: 100%;" id="sub1SScore" name="sub1SScore"></td>
                            <td><br><input type="text" class="frm_input" style="width: 100%;" id="sub1PScore" name="sub1PScore"></td>
                            <td><br><input type="text" class="frm_input" style="width: 100%;" id="sub1Grade" name="sub1Grade"></td>
                        </tr>
                        <tr style="text-align: center;">
                            <td style="text-align:left;">
                                탐구영역2<br>
                                <select id="sub2" name="sub2" class="frm_input" style="width: 100%;">
                                    <option value="">탐구영역 과목2</option>
                                    <option value="sub2_A_1">생활과 윤리</option>
                                    <option value="sub2_A_2">윤리와 사상</option>
                                    <option value="sub2_A_3">한국 지리</option>
                                    <option value="sub2_A_4">세계 지리</option>
                                    <option value="sub2_A_5">동아시아사</option>
                                    <option value="sub2_A_6">세계사</option>
                                    <option value="sub2_A_7">정치와 법</option>
                                    <option value="sub2_A_8">경제</option>
                                    <option value="sub2_A_9">사회문화</option>
                                    <!-- 과학탐구영역 리스트 -->
                                    <option value="sub2_B_1">물리학Ⅰ</option>
                                    <option value="sub2_B_2">화학Ⅰ</option>
                                    <option value="sub2_B_3">생명과학Ⅰ</option>
                                    <option value="sub2_B_4">지구과학Ⅰ</option>
                                    <option value="sub2_B_5">물리Ⅱ</option>
                                    <option value="sub2_B_6">화학Ⅱ</option>
                                    <option value="sub2_B_7">생명과학Ⅱ</option>
                                    <option value="sub2_B_8">지구과학Ⅱ</option>
                                    <!-- 직업탐구영역 리스트 -->
                                    <option value="sub2_C_1">농생명 산업①</option>
                                    <option value="sub2_C_2">농생명 산업②</option>
                                    <option value="sub2_C_3">공업①</option>
                                    <option value="sub2_C_4">공업②</option>
                                    <option value="sub2_C_5">상업 정보①</option>
                                    <option value="sub2_C_6">상업 정보②</option>
                                    <option value="sub2_C_7">수산·해운①</option>
                                    <option value="sub2_C_8">수산·해운②</option>
                                    <option value="sub2_C_9">가사·실업①</option>
                                    <option value="sub2_C_10">가사·실업②</option>
                                </select>
                            </td>
                            <td><input type="text" class="frm_input" style="width: 100%;" id="sub2Origin" name="sub2Origin"></td>
                            <td><input type="text" class="frm_input" style="width: 100%;" id="sub2SScore" name="sub2SScore"></td>
                            <td><input type="text" class="frm_input" style="width: 100%;" id="sub2PScore" name="sub2PScore"></td>
                            <td><input type="text" class="frm_input" style="width: 100%;" id="sub2Grade" name="sub2Grade"></td>
                        </tr>
                        <tr style="text-align: center;">
                            <td style="text-align: left;">
                                한국사
                            </td>
                            <td><input type="text" class="frm_input" style="width: 100%;" id="historyOrigin" name="historyOrigin"></td>
                            <td>-</td>
                            <td>-</td>
                            <td><input type="text" class="frm_input" style="width: 100%;" id="historyGrade" name="historyGrade"></td>
                        </tr>
                        <tr style="text-align: center;">
                            <td style="text-align: left;">
                                제2외국어/한문<br>
                                <select id="foreign" name="foreign" class="frm_input" style="width: 100%;">
                                    <option value="">선택하세요</option>
                                    <option value="foreign1">독일어Ⅰ</option>
                                    <option value="foreign2">프랑스어Ⅰ</option>
                                    <option value="foreign3">스페인어Ⅰ</option>
                                    <option value="foreign4">중국어Ⅰ</option>
                                    <option value="foreign5">일본어Ⅰ</option>
                                    <option value="foreign6">러시아어Ⅰ</option>
                                    <option value="foreign7">아랍어</option>
                                    <option value="foreign8">기초 베트남어</option>
                                    <option value="foreign9">한문 I</option>
                                </select>
                            </td>
                            <td><br><input type="text" class="frm_input" style="width: 100%;" id="foreignOrigin" name="foreignOrigin"></td>
                            <td><br><input type="text" class="frm_input" style="width: 100%;" id="foreignSScore" name="foreignSScore"></td>
                            <td><br><input type="text" class="frm_input" style="width: 100%;" id="foreignPScore" name="foreignPScore"></td>
                            <td><br><input type="text" class="frm_input" style="width: 100%;" id="foreignGrade" name="foreignGrade"></td>
                        </tr>
                        <tr style="text-align: center;">
                            <td style="text-align:left;">
                                내신
                            </td>
                            <td colspan="4">
                                <select class="frm_input" id="grade" name="grade" style="width: 100%;">
                                    <option value="">선택하세요.</option>
                                    <option value="1">1등급</option>
                                    <option value="2">2등급</option>
                                    <option value="3">3등급</option>
                                    <option value="4">4등급</option>
                                    <option value="5">5등급</option>
                                    <option value="6">6등급</option>
                                    <option value="7">7등급</option>
                                    <option value="8">8등급</option>
                                    <option value="9">9등급</option>
                                </select>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div>
                <button class="btn-n btn-green btn-bold btn-large" type="buttton" onclick="saveGrade()">저장</button>
            </div>
        </section>
        <!-- } 성적입력 끝 -->
    </div>
    <div id="smb_my_list" style="width: 100%;">
        <!-- 지원대학 시작 { -->
        <section id="smb_my_od">
            <h2>지원대학</h2>

            <div class="tbl_wrap">
                <table class="tbl_head01">
                    <colgroup width="*">
                    <colgroup width="*">
                    <colgroup width="*">
                    <colgroup width="*">
                    <colgroup width="10%">
                    <thead>
                        <th>학교명</th>
                        <th>본인점수</th>
                        <th>지원가능여부</th>
                        <th>저장시간</th>
                        <th></th>
                    </thead>
                    <tbody>
                        <?
                            $myCampus = sql_query("");
                            foreach($myCampus as $mcc => $mc){
                        ?>
                        <tr style="text-align: center;">
                            <td style="text-align: left;">
                                국어<br>
                                <select id="korean" name="korean" class="frm_input" style="width: 100%;">
                                    <option value="">선택하세요</option>
                                    <option value="kor1">화법과 작문</option>
                                    <option value="kor2">언어와 매체</option>
                                </select>
                            </td>
                            <td><br><input type="text" class="frm_input" style="width: 100%;" id="koreanOrigin" name="koreanOrigin"></td>
                            <td><br><input type="text" class="frm_input" style="width: 100%;" id="koreanSScore" name="koreanSScore"></td>
                            <td><br><input type="text" class="frm_input" style="width: 100%;" id="koreanPScore" name="koreanPScore"></td>
                            <td><br><input type="text" class="frm_input" style="width: 100%;" id="koreanGrade" name="koreanGrade"></td>
                        </tr>
                        <?}?>
                    </tbody>
                </table>
            </div>
        </section>
        <!-- } 지원대학 끝 -->
    </div>
</div>

<div id="branchPopup">
    <div class="mb20" id="branchDiv">
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
                        <th>지점명</th>
                        <td>
                            <input type="text" class="frm_input" id="branchName" name="branchName" value="" autocomplete="off" style="width:100%">
                            <input type="hidden" id="idx" name="idx">
                            <input type="hidden" id="btype" name="btype">
                        </td>
                        <th>활성여부</th>
                        <td>
                            <select class="frm_input" name="branchActive" id="branchActive" style="width: 100%;">
                                <option value="1">활성</option>
                                <option value="0">비활성</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>담당자</th>
                        <td>
                            <input type="text" class="frm_input" id="branchManager" name="branchManager" value="" autocomplete="off" style="width: 100%;">
                        </td>
                        <th>연락처</th>
                        <td>
                            <input type="text" class="frm_input" id="branchHp" name="branchHp" value="" autocomplete="off" style="width: 100%;">
                        </td>
                    </tr>
                    <tr>
                        <th>비고</th>
                        <td colspan="3">
                            <textarea id="branchMemo" name="branchMemo"></textarea>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <button id="closePopup">닫기</button>
    <button id="branchBtn">저장</button>
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
                            <input type="text" class="frm_input" id="mb_birth" name="mb_birth" value="" autocomplete="off" style="width: 100%;">
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
                                <option value="1">관리자</option>
                                <option value="2">원장</option>
                                <option value="3">원내학생</option>
                                <option value="4">일반</option>
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
</div>

<script>

    $("#branchBtn").on('click',function(){
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
                        url: "/bbs/update_branch.php",
                        type: "POST",
                        data: {
                            branchName : $("#branchName").val(),
                            branchManager : $("#branchManager").val(),
                            branchHp: $("#branchHp").val(),
                            branchMemo: $("#branchMemo").val(),
                            branchActive : $("#branchActive").val(),
                            type : $("#btype").val(),
                        },
                        async: false,
                        error: function(data) {
                            alert('에러가 발생하였습니다.');
                            return false;
                        },
                        success: function(data) {
                            if(data == 'success'){
                                swal('성공!','성공적으로 등록되었습니다.','success');
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

    function viewMonth(e){
        let id = e.currentTarget.id;
        location.href = './mypage?month=' + id;
    }

    function saveGrade(){
        let month = '<?=$month?>';
        let tag = "";
        let exist = $("#gradeIdx").val();
        let type = "save";
        if(exist){
            type = 'update';
        } else {
            type = 'save';
        }
        switch(month){
            case 'm_3':
                tag = '3월 모의고사';
                break;
            case 'm_6':
                tag = '6월 모의고사';
                break;
            case 'm_9':
                tag = '9월 모의고사';
                break;
            case 'm_0':
                tag = '수능가채점';
                break;
            case 'm_1':
                tag = '수능';
                break;
        }
        swal({
            title : tag + ' 점수',
            text : '저장하시겠습니까?',
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
                        url: "/bbs/update_grade.php",
                        type: "POST",
                        data: {
                            korean : $("#korean").val(),
                            koreanOrigin : $("#koreanOrigin").val(),
                            koreanSScore : $("#koreanSScore").val(),
                            koreanPScore : $("#koreanPScore").val(),
                            koreanGrade : $("#koreanGrade").val(),
                            math : $("#math").val(),
                            mathOrigin : $("#mathOrigin").val(),
                            mathSScore : $("#mathSScore").val(),
                            mathPScore : $("#mathPScore").val(),
                            mathGrade : $("#mathGrade").val(),
                            englishOrigin : $("#englishOrigin").val(),
                            englishGrade : $("#englishGrade").val(),
                            sub1 : $("#sub1").val(),
                            sub1Origin : $("#sub1Origin").val(),
                            sub1SScore : $("#sub1SScore").val(),
                            sub1PScore : $("#sub1PScore").val(),
                            sub1Grade : $("#sub1Grade").val(),
                            sub2 : $("#sub2").val(),
                            sub2Origin : $("#sub2Origin").val(),
                            sub2SScore : $("#sub2SScore").val(),
                            sub2PScore : $("#sub2PScore").val(),
                            sub2Grade : $("#sub2Grade").val(),
                            historyOrigin : $("#historyOrigin").val(),
                            historyGrade : $("#historyGrade").val(),
                            foreign : $("#foreign").val(),
                            foreignOrigin : $("#foreignOrigin").val(),
                            foreignSScore : $("#foreignSScore").val(),
                            foreignPScore : $("#foreignPScore").val(),
                            foreignGrade : $("#foreignGrade").val(),
                            grade : $("#grade").val(),
                            type : type,
                        },
                        async: false,
                        error: function(data) {
                            alert('에러가 발생하였습니다.');
                            return false;
                        },
                        success: function(data) {
                            if(data == 'success'){
                                swal('성공!','성공적으로 등록되었습니다.','success');
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

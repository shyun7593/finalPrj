<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

$g5['title'] = '성적입력';
include_once('./_head.php');

if(!$month){
    $month = 'm_3';
}
if($_SESSION['mb_student']){
    $membId = $_SESSION['mb_student'];
} else {
    $membId = $member['mb_id'];
}

$bcnt = sql_query("select COUNT(*) as 'cnt'
                        from g5_branch");

$m0 = sql_fetch("SELECT COUNT(*) as 'cnt' FROM g5_member_score WHERE memId = '{$membId}' AND scoreMonth = 'm_0'");
$m1 = sql_fetch("SELECT COUNT(*) as 'cnt' FROM g5_member_score WHERE memId = '{$membId}' AND scoreMonth = 'm_1'");
$m3 = sql_fetch("SELECT COUNT(*) as 'cnt' FROM g5_member_score WHERE memId = '{$membId}' AND scoreMonth = 'm_3'");
$m6 = sql_fetch("SELECT COUNT(*) as 'cnt' FROM g5_member_score WHERE memId = '{$membId}' AND scoreMonth = 'm_6'");
$m9 = sql_fetch("SELECT COUNT(*) as 'cnt' FROM g5_member_score WHERE memId = '{$membId}' AND scoreMonth = 'm_9'");


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
                    <button class="btn-n <?if($month == 'm_3') echo "active";?> <?if($m3['cnt'] > 0) {echo "iswrite";}else{echo "btn-gray";}?>" id="m_3" onclick="viewMonth(event)" type="buttton">3모</button>
                    <button class="btn-n <?if($month == 'm_6') echo "active";?> <?if($m6['cnt'] > 0) {echo "iswrite";}else{echo "btn-gray";}?>" id="m_6" onclick="viewMonth(event)" type="buttton">6모</button>
                    <button class="btn-n <?if($month == 'm_9') echo "active";?> <?if($m9['cnt'] > 0) {echo "iswrite";}else{echo "btn-gray";}?>" id="m_9" onclick="viewMonth(event)" type="buttton">9모</button>
                    <button class="btn-n <?if($month == 'm_0') echo "active";?> <?if($m0['cnt'] > 0) {echo "iswrite";}else{echo "btn-gray";}?>" id="m_0" onclick="viewMonth(event)" type="buttton">수능가채점</button>
                    <button class="btn-n <?if($month == 'm_1') echo "active";?> <?if($m1['cnt'] > 0) {echo "iswrite";}else{echo "btn-gray";}?>" id="m_1" onclick="viewMonth(event)" type="buttton">수능</button>
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
                        <?
                        $subs = sql_query("SELECT code, codeName,upperCode FROM g5_cmmn_code gcc WHERE upperCode = (SELECT code FROM g5_cmmn_code WHERE codeName = '과목' AND useYn = 1) ORDER BY codeDesc");
                        $i=1;
                        foreach($subs as $sub => $s){
                            $memberGrade = sql_fetch("SELECT gms.* FROM g5_member_score gms WHERE gms.memId = '{$membId}' AND gms.scoreMonth = '{$month}' AND upperCode = '{$s['code']}'");
                            ?>
                            <tr style="text-align: center;" class="mySubgrade">
                                <?if($s['codeName'] != '영어' && $s['codeName'] != '한국사'){?>
                                <td style="text-align: left;">
                                    <?=$s['codeName']?><br>
                                    <select name="subject" class="frm_input" style="width: 100%;">
                                        <option value="">선택하세요</option>
                                        <?$jsql = sql_query("SELECT * FROM g5_cmmn_code WHERE upperCode = '{$s['code']}'");
                                        foreach($jsql as $js => $j){?>
                                            <option value="<?=$j['code']?>" <?if($memberGrade['subject'] == $j['code']) echo 'selected';?>><?=$j['codeName']?></option>
                                        <?}?>
                                    </select>
                                    <input type="hidden" name="subjectCode" value="<?=$memberGrade['subject']?>">
                                    <input type="hidden" name="upperCode" value="<?=$s['code']?>">
                                </td>
                                <td><br><input type="text" class="frm_input" style="width: 100%;text-align:center;" name="origin" value="<?=$memberGrade['origin']?>"></td>
                                <td><br><input type="text" class="frm_input" style="width: 100%;text-align:center;" name="sscore" value="<?=$memberGrade['sscore']?>"></td>
                                <td><br><input type="text" class="frm_input" style="width: 100%;text-align:center;" name="pscore" value="<?=$memberGrade['pscore']?>"></td>
                                <td><br><input type="text" class="frm_input" style="width: 100%;text-align:center;" name="grade" value="<?=$memberGrade['grade']?>"></td>
                                <?} else{?>
                                    <td style="text-align: left;">
                                        <?=$s['codeName']?>
                                        <input type="hidden" name="subjectCode" value="<?=$s['code']?>">
                                        <input type="hidden" name="upperCode" value="<?=$s['code']?>">
                                    </td>
                                    <td><br>-</td>
                                    <td><br>-</td>
                                    <td><br>-</td>
                                    <td><br><input type="text" class="frm_input" style="text-align:center;width: 100%;" name="grade" value="<?=$memberGrade['grade']?>"></td>    
                                <?}?>
                            </tr>
                        <?$i++;}?>
                        
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
                        
                    </tbody>
                </table>
            </div>
        </section>
        <!-- } 지원대학 끝 -->
    </div>
</div>



<script>

    

    function viewMonth(e){
        let id = e.currentTarget.id;
        location.href = './myscore?month=' + id;
    }

    function saveGrade(){
        let month = '<?=$month?>';
        
        let gradeArray = $(".mySubgrade");

        let subject = [];
        let upperCode = [];
        let origin = [];
        let sscore = [];
        let pscore = [];
        let grade = [];
        
        
        for(let i = 0; i < gradeArray.length; i++){
            
            let row = $(gradeArray[i]);

            if(i != 5){
                if(!row.find('td:eq(0)').find('input[name="subjectCode"]').val() ||
                !row.find('td:eq(1)').find('input[type="text"]').val() ||
                !row.find('td:eq(2)').find('input[type="text"]').val() ||
                !row.find('td:eq(3)').find('input[type="text"]').val() ||
                !row.find('td:eq(4)').find('input[type="text"]').val()
            ){
                swal("경고!",'제2외국어를 제외한 과목은 필수로 입력해주세요.','warning');
                setTimeout(() => {
                    swal.close();
                }, 1500);
                return false;
                }
            }

            subject.push(row.find('td:eq(0)').find('input[name="subjectCode"]').val());
            upperCode.push(row.find('td:eq(0)').find('input[name="upperCode"]').val());
            origin.push(row.find('td:eq(1)').find('input[type="text"]').val());
            sscore.push(row.find('td:eq(2)').find('input[type="text"]').val());
            pscore.push(row.find('td:eq(3)').find('input[type="text"]').val());
            grade.push(row.find('td:eq(4)').find('input[type="text"]').val());
        }
        
        $.ajax({
            url: "/bbs/myGrade_update.php",
            type: "POST",
            data: {
                subject : subject,
                upperCode : upperCode,
                origin : origin,
                sscore : sscore,
                pscore : pscore,
                grade : grade,
                month : month,
                id : '<?=$membId?>',
            },
            async: false,
            error: function(data) {
                alert('저장 실패! 관리자에게 문의하세요.');
            },
            success: function(data) {
                if(data == 'success'){
                    swal('성공!','저장하였습니다.','success');
                    setTimeout(() => {
                        swal.close();
                        location.reload();
                    }, 1500);
                }
            }
        });
    }

    $("select[name='subject']").on("change",function(){
        $(this).closest('tr').find('input[name="subjectCode"]').val($(this).val());
    });
</script>
<!-- } 마이페이지 끝 -->

<?php
include_once("./_tail.php");

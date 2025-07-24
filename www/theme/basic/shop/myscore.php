<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

$g5['title'] = '성적입력';
include_once('./_head.php');

if(!$month){
    $month = 'C60000001';
}

if(!$year){
    $year = date('Y');
}

$recD = sql_fetch("SELECT regDate FROM g5_gradeCut WHERE gradeType = '{$month}' AND gradeYear = '{$year}' limit 1");

if($_SESSION['mb_student']){
    $membId = $_SESSION['mb_student'];
} else {
    $membId = $member['mb_id'];
}

$bcnt = sql_query("select COUNT(*) as 'cnt'
                        from g5_branch");

$m_cmmn = sql_query("SELECT * FROM g5_cmmn_code WHERE upperCode = (SELECT code FROM g5_cmmn_code WHERE codeName = '모의고사')");


?>

<!-- 마이페이지 시작 { -->
<div id="smb_my">

    <div id="smb_my_list">
        <!-- 성적입력 시작 { -->
        <section id="smb_my_od">
            <div style="display: flex; align-items:center;gap:30px;margin-bottom:10px;">
                <div style="display: flex;gap:10px;">
                    <button class="btn-n btn-green btn-bold" type="buttton" onclick="saveGrade()">저장</button>
                    <?
                        foreach($m_cmmn as $mcm => $m){
                            $cnt = sql_fetch("SELECT COUNT(*) as cnt FROM g5_member_score WHERE memId = '{$membId}' AND scoreMonth = '{$m['code']}'");
                            ?>
                        <button class="btn-n <?if($month == $m['code']) echo "active2";?> <?if($cnt['cnt'] > 0) {echo "iswrite";}else{echo "btn-gray";}?>" id="<?=$m['code']?>" onclick="viewMonth(event)" type="buttton"><?=$m['codeName']?></button>
                    <?}?>
                </div>
                <div style="position:absolute;right:0;">
                    점수 업데이트 : <?=$recD['regDate']?>
                </div>
            </div>


            <div class="tbl_wrap border-tb">
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
                        foreach($subs as $sub => $s){
                            $i = 0;
                            $sub = "";
                            $memberGrade = sql_fetch("SELECT gms.* FROM g5_member_score gms WHERE gms.memId = '{$membId}' AND gms.scoreMonth = '{$month}' AND upperCode = '{$s['code']}'");
                            ?>
                            <tr style="text-align: center;" class="mySubgrade">
                                <?if($s['codeName'] != '영어' && $s['codeName'] != '한국사' && $s['codeName'] != '제2외국어/한문'){?>
                                <td style="text-align: left;">
                                    <?=$s['codeName']?><br>
                                    <select name="subject" class="frm_input" style="width: 100%;">
                                        <option value="">선택하세요</option>
                                        <?$jsql = sql_query("SELECT * FROM g5_cmmn_code WHERE upperCode = '{$s['code']}'");
                                        foreach($jsql as $js => $j){
                                            if(!$memberGrade['subject']){
                                                if(strstr($s['code'],'C2005')){
                                                    $i2 = 1;
                                                    if($i == 1){
                                                        $sub = $j['code'];
                                                    }
                                                } else {
                                                    $i2 = 0;
                                                    if($i == 0){
                                                        $sub = $j['code'];
                                                    }
                                                }
                                            }
                                            ?>
                                            <option value="<?=$j['code']?>" <?if(($memberGrade['subject'] == $j['code']) || (!$memberGrade['subject'] && $i==$i2)) echo 'selected';?>><?=$j['codeName']?></option>
                                        <?$i++;}?>
                                    </select>
                                    <input type="hidden" name="subjectCode" value="<?if($memberGrade['subject']){echo "{$memberGrade['subject']}";}else{echo "{$sub}";}?>">
                                    <input type="hidden" name="upperCode" value="<?=$s['code']?>">
                                </td>
                                <td><br><input type="number" oninput="this.value = Math.max(0, Math.min(<?if($s['codeName'] == '탐구영역1' || $s['codeName'] == '탐구영역2'){echo 50;}else{ echo 100;}?>, this.value))" class="frm_input" style="width: 100%;text-align:center;" name="origin" value="<?=$memberGrade['origin']?>"></td>
                                <td><br><input type="number" class="frm_input" style="width: 100%;text-align:center;" name="pscore" value="<?=$memberGrade['pscore']?>"></td>
                                <td><br><input type="number" class="frm_input" style="width: 100%;text-align:center;" name="sscore" value="<?=$memberGrade['sscore']?>"></td>
                                <td><br><input type="number" class="frm_input" style="width: 100%;text-align:center;" name="grade" value="<?=$memberGrade['grade']?>"></td>
                                <?} else if($s['codeName'] == '제2외국어/한문'){?>
                                    <td style="text-align: left;">
                                    <?=$s['codeName']?><br>
                                    <select name="subject" class="frm_input" style="width: 100%;">
                                        <option value="">선택하세요</option>
                                        <?$jsql = sql_query("SELECT * FROM g5_cmmn_code WHERE upperCode = '{$s['code']}'");
                                        foreach($jsql as $js => $j){
                                            ?>
                                            <option value="<?=$j['code']?>" <?if(($memberGrade['subject'] == $j['code'])) echo 'selected';?>><?=$j['codeName']?></option>
                                        <?}?>
                                    </select>
                                    <input type="hidden" name="subjectCode" value="<?if($memberGrade['subject']){echo "{$memberGrade['subject']}";}?>">
                                    <input type="hidden" name="upperCode" value="<?=$s['code']?>">
                                </td>
                                <td><br><input type="number" oninput="this.value = Math.max(0, Math.min(50, this.value))" class="frm_input" style="width: 100%;text-align:center;" name="origin" value="<?=$memberGrade['origin']?>"></td>
                                <td><br>-</td>
                                <td><br>-</td>
                                <td><br><input type="number" class="frm_input" style="width: 100%;text-align:center;" name="grade" value="<?=$memberGrade['grade']?>"></td>
                                <?} else{
                                    $subJectCd = sql_fetch("SELECT code FROM g5_cmmn_code WHERE upperCode = '{$s['code']}'");
                                    ?>
                                    <td style="text-align: left;">
                                        <?=$s['codeName']?>
                                        <input type="hidden" name="subjectCode" value="<?=$subJectCd['code']?>">
                                        <input type="hidden" name="upperCode" value="<?=$s['code']?>">
                                    </td>
                                    <td><br><input type="number" oninput="this.value = Math.max(0, Math.min(<?if($s['codeName'] == '한국사'){echo 50;}else{ echo 100;}?>, this.value))" class="frm_input" style="width: 100%;text-align:center;" name="origin" value="<?=$memberGrade['origin']?>"></td>
                                    <td><br>-</td>
                                    <td><br>-</td>
                                    <td><br><input type="number" class="frm_input" style="text-align:center;width: 100%;" name="grade" value="<?=$memberGrade['grade']?>"></td>    
                                <?}?>
                            </tr>
                        <?}?>
                        
                        <tr style="text-align: center;">
                            <td style="text-align:left;">
                                내신
                            </td>
                            <td colspan="4">
                                <select class="frm_input" id="grade" name="grade" style="width: 100%;">
                                    <option value="">선택하세요.</option>
                                    <?
                                        $admitt = sql_query("SELECT code, codeName FROM g5_cmmn_code WHERE upperCode = 'C50000000' AND useYN = 1");
                                        $memberGrade = sql_fetch("SELECT gms.* FROM g5_member_score gms WHERE gms.memId = '{$membId}' AND gms.scoreMonth = '{$month}' AND upperCode = 'C50000000'");
                                        foreach($admitt as $adm => $a){
                                    ?>
                                    <option value="<?=$a['code']?>" <?if($memberGrade['subject'] == $a['code']) echo 'selected';?>><?=$a['codeName']?></option>
                                    <?}?>
                                </select>
                                <input type="hidden" name="admittupperCode" id="admittupperCode" value="C50000000">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div style="margin-top:10px;">
                <button class="btn-n btn-green btn-bold btn-large" type="buttton" onclick="saveGrade()">저장</button>
            </div>
        </section>
        <!-- } 성적입력 끝 -->
    </div>
    <div id="smb_my_list" style="width: 100%;">
        <!-- 지원대학 시작 { -->
        <section id="smb_my_od">
            <h2>지원대학</h2>

            <div class="tbl_wrap border-tb">
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

        let totalGrade = $("#grade").val();
        let admitt = $("#admittupperCode").val();
        
        
        for(let i = 0; i < gradeArray.length; i++){
            
            let row = $(gradeArray[i]);

            if(i != 5 && i != 2 && i != 7 && i != 6){
                if(!row.find('td:eq(0)').find('input[name="subjectCode"]').val() ||
                !row.find('td:eq(1)').find('input[type="number"]').val() ||
                !row.find('td:eq(2)').find('input[type="number"]').val() ||
                !row.find('td:eq(3)').find('input[type="number"]').val() ||
                !row.find('td:eq(4)').find('input[type="number"]').val()
            ){
                swal("경고!",'제2외국어를 제외한 과목은 필수로 입력해주세요.','warning');
                setTimeout(() => {
                    swal.close();
                }, 1500);
                return false;
                }
            }

            if(i == 2 || i == 5){
                if(!row.find('td:eq(1)').find('input[type="number"]').val() || 
                !row.find('td:eq(4)').find('input[type="number"]').val()){
                    swal("경고!",'점수, 등급을 필수로 입력해주세요.','warning');
                    setTimeout(() => {
                        swal.close();
                    }, 1500);
                    return false;
                }
            }            

            subject.push(row.find('td:eq(0)').find('input[name="subjectCode"]').val());
            upperCode.push(row.find('td:eq(0)').find('input[name="upperCode"]').val());
            origin.push(row.find('td:eq(1)').find('input[type="number"]').val());
            pscore.push(row.find('td:eq(2)').find('input[type="number"]').val());
            sscore.push(row.find('td:eq(3)').find('input[type="number"]').val());
            grade.push(row.find('td:eq(4)').find('input[type="number"]').val());
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
                totalGrade : totalGrade,
                admitt:admitt,
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
            const $row = $(this).closest('tr');
            $row.find('input[name="origin"]').val('');
            $row.find('input[name="sscore"]').val('');
            $row.find('input[name="pscore"]').val('');
            $row.find('input[name="grade"]').val('');
        
        $(this).closest('tr').find('input[name="subjectCode"]').val($(this).val());
    });


    const cache = {};

    $('input[name="origin"]').on('change', function () {
        
        const $row = $(this).closest('tr');
        const subjectCode = $row.find('input[name="subjectCode"]').val();
        
        if(!subjectCode){
            swal('','과목을 선택해 주세요.','warning');
            $row.find('input[name="origin"]').val('');
            $row.find('input[name="sscore"]').val('');
            $row.find('input[name="pscore"]').val('');
            $row.find('input[name="grade"]').val('');
            return;
        }

        const month = "<?=$month?>";
        const score = $(this).val();
        const key = `${subjectCode}-${month}-${score}`; // origin 값 포함!
        
            if (cache[key]) {
                applyScore($row, cache[key]);
                return;
            }

            $.ajax({
                type: 'POST',
                url: '/bbs/get_gradeCut.php',
                data: { subjectCode, month, score },
                success: function (res) {
                    const data = JSON.parse(res);
                    cache[key] = data;
                    applyScore($row, data);
                }
            });
        
        
    });

    function applyScore($row, data) {
        $row.find('input[name="sscore"]').val(data.sscore);
        $row.find('input[name="pscore"]').val(data.pscore);
        $row.find('input[name="grade"]').val(data.gGrade);
    }

</script>
<!-- } 마이페이지 끝 -->

<?php
include_once("./_tail.php");

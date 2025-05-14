<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

$g5['title'] = '실기기록';
include_once('./_head.php');



$add_sql = " 1=1 ";
$branch_sql = " 1=1 ";

if($gender){
    $add_sql .= " AND mb.mb_sex = '{$gender}' ";
}

if($branchIdx){
    $add_sql .= " AND mb.mb_signature = '{$branchIdx}' ";
}

switch($_SESSION['mb_profile']){
    case 'C40000001': // 관리자
        $add_sql .= " AND 1=1 ";
        break;
    case 'C40000002': // 선생님
        $add_sql .= " AND mb.mb_signature = '{$_SESSION['mb_signature']}' ";
        $branch_sql .= " AND b.idx = '{$_SESSION['mb_signature']}' ";
        $branchIdx = $_SESSION['mb_signature'];
        break;
    case 'C40000003': // 학생
        $add_sql .= " AND sp.memberIdx = '{$_SESSION['ss_mb_id']}' ";
        $branch_sql .= " AND b.idx = '{$_SESSION['mb_signature']}' ";
        $branchIdx = $_SESSION['mb_signature'];
        break;
}

$sql = "SELECT 
            sp.memberIdx ,
            sp.`date` ,
            sp.sRank ,
            sp.grade ,
            sp.core_Rank,
            sp.core_score,
            sp.10m_Rank,
            sp.10m_score,
            sp.medicine_Rank,
            sp.medicine_score,
            sp.left_Rank,
            sp.left_score,
            sp.stand_Rank,
            sp.stand_score,
            sp.20mBu_Rank,
            sp.20mBu_score,
            sp.situp_Rank,
            sp.situp_score,
            sp.surgent_Rank,
            sp.surgent_score,
            sp.total_Rank,
            sp.total_Rev,
            (SELECT branchName FROM g5_branch b WHERE mb.mb_signature = b.idx) as 'branchName', 
            mb.mb_name, 
            mb.mb_1,
	        mb.mb_sex
        FROM g5_student_Practice sp
        JOIN g5_member mb on
        sp.memberIdx = mb.mb_id
        WHERE
         {$add_sql}
        ORDER BY `date`,grade
";
$scnt = sql_fetch("SELECT 
            count(*) as 'cnt'
        FROM g5_student_Practice sp
        JOIN g5_member mb on
        sp.memberIdx = mb.mb_id
        WHERE
         {$add_sql}
        ORDER BY `date`,grade");
$bsql = "SELECT * FROM g5_branch b WHERE {$branch_sql}";

?>

<style>
.tbl_wrap {
  overflow-x: auto; /* 가로 스크롤 */
  -webkit-overflow-scrolling: touch; /* 모바일에서 스크롤 부드럽게 */
}

.tbl_head01 {
  border-collapse: collapse;
  width: 100%;
}



.tbl_head01 th, .tbl_head01 td {
  text-align: center;
}

/* 첫 번째 열부터 여섯 번째 열까지 sticky 처리 */
.tbl_head01 th:nth-child(1),
.tbl_head01 td:nth-child(1) {
  position: sticky;
  left: 0;
  background: #fff;
  z-index: 12;
}

.tbl_head01 th:nth-child(2),
.tbl_head01 td:nth-child(2) {
  position: sticky;
  left: 100px;
  background: #fff;
  z-index: 12;
}

.tbl_head01 th:nth-child(3),
.tbl_head01 td:nth-child(3) {
  position: sticky;
  left: 150px;
  background: #fff;
  z-index: 2;
}

.tbl_head01 th:nth-child(4),
.tbl_head01 td:nth-child(4) {
  position: sticky;
  left: 250px;
  background: #fff;
  z-index: 2;
}

.tbl_head01 th:nth-child(5),
.tbl_head01 td:nth-child(5) {
  position: sticky;
  left: 300px;
  background: #fff;
  z-index: 2;
}

.tbl_head01 th:nth-child(6),
.tbl_head01 td:nth-child(6) {
  position: sticky;
  left: 480px;
  background: #fff;
  z-index: 2;
}

/* 캠퍼스 열에 sticky 처리 */
.tbl_head01 th:nth-child(7),
.tbl_head01 td:nth-child(7) {
  position: sticky;
  left: 530px;
  background: #fff;
  z-index: 2;
}


.tbl_head01 .headd th:nth-child(1)::after,
.tbl_head01 .headd th:nth-child(2)::after,
.tbl_head01 .headd th:nth-child(3)::after,
.tbl_head01 .headd th:nth-child(4)::after,
.tbl_head01 .headd th:nth-child(5)::after,
.tbl_head01 .headd th:nth-child(6)::after
{
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    height: 110%;
    width: 2px;
    background-color: white; /* 경계선 색 */
}




/* 캠퍼스 열의 오른쪽 경계선 추가 (가상 요소로 처리) */
.tbl_head01 .headd th:nth-child(7)::after,
.tbl_head01 .headd td:nth-child(7)::after,
.tbl_head01 .connt th:nth-child(7)::after,
.tbl_head01 .connt td:nth-child(7)::after 
{
  content: '';
  position: absolute;
  top: 0;
  right: 0;
  height: 110%;
  width: 3px;
  background-color: #bdb8b8; /* 경계선 색 */
}

.sub-header th {
  z-index: 0 !important;
  background-color: #fff;
  border-bottom: none !important;
}

.headd th{
    border-bottom: none !important;
}

.headd th:nth-child(15){
    border-right: 2px solid white;
}

/* 2번째~7번째 열까지 계속 추가... */

</style>

<!-- 등급관리 시작 { -->
<div id="smb_my">
    <div id="smb_my_list">
        <!-- 최근 주문내역 시작 { -->
        <section id="smb_my_od">
            <form id="fsearch" name="fsearch" onsubmit="return fsearch_submit(this);" class="local_sch01 local_sch" method="get">
                <div style="display: flex;flex-direction:column;row-gap:10px;margin-bottom:10px;background:white;padding:10px 5px;">
                    <input type="hidden" id="branchIdx" name="branchIdx" value="<?=$branchIdx?>">
                    <input type="hidden" id="gender" name="gender" value="<?=$gender?>">
                    <div style="display: flex;align-items:center;gap:10px;">
                        <div>캠퍼스 : </div>
                        <div>
                            <?if($_SESSION['mb_profile'] == 'C40000001'){?>
                            <button type="button" class="btn-n <?if($branchIdx ==  '') echo "active";?>" onclick="viewCampus('')">전체</button>
                            <?}?>
                            <?
                                $bres=sql_query($bsql);
                                foreach($bres as $bs => $b){?>
                                <button type="button" class="btn-n <?if($branchIdx == $b['idx']) echo "active";?>" onclick="viewCampus('<?=$b['idx']?>')"><?=$b['branchName']?></button>
                                <?}
                            ?>
                        </div>
                    </div>
                    <?if($_SESSION['mb_profile'] != 'C40000003'){?>
                    <div style="display: flex;align-items:center;gap:10px;">
                        <div>성&nbsp;&nbsp;&nbsp;별 : </div>
                        <div>
                            <button type="button" class="btn-n <?if($gender == '') echo "active";?>" onclick="viewGender('')">전체</button>
                            <button type="button" class="btn-n <?if($gender == 'M') echo "active";?>" onclick="viewGender('M')">남</button>
                            <button type="button" class="btn-n <?if($gender == 'F') echo "active";?>" onclick="viewGender('F')">여</button>
                        </div>
                    </div>
                    <?}?>
                    <div style="display: flex;align-items:center;gap:10px;">
                        <div>종&nbsp;&nbsp;&nbsp;목 : </div>
                        
                            <div>
                                <button type="button" class="subje btn-n active" id="tall" onclick="viewTypeChange(event)">전체</button>
                                <button type="button" class="subje btn-n" id="core" onclick="viewTypeChange(event)">배근력</button>
                                <button type="button" class="subje btn-n" id="m10m" onclick="viewTypeChange(event)">10m왕복</button>
                                <button type="button" class="subje btn-n" id="medicine" onclick="viewTypeChange(event)">메디신</button>
                                <button type="button" class="subje btn-n" id="leftGul" onclick="viewTypeChange(event)">좌전굴</button>
                                <button type="button" class="subje btn-n" id="stand" onclick="viewTypeChange(event)">제멀</button>
                                <button type="button" class="subje btn-n" id="m20mBu" onclick="viewTypeChange(event)">20m부저</button>
                                <button type="button" class="subje btn-n" id="situp" onclick="viewTypeChange(event)">윗몸</button>
                                <button type="button" class="subje btn-n" id="sergent" onclick="viewTypeChange(event)">서전트</button>
                            </div>
                        
                    </div>
                </div>
            </form>
            <?if($scnt['cnt'] > 0){?>
            <div class="tbl_wrap border-tb scroll-y" style="overflow-x: auto;max-height:450px;">
                <table class="tbl_head01" style="width: auto;">
                    <tbody>
                    <tr class="headd">
                        <th style="top:0;z-index:15;min-width:100px;width:100px;background:#e4e4e4;" rowspan="2">날짜</th>
                        <th style="top:0;z-index:15;min-width:50px;width:50px;background:#e4e4e4;" rowspan="2">순위</th>
                        <th style="top:0;z-index:15;min-width:100px;width:100px;background:#e4e4e4;" rowspan="2">이름</th>
                        <th style="top:0;z-index:15;min-width:50px;width:50px;background:#e4e4e4;" rowspan="2">성</th>
                        <th style="top:0;z-index:15;min-width:180px;width:180px;background:#e4e4e4;" rowspan="2">학교</th>
                        <th style="top:0;z-index:15;min-width:50px;width:50px;background:#e4e4e4;" rowspan="2">학년</th>
                        <th style="top:0;z-index:15;min-width:100px;width:100px;background:#e4e4e4;" rowspan="2">캠퍼스</th>
                        <th style="position:sticky;top:0;z-index:13;min-width:100px;width:100px;background:#e4e4e4;" class='core' colspan="2">배근력</th>
                        <th style="position:sticky;top:0;z-index:13;min-width:100px;width:100px;background:#e4e4e4;" class='m10m' colspan="2">10m왕복</th>
                        <th style="position:sticky;top:0;z-index:13;min-width:100px;width:100px;background:#e4e4e4;" class='medicine' colspan="2">메디신</th>
                        <th style="position:sticky;top:0;z-index:13;min-width:100px;width:100px;background:#e4e4e4;" class='leftGul' colspan="2">좌전굴</th>
                        <th style="position:sticky;top:0;z-index:13;min-width:100px;width:100px;background:#e4e4e4;" class='stand' colspan="2">제멀</th>
                        <th style="position:sticky;top:0;z-index:13;min-width:100px;width:100px;background:#e4e4e4;" class='m20mBu' colspan="2">20m부저</th>
                        <th style="position:sticky;top:0;z-index:13;min-width:100px;width:100px;background:#e4e4e4;" class='situp' colspan="2">윗몸</th>
                        <th style="position:sticky;top:0;z-index:13;min-width:100px;width:100px;background:#e4e4e4;" class='sergent' colspan="2">서전트</th>
                        <th style="position:sticky;top:0;z-index:13;min-width:100px;width:100px;background:#e4e4e4;border-right:2px solid white;" rowspan="2">총점</th>
                        <th style="position:sticky;top:0;z-index:13;min-width:100px;width:100px;background:#e4e4e4;" rowspan="2">평균</th>
                    </tr>
                    <tr class="sub-header">
                        <th style="position:sticky;top:38.5px;min-width:100px;background:#e4e4e4;border-right:1px solid white" class='core'>기록</th>
                        <th style="position:sticky;top:38.5px;min-width:100px;background:#e4e4e4;border-right:1px solid white" class='core'>점수</th>
                        <th style="position:sticky;top:38.5px;min-width:100px;background:#e4e4e4;border-right:1px solid white" class='m10m'>기록</th>
                        <th style="position:sticky;top:38.5px;min-width:100px;background:#e4e4e4;border-right:1px solid white" class='m10m'>점수</th>
                        <th style="position:sticky;top:38.5px;min-width:100px;background:#e4e4e4;border-right:1px solid white" class='medicine'>기록</th>
                        <th style="position:sticky;top:38.5px;min-width:100px;background:#e4e4e4;border-right:1px solid white" class='medicine'>점수</th>
                        <th style="position:sticky;top:38.5px;min-width:100px;background:#e4e4e4;border-right:1px solid white" class='leftGul'>기록</th>
                        <th style="position:sticky;top:38.5px;min-width:100px;background:#e4e4e4;border-right:1px solid white" class='leftGul'>점수</th>
                        <th style="position:sticky;top:38.5px;min-width:100px;background:#e4e4e4;border-right:1px solid white" class='stand'>기록</th>
                        <th style="position:sticky;top:38.5px;min-width:100px;background:#e4e4e4;border-right:1px solid white" class='stand'>점수</th>
                        <th style="position:sticky;top:38.5px;min-width:100px;background:#e4e4e4;border-right:1px solid white" class='m20mBu'>기록</th>
                        <th style="position:sticky;top:38.5px;min-width:100px;background:#e4e4e4;border-right:1px solid white" class='m20mBu'>점수</th>
                        <th style="position:sticky;top:38.5px;min-width:100px;background:#e4e4e4;border-right:1px solid white" class='situp'>기록</th>
                        <th style="position:sticky;top:38.5px;min-width:100px;background:#e4e4e4;border-right:1px solid white" class='situp'>점수</th>
                        <th style="position:sticky;top:38.5px;min-width:100px;background:#e4e4e4;border-right:1px solid white" class='sergent'>기록</th>
                        <th style="position:sticky;top:38.5px;min-width:100px;background:#e4e4e4;border-right:2px solid white" class='sergent'>점수</th>
                    </tr>
                        <?
                        $res = sql_query($sql);
                        foreach($res as $rs => $s){
                            if($s['mb_sex'] == "M"){
                                $g = "남";
                            } else {
                                $g = "여";
                            }
                            ?>
                            <tr class="connt">
                                <td style="max-width:100px;text-align:center;"><?=$s['date']?></td>
                                <td style="max-width:50px;text-align:center;"><?=$s['sRank']?></td>
                                <td style="max-width:100px;text-align:center;"><?=$s['mb_name']?></td>
                                <td style="max-width:50px;text-align:center;"><?=$g?></td>
                                <td style="max-width:180px;text-align:center;"><?=$s['mb_1']?></td>
                                <td style="max-width:50px;text-align:center;"><?=$s['grade']?></td>
                                <td style="max-width:100px;text-align:center;"><?=$s['branchName']?></td>
                                <td style="width:150px;text-align:center;" class="core core_Rank"><?=$s['core_Rank']?></td>
                                <td style="width:150px;text-align:center;" class="core core_score"><?=$s['core_score']?></td>
                                <td style="width:150px;text-align:center;" class="m10m m10m_Rank"><?=$s['10m_Rank']?></td>
                                <td style="width:150px;text-align:center;" class="m10m m10m_score"><?=$s['10m_score']?></td>
                                <td style="width:150px;text-align:center;" class="medicine medicine_Rank"><?=$s['medicine_Rank']?></td>
                                <td style="width:150px;text-align:center;" class="medicine medicine_score"><?=$s['medicine_score']?></td>
                                <td style="width:150px;text-align:center;" class="leftGul leftGul_Rank"><?=$s['left_Rank']?></td>
                                <td style="width:150px;text-align:center;" class="leftGul leftGul_score"><?=$s['left_score']?></td>
                                <td style="width:150px;text-align:center;" class="stand stand_Rank"><?=$s['stand_Rank']?></td>
                                <td style="width:150px;text-align:center;" class="stand stand_score"><?=$s['stand_score']?></td>
                                <td style="width:150px;text-align:center;" class="m20mBu m20mBu_Rank"><?=$s['20mBu_Rank']?></td>
                                <td style="width:150px;text-align:center;" class="m20mBu m20mBu_score"><?=$s['20mBu_score']?></td>
                                <td style="width:150px;text-align:center;" class="situp situp_Rank"><?=$s['situp_Rank']?></td>
                                <td style="width:150px;text-align:center;" class="situp situp_score"><?=$s['situp_score']?></td>
                                <td style="width:150px;text-align:center;" class="sergent sergent_Rank"><?=$s['surgent_Rank']?></td>
                                <td style="width:150px;text-align:center;" class="sergent sergent_score"><?=$s['surgent_score']?></td>
                                <td style="width:150px;text-align:center;" class="totals"><?=$s['total_Rank']?></td>
                                <td style="width:150px;text-align:center;" class="avg"><?=$s['total_Rev']?></td>
                            </tr>
                        <?}?>
                    </tbody>
                </table>
            </div>
            <?} else {?>
                <div class="tbl_wrap border-tb">
                <table class="tbl_head01">
                    <tbody>
                        <tr>
                            <td style="text-align:center;">검색 결과가 없습니다.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <?}?>
        </section>
        <!-- } 최근 주문내역 끝 -->
    </div>
</div>

<script>
    
    function viewTypeChange(e){
        let id = e.currentTarget.id;
        let classes = e.currentTarget.classList;
        let cnt = $(".subje.active").length;
        if(id == 'tall'){
            if(classes.contains('active')){

            } else {
                document.querySelectorAll('.subje').forEach((el,i,arr)=>{
                    if(el.id != id){
                        el.classList.remove('active');
                    } else{
                        el.classList.add('active');
                    }
                });
            }
            
        } else {
            $("#tall").removeClass('active');
            if(classes.contains('active')){
                if(cnt == 1){
                    document.querySelectorAll('.subje').forEach((el,i,arr)=>{
                        if(el.id != 'tall'){
                            el.classList.remove('active');
                        } else{
                            el.classList.add('active');
                        }
                    });
                } else {
                    classes.remove('active');
                }
            } else {
                $(`#${id}`).addClass('active');
                if($(".subje.active").length == $(".subje").length -1){
                    document.querySelectorAll('.subje').forEach((el,i,arr)=>{
                        if(el.id != 'tall'){
                            el.classList.remove('active');
                        } else{
                            el.classList.add('active');
                        }
                    });
                }
            }
        }
        resetView();
    }

    function fsearch_submit(e) {
    }

    function resetView(){
        var arrs = [];
        var arrs2 = [];
        if($("#tall").hasClass('active')){
            document.querySelectorAll('.subje').forEach((el,i,arr)=>{
                arrs.push(el.id);
            });
            for(let i = 0; i < arrs.length; i++){
                document.querySelectorAll(`.${arrs[i]}`).forEach((el,i,arr)=>{
                    el.style.display = '';
                });
            }
        } else {
            document.querySelectorAll('.subje').forEach((el,i,arr)=>{
                if(el.classList.contains('active')){
                    arrs.push(el.id);
                } else {
                    arrs2.push(el.id);
                }
            });
    
            for(let i = 0; i < arrs2.length; i++){
                document.querySelectorAll(`.${arrs2[i]}`).forEach((el,i,arr)=>{
                    el.style.display = 'none';
                });
            }
    
            for(let i = 0; i < arrs.length; i++){
                document.querySelectorAll(`.${arrs[i]}`).forEach((el,i,arr)=>{
                    el.style.display = '';
                });
            }
        }
        
        document.querySelectorAll(".avg").forEach((el,i,arr)=>{
            let total = 0;
            let cnt = 0;
            for(let j=7; j<el.parentElement.children.length - 2;j++){
                if(arrs.some(cls => el.parentElement.children[j].classList.contains(cls)) && el.parentElement.children[j].classList[1] == `${el.parentElement.children[j].classList[0]}_score`){
                    if(el.parentElement.children[j].textContent != ''){
                        total+=Number(el.parentElement.children[j].textContent);
                        cnt++;
                    }
                }
            }
            el.previousElementSibling.textContent = total;
            if(cnt == 0){
                el.textContent = 0;
            } else {
                el.textContent = Math.round((total/cnt)*100)/100;
            }
        });
    }

    function viewGender(e){
        $("#gender").val(e);
        $("#fsearch").submit();
    }

    function viewCampus(e){
        $("#branchIdx").val(e);
        $("#fsearch").submit();
    }
</script>
<!-- } 마이페이지 끝 -->

<?php
include_once("./_tail.php");
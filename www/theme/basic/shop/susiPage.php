<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

$g5['title'] = '합격자정보';
include_once('./_head.php');

$sql_add = " 1=1 ";

if($gender){
    $sql_add .= " AND gender = '{$gender}' ";
}

if($college){
    $sql_add .= " AND college like '%{$college}%' ";
}

if($subject){
    $sql_add .= " AND `subject` like '%{$subject}%' ";
}

if($susi){
    $sql_add .= " AND susi like '%{$susi}%' ";
}

$cnt = sql_fetch("SELECT COUNT(*) as 'cnt' FROM g5_suhab WHERE $sql_add");

$sql = "SELECT * FROM g5_suhab WHERE $sql_add";

?>
<style>
    select{
        height : auto !important;
    }
    tbody .connt td{
        border-bottom:1px solid #d3d3d3;
    }
    .sub-Jongmok{
        padding: 5px 3px;
        min-width: 80px;
    }
    td div:not(:last-child){
        border-right:1px solid #e4e4e4;
    }
    .fSilgi{
        border-left: 1px solid #e4e4e4;
    }

    td.tSilgi:not(:has(~ td.tSilgi)) {
    border-right: 1px solid #e4e4e4; /* 원하는 테두리 스타일을 적용하세요 */
    }
    
</style>
<!-- 등급관리 시작 { -->
<div id="smb_my">
    <div id="smb_my_list">
        <!-- 최근 주문내역 시작 { -->
        <section id="smb_my_od" style="margin:0;">
            <form id="fsearch" name="fsearch" onsubmit="return fsearch_submit(this);" class="local_sch01 local_sch" method="get">
                <input type="hidden" id="gender" name="gender" value="<?=$gender?>">
                <div style="display: flex;flex-direction:column;row-gap:10px;margin-bottom:10px;background:white;padding:10px 5px;">
                    <div style="display: flex;align-items:center;gap:10px;">
                        <div style="font-weight:800;">성&nbsp;&nbsp;&nbsp;별 : </div>
                        <div>
                            <button type="button" class="btn-n <?if($gender == '') echo "active";?>" onclick="viewGender('')">전체</button>
                            <button type="button" class="btn-n <?if($gender == '남') echo "active";?>" onclick="viewGender('남')">남</button>
                            <button type="button" class="btn-n <?if($gender == '여') echo "active";?>" onclick="viewGender('여')">여</button>
                        </div>
                    </div>
                    
                    <div style="display: flex;align-items:center;gap:10px;">
                        <div style="font-weight:800;">검&nbsp;&nbsp;&nbsp;색 : </div>
                        <div style="display: flex;justify-content:center;align-items:center;gap:10px;min-width:500px;">
                            <td style="padding:10px;"><input type="text" name="college" id="college" placeholder="대학명" class="frm_input textSearch" style="width: 100%;padding:0 10px;" value="<?=$college?>"></td>
                            <td style="padding:10px;"><input type="text" name="subject" id="subject" placeholder="학과명" class="frm_input textSearch" style="width: 100%;padding:0 10px;" value="<?=$subject?>"></td>
                            <td style="padding:10px;"><input type="submit" class="search-btn" id="searchEnter" value="" style="width:50px !important;"></td>
                        </div>
                    </div>

                    <div style="display: flex;align-items:center;gap:10px;">
                        <div style="font-weight:800;">전&nbsp;&nbsp;&nbsp;형 : </div>
                        <div style="display: flex;justify-content:center;align-items:center;gap:10px;min-width:500px;">
                            <td style="padding:10px;"><input type="text" name="susi" id="susi" placeholder="전형" class="frm_input textSearch" style="width: 100%;padding:0 10px;" value="<?=$susi?>"></td>
                            <td style="padding:10px;"></td>
                            <td style="padding:10px;"><input type="submit" class="search-btn" id="searchEnter" value="" style="width:23px !important;"></td>
                        </div>
                    </div>
                </div>
            </form>
            <?if($cnt['cnt'] > 0){?>
            <div class="tbl_wrap border-tb scroll-y" style="overflow-x: auto;max-height:73vh;">
                <table class="tbl_head01" style="width: auto;border-collapse: separate !important;border-spacing: 0 !important;">
                    <thead>
                        <tr class="headd">
                            <th style="position:sticky;top:0;z-index:13;min-width:50px;width:50px;background:rgba(227, 244, 248);border-right:1px solid white;" rowspan="2">성별</th>
                            <th style="position:sticky;top:0;z-index:13;min-width:100px;width:100px;background:rgba(227, 244, 248);border-right:1px solid white;" rowspan="2">지점</th>
                            <th style="position:sticky;top:0;z-index:13;min-width:100px;width:100px;background:rgba(227, 244, 248);border-right:1px solid white;" rowspan="2">이름</th>
                            <th style="position:sticky;top:0;z-index:13;min-width:100px;width:100px;background:rgba(227, 244, 248);border-right:1px solid white;" rowspan="2">학교</th>
                            <th style="position:sticky;top:0;z-index:13;min-width:150px;width:150px;background:rgba(227, 244, 248);border-right:1px solid white;" rowspan="2">대학</th>
                            <th style="position:sticky;top:0;z-index:13;min-width:200px;width:200px;background:rgba(227, 244, 248);border-right:1px solid white;" rowspan="2">학과</th>
                            <th style="position:sticky;top:0;z-index:13;min-width:150px;width:150px;background:rgba(227, 244, 248);border-right:1px solid white;border-right:1px solid #e4e4e4;" rowspan="2">전형</th>
                            <th style="position:sticky;top:0;z-index:13;min-width:50px;width:50px;background:rgba(227, 244, 248);border-right:1px solid white;border-right:1px solid #e4e4e4;"colspan="5">점수</th>
                            <th style="position:sticky;top:0;z-index:13;min-width:160px;width:160px;background:rgba(227, 244, 248);border-right:1px solid white;border-right:1px solid #e4e4e4;"colspan="2">합격</th>
                            <th style="position:sticky;top:0;z-index:13;min-width:100px;width:100px;background:rgba(227, 244, 248);" colspan="18">실기</th>
                        </tr>
                    <tr class="sub-header">
                        
                        <th style="position:sticky;top:45px;min-width:70px;background:rgba(227, 244, 248);border-right:1px solid white;border-bottom:1px solid #d3d3d3;">등급</th>
                        <th style="position:sticky;top:45px;min-width:80px;background:rgba(227, 244, 248);border-right:1px solid white;border-bottom:1px solid #d3d3d3;">내신</th>
                        <th style="position:sticky;top:45px;min-width:80px;background:rgba(227, 244, 248);border-right:1px solid white;border-bottom:1px solid #d3d3d3;">실기</th>
                        <th style="position:sticky;top:45px;min-width:80px;background:rgba(227, 244, 248);border-right:1px solid white;border-bottom:1px solid #d3d3d3;">기타</th>
                        <th style="position:sticky;top:45px;min-width:80px;background:rgba(227, 244, 248);border-right:1px solid white;border-bottom:1px solid #d3d3d3;border-right:1px solid #e4e4e4;">총점</th>

                        <th style="position:sticky;top:45px;min-width:80px;background:rgba(227, 244, 248);border-right:1px solid white;border-bottom:1px solid #d3d3d3;">최초</th>
                        <th style="position:sticky;top:45px;min-width:80px;background:rgba(227, 244, 248);border-right:1px solid white;border-bottom:1px solid #d3d3d3;border-right:1px solid #e4e4e4;">최종</th>

                        <th style="position:sticky;top:45px;min-width:100px;background:rgba(227, 244, 248);border-right:1px solid white;border-bottom: 1px solid #d3d3d3;">종목</th>
                        <th style="position:sticky;top:45px;min-width:100px;background:rgba(227, 244, 248);border-right:1px solid white;border-bottom: 1px solid #d3d3d3;">기록</th>
                        <th style="position:sticky;top:45px;min-width:100px;background:rgba(227, 244, 248);border-right:1px solid white;border-bottom: 1px solid #d3d3d3;">점수</th>

                        <th style="position:sticky;top:45px;min-width:100px;background:rgba(227, 244, 248);border-right:1px solid white;border-bottom: 1px solid #d3d3d3;">종목</th>
                        <th style="position:sticky;top:45px;min-width:100px;background:rgba(227, 244, 248);border-right:1px solid white;border-bottom: 1px solid #d3d3d3;">기록</th>
                        <th style="position:sticky;top:45px;min-width:100px;background:rgba(227, 244, 248);border-right:1px solid white;border-bottom: 1px solid #d3d3d3;">점수</th>

                        <th style="position:sticky;top:45px;min-width:100px;background:rgba(227, 244, 248);border-right:1px solid white;border-bottom: 1px solid #d3d3d3;">종목</th>
                        <th style="position:sticky;top:45px;min-width:100px;background:rgba(227, 244, 248);border-right:1px solid white;border-bottom: 1px solid #d3d3d3;">기록</th>
                        <th style="position:sticky;top:45px;min-width:100px;background:rgba(227, 244, 248);border-right:1px solid white;border-bottom: 1px solid #d3d3d3;">점수</th>

                        <th style="position:sticky;top:45px;min-width:100px;background:rgba(227, 244, 248);border-right:1px solid white;border-bottom: 1px solid #d3d3d3;">종목</th>
                        <th style="position:sticky;top:45px;min-width:100px;background:rgba(227, 244, 248);border-right:1px solid white;border-bottom: 1px solid #d3d3d3;">기록</th>
                        <th style="position:sticky;top:45px;min-width:100px;background:rgba(227, 244, 248);border-right:1px solid white;border-bottom: 1px solid #d3d3d3;">점수</th>

                        <th style="position:sticky;top:45px;min-width:100px;background:rgba(227, 244, 248);border-right:1px solid white;border-bottom: 1px solid #d3d3d3;">종목</th>
                        <th style="position:sticky;top:45px;min-width:100px;background:rgba(227, 244, 248);border-right:1px solid white;border-bottom: 1px solid #d3d3d3;">기록</th>
                        <th style="position:sticky;top:45px;min-width:100px;background:rgba(227, 244, 248);border-right:1px solid white;border-bottom: 1px solid #d3d3d3;">점수</th>

                        <th style="position:sticky;top:45px;min-width:100px;background:rgba(227, 244, 248);border-right:1px solid white;border-bottom: 1px solid #d3d3d3;">종목</th>
                        <th style="position:sticky;top:45px;min-width:100px;background:rgba(227, 244, 248);border-right:1px solid white;border-bottom: 1px solid #d3d3d3;">기록</th>
                        <th style="position:sticky;top:45px;min-width:100px;background:rgba(227, 244, 248);border-right:1px solid white;border-bottom: 1px solid #d3d3d3;">점수</th>
                    </tr>
                </thead>
                    <tbody>
                    <?
                        $res = sql_query($sql);
                        foreach($res as $rs => $s){
                            if($s['gender'] == "남"){
                                $g = "남";
                            } else {
                                $g = "여";
                            }
                            ?>
                            <tr class="connt">
                                <td style="max-width:50px;text-align:center;"><?=$g?></td>
                                <td style="max-width:100px;text-align:center;"><?=$s['branch']?></td>
                                <td style="max-width:50px;text-align:center;"><?=$s['studentNm']?></td>
                                <td style="max-width:50px;text-align:center;"><?=$s['school']?></td>
                                <td style="max-width:50px;text-align:center;"><?=$s['college']?></td>
                                <td style="max-width:200px;text-align:center;"><?=$s['subject']?></td>
                                <td style="max-width:150px;text-align:center;border-right:1px solid #e4e4e4;"><?=$s['susi']?></td>
                                <td style="width:50px;text-align:center;"><?=$s['grade']?></td>
                                <td style="width:50px;text-align:center;"><?=$s['naesin']?></td>
                                <td style="width:50px;text-align:center;"><?=$s['silgi']?></td>
                                <td style="width:50px;text-align:center;"><?=$s['other']?></td>
                                <td style="width:50px;text-align:center;border-right:1px solid #e4e4e4;"><?=$s['total']?></td>
                                <td style="width:50px;text-align:center;"><?=$s['firstA']?></td>
                                <td style="width:100px;text-align:center;border-right:1px solid #e4e4e4;"><?=$s['lastA']?></td>

                                <td style="width:200px;text-align:center;"><?=$s['sil_type1']?></td>
                                <td style="width:80px;text-align:center;"><?=$s['sil_record1']?></td>
                                <td style="width:80px;text-align:center;border-right:1px solid #e4e4e4;"><?=$s['sil_score1']?></td>
                                
                                <td style="width:100px;text-align:center;"><?=$s['sil_type2']?></td>
                                <td style="width:100px;text-align:center;"><?=$s['sil_record2']?></td>
                                <td style="width:100px;text-align:center;border-right:1px solid #e4e4e4;"><?=$s['sil_score2']?></td>

                                <td style="width:100px;text-align:center;"><?=$s['sil_type3']?></td>
                                <td style="width:100px;text-align:center;"><?=$s['sil_record3']?></td>
                                <td style="width:100px;text-align:center;border-right:1px solid #e4e4e4;"><?=$s['sil_score3']?></td>

                                <td style="width:100px;text-align:center;"><?=$s['sil_type4']?></td>
                                <td style="width:100px;text-align:center;"><?=$s['sil_record4']?></td>
                                <td style="width:100px;text-align:center;border-right:1px solid #e4e4e4;"><?=$s['sil_score4']?></td>

                                <td style="width:100px;text-align:center;"><?=$s['sil_type5']?></td>
                                <td style="width:100px;text-align:center;"><?=$s['sil_record5']?></td>
                                <td style="width:100px;text-align:center;border-right:1px solid #e4e4e4;"><?=$s['sil_score5']?></td>

                                <td style="width:100px;text-align:center;"><?=$s['sil_type6']?></td>
                                <td style="width:100px;text-align:center;"><?=$s['sil_record6']?></td>
                                <td style="width:100px;text-align:center;"><?=$s['sil_score6']?></td>

                                
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

    function viewJuSu(e){
        $("#college").val('');
        $("#subject").val('');
        $("#coltype").val(e);
        $("#fsearch").submit();
    }

    $("#selectcollege").on('change',function(){
        $("#college").val($(this).val());
        $("#subject").val('');
        $("#fsearch").submit();
    });

    $("#selectsubject").on('change',function(){
        $("#subject").val($(this).val());
        $("#fsearch").submit();
    });
</script>
<!-- } 마이페이지 끝 -->

<?php
include_once("./_tail.php");
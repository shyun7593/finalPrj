<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

$g5['title'] = '합격자정보';
include_once('./_head.php');

$sql_add = " 1=1 ";

if($gender){
    $sql_add .= " AND gender = '{$gender}' ";
}

if($college){
    $sql_add .= " AND college = '{$college}' ";
}

if($susi){
    $sql_add .= " AND susi = '{$susi}' ";
}

if($subject){
    $s = sql_fetch("SELECT `subject` FROM g5_suhab WHERE college = '{$college}' GROUP BY `subject` limit 1")['subject'];
    $sql_add .= " AND `subject` = '{$s}' ";
}

if(!$orderType){
    $orderd = " ORDER BY total desc";
}

$cnt = sql_fetch("SELECT COUNT(*) as 'cnt' FROM g5_suhab WHERE $sql_add");

$sql = "SELECT * FROM g5_suhab WHERE $sql_add $orderd";

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
                <input type="hidden" id="college" name="college" value="<?=$college?>">
                <input type="hidden" id="subject" name="subject" value="<?=$subject?>">
                <input type="hidden" id="susi" name="susi" value="<?=$susi?>">
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
                        <div style="font-weight:800;">학&nbsp;&nbsp;&nbsp;교 : </div>
                        <div>
                            <select name="selectcollege" id="selectcollege" class="frm_input">
                                <option value="">선택하세요.</option>
                                <?
                                    $passRes = sql_query("SELECT college FROM g5_suhab GROUP BY college ORDER BY college");
                                    foreach($passRes as $pr => $p){?>
                                        <option value="<?=$p['college']?>" <?if($p['college'] == $college) echo 'selected';?>><?=$p['college']?></option>
                                <? }
                                ?>
                            </select>
                        </div>
                        <div style="font-weight:800;">학&nbsp;&nbsp;&nbsp;과 : </div>
                        <div>
                            <select name="selectsubject" id="selectsubject" class="frm_input">
                                
                                <?if($college){?>
                                    <option value="">선택하세요.</option>
                                    <?$subRes = sql_query("SELECT `subject` FROM g5_suhab WHERE college = '{$college}' GROUP BY `subject`");
                                    foreach($subRes as $sr => $s){?>
                                        <option value="<?=$s['subject']?>" <?if($s['subject'] == $subject) {echo 'selected';}?>><?=$s['subject']?></option>
                                <?  }
                                }else {?>
                                    <option>대학을 먼저 선택해주세요.</option>
                                <?}?>
                            </select>
                        </div>
                        <div style="font-weight:800;">전&nbsp;&nbsp;&nbsp;형 : </div>
                        <div>
                            <select name="selectsusi" id="selectsusi" class="frm_input">
                                <option value="">선택하세요.</option>
                                <?
                                    $su_add = " 1=1 ";
                                    if($college){
                                        $su_add .= " AND college = '{$college}' ";
                                    }

                                    if($subject){
                                        $su_add .= " AND `subject` = '{$subject}' ";
                                    }
                                    $subRes = sql_query("SELECT susi FROM g5_suhab WHERE $su_add GROUP BY susi");
                                    foreach($subRes as $sr => $s){?>
                                        <option value="<?=$s['susi']?>" <?if($s['susi'] == $susi) {echo 'selected';}?>><?=$s['susi']?></option>
                                <?  }?>
                            </select>
                        </div>
                    </div>
                    <div style="display: flex;align-items:center;gap:10px;">
                        <div style="font-weight:800;">필&nbsp;&nbsp;&nbsp;터 : </div>
                        <div>
                            <button type="button" class="subje btn-n" id="sScores" onclick="viewTypeChange(event)">접기</button>
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
                            <!-- <th style="position:sticky;top:0;z-index:13;min-width:150px;width:150px;background:rgba(227, 244, 248);border-right:1px solid white;" rowspan="2">대학</th>
                            <th style="position:sticky;top:0;z-index:13;min-width:200px;width:200px;background:rgba(227, 244, 248);border-right:1px solid white;" rowspan="2">학과</th>
                            <th style="position:sticky;top:0;z-index:13;min-width:150px;width:150px;background:rgba(227, 244, 248);border-right:1px solid white;border-right:1px solid #e4e4e4;" rowspan="2">전형</th> -->
                            <th class="sScores" style="position:sticky;top:0;z-index:13;min-width:50px;width:50px;background:rgba(227, 244, 248);border-right:1px solid white;border-right:1px solid #e4e4e4;"colspan="5">점수</th>
                            <th class="sScores" style="position:sticky;top:0;z-index:13;min-width:160px;width:160px;background:rgba(227, 244, 248);border-right:1px solid white;border-right:1px solid #e4e4e4;"colspan="2">합격</th>
                            <th style="position:sticky;top:0;z-index:13;min-width:100px;width:100px;background:rgba(227, 244, 248);" colspan="18">실기</th>
                        </tr>
                    <tr class="sub-header">
                        
                        <th class="sScores" style="position:sticky;top:45px;min-width:70px;background:rgba(227, 244, 248);border-right:1px solid white;border-bottom:1px solid #d3d3d3;">등급</th>
                        <th class="sScores" style="position:sticky;top:45px;min-width:80px;background:rgba(227, 244, 248);border-right:1px solid white;border-bottom:1px solid #d3d3d3;">내신</th>
                        <th class="sScores" style="position:sticky;top:45px;min-width:80px;background:rgba(227, 244, 248);border-right:1px solid white;border-bottom:1px solid #d3d3d3;">실기</th>
                        <th class="sScores" style="position:sticky;top:45px;min-width:80px;background:rgba(227, 244, 248);border-right:1px solid white;border-bottom:1px solid #d3d3d3;">기타</th>
                        <th class="sScores" style="position:sticky;top:45px;min-width:80px;background:rgba(227, 244, 248);border-right:1px solid white;border-bottom:1px solid #d3d3d3;border-right:1px solid #e4e4e4;">총점</th>

                        <th class="sScores" style="position:sticky;top:45px;min-width:80px;background:rgba(227, 244, 248);border-right:1px solid white;border-bottom:1px solid #d3d3d3;">최초</th>
                        <th class="sScores" style="position:sticky;top:45px;min-width:80px;background:rgba(227, 244, 248);border-right:1px solid white;border-bottom:1px solid #d3d3d3;border-right:1px solid #e4e4e4;">최종</th>

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
                                <td style="max-width:50px;text-align:center;border-right:1px solid #e4e4e4;"><?=$s['school']?></td>
                                <!-- <td style="max-width:50px;text-align:center;"><?=$s['college']?></td>
                                <td style="max-width:200px;text-align:center;"><?=$s['subject']?></td>
                                <td style="max-width:150px;text-align:center;border-right:1px solid #e4e4e4;"><?=$s['susi']?></td> -->
                                <td class="sScores" style="width:50px;text-align:center;"><?=$s['grade']?></td>
                                <td class="sScores" style="width:50px;text-align:center;"><?=$s['naesin']?></td>
                                <td class="sScores" style="width:50px;text-align:center;"><?=$s['silgi']?></td>
                                <td class="sScores" style="width:50px;text-align:center;"><?=$s['other']?></td>
                                <td class="sScores" style="width:50px;text-align:center;border-right:1px solid #e4e4e4;"><?=$s['total']?></td>
                                <td class="sScores" style="width:50px;text-align:center;"><?=$s['firstA']?></td>
                                <td class="sScores" style="width:100px;text-align:center;border-right:1px solid #e4e4e4;"><?=$s['lastA']?></td>

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
    function fsearch_submit(e) {
    }

    function viewGender(e){
        $("#gender").val(e);
        $("#fsearch").submit();
    }

    // function viewJuSu(e){
    //     $("#college").val('');
    //     $("#subject").val('');
    //     $("#coltype").val(e);
    //     $("#fsearch").submit();
    // }

    $("#selectcollege").on('change',function(){
        $("#college").val($(this).val());
        $("#subject").val('');
        $("#susi").val('');
        $("#fsearch").submit();
    });

    $("#selectsubject").on('change',function(){
        $("#subject").val($(this).val());
        $("#fsearch").submit();
    });

    $("#selectsusi").on('change',function(){
        $("#susi").val($(this).val());
        $("#fsearch").submit();
    });

    function viewTypeChange(e){
        e.currentTarget.classList.toggle('active');
        let id = e.currentTarget.id;
        let classes = e.currentTarget.classList;
        if(id == 'sScores'){
            if(classes.contains('active')){
                document.querySelectorAll('.sScores').forEach((el,i,arr)=>{
                    el.style.display='none';
                });
            } else {
                document.querySelectorAll('.sScores').forEach((el,i,arr)=>{
                    el.style.display='';
                });
            }
            
        }
    }
</script>
<!-- } 마이페이지 끝 -->

<?php
include_once("./_tail.php");
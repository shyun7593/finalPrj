<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

$g5['title'] = '대학정보';
include_once('./_head.php');

$sql_add = " 1=1 ";

switch($stype){
    case 'jungsi':
        $sql_add .= " AND gcs.jungsiIdx is not null ";
        break;
    case 'susi':
        $sql_add .= " AND gcs.susiIdx is not null ";
        break;
    default:
        break;
}

if($textCol){
    $sql_add .= " AND  gc.cName like '%{$textCol}%' ";
}

if($textSub){
    $sql_add .= " AND gcs.sName like '%{$textSub}%' ";
}

$cnt = sql_fetch("SELECT 
    COUNT(*) as 'cnt'
FROM g5_college gc JOIN g5_college_subject gcs on gcs.collegeIdx = gc.cIdx
WHERE {$sql_add}");

$res = sql_query("select 
                    gc.*,
                    gcs.*, 
                    CASE
                        WHEN gcs.susiIdx is null OR gcs.susiIdx = '' THEN 0
                        ELSE 1
                    END as 'su',
                    CASE
                        WHEN gcs.jungsiIdx is null OR gcs.jungsiIdx = '' THEN 0
                        ELSE 1
                    END as 'ju',
                    gac.idx as 'myIdx',
                    gcc.codeName as 'areaName',
                    gcc2.codeName as 'gun'
                from g5_college gc
                    LEFT JOIN g5_college_subject gcs on
                        gc.cIdx = gcs.collegeIdx
                    LEFT JOIN g5_add_college gac on
                        gac.subIdx = gcs.sIdx AND gac.memId = '{$_SESSION['ss_mb_id']}' AND gac.memId = gac.regId
                    JOIN g5_cmmn_code gcc on
                        gcc.code = gcs.areaCode
                    LEFT JOIN g5_cmmn_code gcc2 on
                        gcc2.code = gcs.cmmn1
                where 
                    {$sql_add}
                ORDER BY gc.cName, gcs.sName");

$query_string = http_build_query(array(
    'stype' => $_GET['stype'],
    'text' => $_GET['text'],
));
?>
<style>
    #collegePopup{
        width: 1200px !important;
    }
    #collegePopup table{
        border-collapse: collapse;
        text-align: center;
        width: 100%;
        table-layout: fixed;
    }

    #collegePopup table th,#collegePopup table td{
        padding:4px 0;
    }
    #collegePopup table thead th{
        background-color: #334d63;
        color:white;
    }

    #collegePopup p.p_title{
        font-size: 1.2em;
        font-weight: 800;
        padding : 10px 0 5px 0;
        display: flex;
        align-items: baseline;
        gap:5px;
    }

    #collegePopup p:not(.p_title){
        font-size: 1.1em;
        margin-bottom: 5px;
    }
    #collegePopup p.p_title::before{
        content:"\ea1c";
        font-family: "xeicon";
    }

    #collegePopup .lis::before{
        content:"\eb0d";
        font-family: "xeicon";
        font-size: 10px;
        margin-right: 5px;
    }

    #collegePopup .inner_Cont{
        display:flex;
        gap:25px;
        margin:5px;
    }

    #collegePopup .subject_trans div:not(:first-child){
        margin-top:15px;
    }

    #collegePopup .subject_trans p{
        margin-bottom: 10px;
    }
    i {
        font-size:1.5em;
    }

    .myicon{
        display: none;
    }
    .myicon.view{
        display: block;
    }
    .no-view{
        display: none;
    }
    .under_line:hover{
        text-decoration: underline;
        cursor: pointer;
    }
</style>

<!-- 등급관리 시작 { -->
<div id="smb_my" style="display: grid;grid-template-columns: 1fr;">
            
            <div style="display: flex;flex-direction:column;row-gap:10px;background:white;padding:5px 0;">
                <div style="display: flex;align-items:center;gap:10px;">
                    <div style="font-weight:800;">분&nbsp;&nbsp;&nbsp;류 : </div>
                    <div>
                        <button type="button" data-value="" class="ctype btn-n active" onclick="viewTypeChange(event)">전체</button>
                        <button type="button" data-value="정시" class="ctype btn-n" onclick="viewTypeChange(event)">정시</button>
                        <button type="button" data-value="수시" class="ctype btn-n" onclick="viewTypeChange(event)">수시</button>
                    </div>
                </div>
                <div style="display: flex;align-items:center;gap:10px;">
                    <div style="font-weight:800;">모&nbsp;&nbsp;&nbsp;집 : </div>
                    <div>
                        <button type="button" data-value="" class="gtype btn-n active" onclick="viewgTypeChange(event)">전체</button>
                        <button type="button" data-value="가군" class="gtype btn-n" onclick="viewgTypeChange(event)">가군</button>
                        <button type="button" data-value="나군" class="gtype btn-n" onclick="viewgTypeChange(event)">나군</button>
                        <button type="button" data-value="다군" class="gtype btn-n" onclick="viewgTypeChange(event)">다군</button>
                    </div>
                </div>
                <div style="display: flex;align-items:center;gap:10px;">
                    <div style="font-weight:800;">지&nbsp;&nbsp;&nbsp;역 : </div>
                    <div>
                        <button type="button" data-value="" class="areaCode btn-n active" onclick="viewArea(event,'')">전체</button>
                        <?
                            $asql = sql_query("SELECT * FROM g5_cmmn_code WHERE upperCode ='C10000000' AND useYN = 1");
                            foreach($asql as $as => $a){
                        ?>
                            <button type="button" data-value="<?=$a['codeName']?>" class="areaCode btn-n" onclick="viewArea(event,'<?=$a['codeName']?>')"><?=$a['codeName']?></button>
                        <?}?>
                    </div>
                </div>
                <div style="display: flex;align-items:center;gap:10px;">
                    <div style="font-weight:800;">검&nbsp;&nbsp;&nbsp;색 : </div>
                    <div style="display: flex;justify-content:center;align-items:center;gap:10px;min-width:500px;">
                        <td style="padding:10px;"><input type="text" name="textCol" id="textCol" placeholder="대학명" class="frm_input textSearch" style="width: 100%;padding:0 10px;height:35px;" value="<?=$text?>"></td>
                        <td style="padding:10px;"><input type="text" name="textSub" id="textSub" placeholder="학과명" class="frm_input textSearch" style="width: 100%;padding:0 10px;height:35px;" value="<?=$text?>"></td>
                        <td style="padding:10px;"><input type="button" class="search-btn" value="" style="width: 50px !important;" onclick="viewCollege()"></td>
                    </div>
                </div>
            </div>
<div id="smb_my_list">
        <!-- 최근 주문내역 시작 { -->
        <section id="smb_my_od" style="margin:unset;">
            <h2>대학 리스트<span style="font-size: small;" class="cntTotal">&nbsp;&nbsp;&nbsp; 학과 수 : <?= $cnt['cnt']?></h2>
            <div class="smb_my_more" style="cursor:pointer;">
                <!-- <a onclick="popupBranch('insert','')">등록</a> -->
            </div>
            
            <div class="yes-college" <?if($cnt['cnt'] == 0) echo ' no-view';?> style="display: grid;grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));grid-auto-rows: 160px;gap: 20px;height: 71vh;overflow-y: auto;padding:10px;">
                <button class="btn-n active" id="scrollTopBtn" style="position: absolute;bottom:0px; right:-45px;" onclick="goScrollTop(event)"><i id="hide_btn" class="xi-caret-up" style="font-size: 1em;"></i></button>
                <?
                    foreach($res as $k => $v){
                ?>
                    <div style="height:160px;border:2px solid #e4e4e480;border-radius:20px;box-shadow:0 5px 10px darkgray;max-width:400px;" class="college_hov colleges" data-area="<?=$v['areaName']?>" data-college="<?=$v['cName']?>" data-subject="<?=$v['sName']?>" data-susi="<?=$v['su']?>" data-jungsi="<?=$v['ju']?>" data-gun="<?=$v['gun']?>">
                        <div style="display: grid;grid-template-columns:1fr 2fr 0.2fr;height:100%;align-items:center;padding:10px 20px;gap:10px;">
                            <div onclick="viewDetail('<?=$v['sIdx']?>')">
                                <img src="<?=$v['c_url']?>" style="max-width:100px;"/>
                            </div>
                            <div style="height:100%;padding:10px;display: flex;flex-direction: column;justify-content: space-between;" onclick="viewDetail('<?=$v['sIdx']?>')">
                                <div>
                                    <div style="font-weight: 900;font-size:1.3em;"><?=$v['cName']?></div>
                                    <div style="color: gray;font-size:1.1em"><?=$v['sName']?></div>
                                    <div style="color: gray;font-size:1em"># <?=$v['areaName']?><?if($v['gun']) echo " # " . $v['gun'];?></div>
                                </div>
                                <div style="display: flex;gap:5px;align-items:center;margin-top:5px;width:100%;">
                                    <?if($v['ju']>0 && (!$stype || $stype == 'jungsi')){?>
                                    <div style="width: 45%;background: #ffc0cb70;border-radius: 15px;text-align: center;font-weight: bold;color:hotpink;padding:3px;">
                                        정시
                                    </div>
                                    <?}?>
                                    <?if($v['su']>0 && (!$stype || $stype == 'susi')){?>
                                    <div style="width: 45%;background: #87ceeb3b;border-radius: 15px;text-align: center;font-weight: bold;color:#639daf;padding:3px;">
                                        수시
                                    </div>
                                    <?}?>
                                </div>
                            </div>
                            <div style="height: 100%;text-align:end;">
                                <i class="xi-star<?if($v['myIdx']) echo " view";?> myicon" style="color:hotpink;" onclick="addCollege(event,'remove','<?=$v['sIdx']?>')"></i>
                                <i class="xi-star-o<?if(!$v['myIdx']) echo " view";?> myicon" onclick="addCollege(event,'add','<?=$v['sIdx']?>')"></i>
                            </div>
                        </div>
                    </div>
                <?}?>
            </div>
        
            <div class="no-college<?if($cnt['cnt'] > 0) echo ' no-view';?>" style="display:none;align-items:center;height: 200px;width:100%;justify-content:center;padding:20px;font-size: 1.5em;font-weight: 800;">검색 결과가 없습니다.</div>
        
        </section>
        <!-- } 최근 주문내역 끝 -->
    </div>
	
</div>

<div id="collegePopup">
    <div class="mb20" id="collegeDiv">
        
    </div>
</div>

<script>
    function viewDetail(subIdx){
        $.ajax({
            url: "/bbs/searchSubject.php",
            type: "POST",
            data: {
                idx : subIdx,
                stype : $("#stype").val(),
            },
            async: false,
            error: function(data) {
                alert('에러가 발생하였습니다.');
                return false;
            },
            success: function(data) {
                json = eval("(" + data + ");");
                // console.log(json);
                // <div style="font-size:1.5em;font-weight:800;padding:0 10px;">
                //         ${json['data']['college']['subjectNm']}
                //     </div>
                let html = `
                    <div style="display: flex;justify-content: center;align-items: center;gap: 15px;font-size: 2em;font-weight: 800;">
                        <img src="${json['data']['college']['img']}" style="max-width:80px;"/>
                        ${json['data']['college']['collegeNm']}`;
                if(json['data']['college']['addYn']){
                    html += `<i class="xi-star" style="color: hotpink;display: flex;color:hotpink;font-size:20px;"></i>`;
                }
                html += `</div>
                    <div style="display:flex;gap:5px;align-items:end;margin-bottom:5px;">
                        <div style="font-size:1.5em;font-weight:800;padding-left:10px;"># ${json['data']['college']['subjectNm']}</div>
                        <div style="display:flex;justify-content:start;align-items:center;gap:4px;">
                            <div class="hashT">
                                # ${json['data']['college']['areaNm']}
                            </div>

                            <div class="hashT">
                                # ${json['data']['college']['collegeType']}
                            </div>`;
                    if(!Array.isArray(json['data']['jungsi']) && json['data']['susi'].length > 0){
                        html+=`<div class="hashT">
                                # 정시/수시
                            </div>`;
                    } else if(!Array.isArray(json['data']['jungsi']) && json['data']['susi'].length == 0){
                        html+=`<div class="hashT">
                                # 정시
                            </div>`;
                    } else if(Array.isArray(json['data']['jungsi']) && json['data']['susi'].length > 0){
                        html+=`<div class="hashT">
                                # 수시
                            </div>`;
                    }

                    if(!Array.isArray(json['data']['jungsi'])){
                        html+=`<div class="hashT">
                                # ${json['data']['college']['gun']}
                            </div>`;
                    }
                    html +=`</div>
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;text-align:center;border-bottom: 2px solid #0c2233;border-top-left-radius: 15px;overflow: hidden;border-top-right-radius: 15px;">`;

                    if(!Array.isArray(json['data']['jungsi'])){    
                        html += `<div class="top_menu jInfo active" data-type="jInfo" data-rm="sInfo">정시</div>`;
                    } else{
                        html += `<div class="top_menu jInfo" style="pointer-events:none !important;color:#e4e4e4;" data-type="jInfo" data-rm="sInfo">정시</div>`;
                    }
                
                    if(json['data']['susi'].length > 0 && !Array.isArray(json['data']['jungsi'])){
                        html += `<div class="top_menu sInfo" data-type="sInfo" data-rm="jInfo">수시</div>`;
                    } else if(json['data']['susi'].length > 0){
                        html += `<div class="top_menu sInfo active" data-type="sInfo" data-rm="jInfo">수시</div>`;
                    } else{
                        html += `<div class="top_menu sInfo" style="pointer-events:none !important;color:#e4e4e4;"  data-type="sInfo" data-rm="jInfo">수시</div>`;
                    }

                    html+= `</div>`;

                if(!Array.isArray(json['data']['jungsi'])){
                    let tag = "";
                    if(parseInt(json['data']['jungsi']['person'])){
                        tag = " 명";
                    }
                    let pro = "";
                    if(json['data']['jungsi']['Pro']){
                        pro = " / 교직이수 : " + json['data']['jungsi']['Pro'];
                    }
                    html += `
                        <div id="jInfo" class="info-Content view">
                            <div>
                            <p class="p_title">모집 인원 : <span style="font-weight:normal;font-size:0.9em;">${json['data']['jungsi']['person']}${tag}${pro}</span></p>`;
                    
                    // 요소 반영 비율
                    html += `
                        <p class="p_title">요소별 반영비율</p>
                        <div class="inner_Cont">
                            <table style="text-align:center;" border="1">
                                <colgroup width='*'>
                                <colgroup width='*'>
                                <colgroup width='*'>
                                <colgroup width='*'>
                                <colgroup width='*'>
                                <thead>
                                    <tr>
                                        <th>수능</th>
                                        <th>내신</th>
                                        <th>실기</th>
                                        <th>기타</th>
                                        <th>전형총점</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>${json['data']['jungsi']['Srate'] ? json['data']['jungsi']['Srate'] : '-'}</td>
                                        <td>${json['data']['jungsi']['Nrate'] ? json['data']['jungsi']['Nrate'] : '-'}</td>
                                        <td>${json['data']['jungsi']['Prate'] ? json['data']['jungsi']['Prate'] : '-'}</td>
                                        <td>${json['data']['jungsi']['Orate'] ? json['data']['jungsi']['Orate'] : '-'}</td>
                                        <td>${json['data']['jungsi']['total'] ? json['data']['jungsi']['total'] + '점' : '-'}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>`;

                    // 과목별 반영 비율
                    html += `
                        <p class="p_title">과목별 반영비율 / 선택 과목</p>
                        <div class="inner_Cont">
                            <table style="text-align:center;" border="1">
                                <colgroup width='*'>
                                <colgroup width='*'>
                                <colgroup width='*'>
                                <colgroup width='*'>
                                <colgroup width='*'>
                                <colgroup width='*'>
                                <thead>
                                    <tr>
                                        <th>과목</th>
                                        <th>국어</th>
                                        <th>수학</th>
                                        <th>영어</th>
                                        <th>탐구</th>
                                        <th>한국사</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="background-color: #334d63;color: white;font-weight:bold;">비율</td>
                                        <td>${json['data']['jungsi']['Korrate'] ? json['data']['jungsi']['Korrate'].replace(/,/g, ', ') : '-'}</td>
                                        <td>${json['data']['jungsi']['Mathrate'] ? json['data']['jungsi']['Mathrate'].replace(/,/g, ', ') : '-'}</td>
                                        <td>${json['data']['jungsi']['Engrate'] ? json['data']['jungsi']['Engrate'].replace(/,/g, ', ') : '-'}</td>
                                        <td>${json['data']['jungsi']['Tamrate'] ? json['data']['jungsi']['Tamrate'].replace(/,/g, ', ') : '-'}</td>
                                        <td>${json['data']['jungsi']['HisAdd'] ? json['data']['jungsi']['HisAdd'] : '-'}</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #334d63;color: white;font-weight:bold;">선택 여부</td>
                                        <td>${json['data']['jungsi']['KorSelect'] ? json['data']['jungsi']['KorSelect'] : '-'}</td>
                                        <td>${json['data']['jungsi']['MathSelect'] ? json['data']['jungsi']['MathSelect'] : '-'}</td>
                                        <td>${json['data']['jungsi']['EngSelect'] ? json['data']['jungsi']['EngSelect'] : '-'}</td>
                                        <td>${json['data']['jungsi']['TamSelect'] ? json['data']['jungsi']['TamSelect'] : '-'}${json['data']['jungsi']['TamCnt'] > 0 ? '(' + json['data']['jungsi']['TamCnt'] + ')': ''}</td>
                                        <td>${json['data']['jungsi']['HisSelect'] ? json['data']['jungsi']['HisSelect'] : '-'}</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #334d63;color: white;font-weight:bold;">제외 과목</td>
                                        <td>${json['data']['jungsi']['KorSub'] ? json['data']['jungsi']['KorSub'] : '-'}</td>
                                        <td>${json['data']['jungsi']['MathSub'] ? json['data']['jungsi']['MathSub'] : '-'}</td>
                                        <td>-</td>
                                        <td>${json['data']['jungsi']['TamSub'] ? json['data']['jungsi']['TamSub'] : '-'}</td>
                                        <td>-</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: #334d63;color: white;font-weight:bold;">활용 지표</td>
                                        <td>${json['data']['jungsi']['Char'] ? json['data']['jungsi']['Char'] : '-'}</td>
                                        <td>${json['data']['jungsi']['Char'] ? json['data']['jungsi']['Char'] : '-'}</td>
                                        <td>-</td>
                                        <td>`;
                                if(json['data']['jungsi']['TamChar'].includes('변')){
                                    html += `<span class="under_line" style="color:blue;font-weight:800;" onclick="transPopup('${json['data']['college']['collegeNm']}',${subIdx})">${json['data']['jungsi']['TamChar']}</span>`;
                                } else {
                                    html += `${json['data']['jungsi']['TamChar'] ? json['data']['jungsi']['TamChar'] : '-'}`;
                                }
                                html += `</td>
                                        <td>-</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    `;
                    if(json['data']['jungsi']['Psub']){
                        html+=`
                        <p class="p_title">실기 과목</p>
                        <div style="padding: 0 10px;margin:0 5px;font-size:1.1em;">`;
                        let Psub = json['data']['jungsi']['Psub'].split(',');
                        for(let j=0;j<Psub.length;j++){
                            html += `<div class="lis">${Psub[j]}</div>`;
                        }
                        html+=`</div>`;
                    }
                    html += `
                    </div>
                    <div>
                        <p class="p_title">접수/마감일</p>
                        <div class="subject_trans" style="padding: 0 0 0 10px;margin:0 0 0 5px;font-size:1em;">
                            <table style="text-align:center;" border="1">
                                <colgroup width='*'>
                                <colgroup width='*'>
                                <colgroup width='*'>
                                <colgroup width='*'>
                                <colgroup width='*'>
                                <thead>
                                    <tr>
                                        <th colspan="2">원서</th>
                                        <th colspan="2">실기</th>
                                        <th rowspan="2">합격</th>
                                    </tr>
                                    <tr>
                                        <th>접수</th>
                                        <th>마감</th>
                                        <th>시작</th>
                                        <th>마감</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="padding:4px 3px;">${json['data']['jungsi']['AppStart'] ? json['data']['jungsi']['AppStart'].toString().replace(/^(\d{4})(\d{2})(\d{2})$/, '$1-$2-$3') : '-'}</td>
                                        <td style="padding:4px 3px;">${json['data']['jungsi']['AppEnd'] ? json['data']['jungsi']['AppEnd'].toString().replace(/^(\d{4})(\d{2})(\d{2})$/, '$1-$2-$3') : '-'}</td>
                                        <td style="padding:4px 3px;">${json['data']['jungsi']['PrStart'] ? json['data']['jungsi']['PrStart'].toString().replace(/^(\d{4})(\d{2})(\d{2})$/, '$1-$2-$3') : '-'}</td>
                                        <td style="padding:4px 3px;">${json['data']['jungsi']['PrEnd'] ? json['data']['jungsi']['PrEnd'].toString().replace(/^(\d{4})(\d{2})(\d{2})$/, '$1-$2-$3') : '-'}</td>
                                        <td style="padding:4px 3px;">${json['data']['jungsi']['PsDate'] ? json['data']['jungsi']['PsDate'].toString().replace(/^(\d{4})(\d{2})(\d{2})$/, '$1-$2-$3') : '-'}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <p class="p_title">과목별 점수 반영표</p>
                        <div class="subject_trans" style="padding: 0 0 0 10px;margin:0 0 0 5px;font-size:1em;">
                    `;

                    // 과목별 변환점수 표
                    if(Array.isArray(json['data']['jungsi']['eng']) && Array.isArray(json['data']['jungsi']['lang']) && Array.isArray(json['data']['jungsi']['history'])){
                        html += `<div style="text-align:center;">
                            <p>-</p>
                            </div>`;
                    } else {
                        if(!Array.isArray(json['data']['jungsi']['eng'])){
                            html += `<div style="text-align:center;">
                                <table style="text-align:center;" border="1">
                                    <colgroup>
                                        <col width="50px">
                                        <col width="*">
                                    </colgroup>
                                    <thead>
                                        <th colspan="10" style="background-color: #334d63;color: white;">영어</th>
                                    </thead>
                                    <tbody>
                            `;
                            html += `<tr>`;
                            for(let t = 1; t < 10; t++){
                                if(t == 1){
                                    html += `<td style="background-color: #334d63;color: white;font-weight:bold;">등급</td>`;
                                }
                                html += `
                                            <td>
                                                ${t}
                                            </td>
                                        `;
                            }
                            html += '</tr><tr>';
                            for(let t = 1; t < 10; t++){
                                if(t == 1){
                                    html += `<td style="background-color: #334d63;color: white;font-weight:bold;">점수</td>`;
                                }
                                html += `
                                            <td>
                                                ${json['data']['jungsi']['eng'][t]['score']}
                                            </td>
                                        `;
                            }
                            html += `</tr>
                                    </tbody>
                                </table>
                            </div>`;
                        }
                        if(!Array.isArray(json['data']['jungsi']['history'])){
                            html += `<div style="text-align:center;">
                                <table style="text-align:center;" border="1">
                                    <colgroup>
                                        <col width="50px">
                                        <col width="*">
                                    </colgroup>
                                    <thead>
                                        <th colspan="10" style="background-color: #334d63;color: white;">한국사</th>
                                    </thead>
                                    <tbody>
                            `;
                            html += `<tr>`;
                            for(let t = 1; t < 10; t++){
                                if(t == 1){
                                    html += `<td style="background-color: #334d63;color: white;font-weight:bold;">등급</td>`;
                                }
                                html += `
                                            <td>
                                                ${t}
                                            </td>
                                        `;
                            }
                            html += '</tr><tr>';
                            for(let t = 1; t < 10; t++){
                                if(t == 1){
                                    html += `<td style="background-color: #334d63;color: white;font-weight:bold;">점수</td>`;
                                }
                                html += `
                                            <td>
                                                ${json['data']['jungsi']['history'][t]['score']}
                                            </td>
                                        `;
                            }
                            html += `</tr>
                                    </tbody>
                                </table>
                            </div>`;
                    }
                    html += `</div>
                    </div>`;
                    }
                    if(!Array.isArray(json['data']['jungsi']['lang'])){
                        html += `<div style="text-align:center;">
                                <p>제2 외국어</p>
                                <table style="text-align:center;" border="1">
                                    <colgroup>
                                        <col width="50px">
                                        <col width="*">
                                    </colgroup>
                                    <tbody>
                            `;
                            html += `<tr>`;
                            for(let t = 1; t < 10; t++){
                                if(t == 1){
                                    html += `<td style="background-color: #334d63;color: white;">등급</td>`;
                                }
                                html += `
                                            <td>
                                                ${t}
                                            </td>
                                        `;
                            }
                            html += '</tr><tr>';
                            for(let t = 1; t < 10; t++){
                                if(t == 1){
                                    html += `<td style="background-color: #334d63;color: white;">점수</td>`;
                                }
                                html += `
                                            <td>
                                                ${json['data']['jungsi']['lang'][t]['score']}
                                            </td>
                                        `;
                            }
                            html += `</tr>
                                    </tbody>
                                </table>
                            </div>`;
                    }
                    html += `</div>
                        </div>
                    </div>`;
                    
                }

                // 수시영역
                if(json['data']['susi'].length > 0){
                    html += `<div id="sInfo" class="info-Content cnt${json['data']['susi'].length}">`;
                    for(let q = 0; q<json['data']['susi'].length; q++){
                        html += `
                        <div class="susi-Order" data-idx="${q}" style="text-align:center;padding:15px;cursor:pointer;">
                            ${json['data']['susi'][q]['suOrder']}
                        </div>
                    `;
                    }
                    html += `
                        </div>
                    `;
                    for(let k = 0; k < json['data']['susi'].length; k++){
                        let stag = "";
                        if(parseInt(json['data']['susi'][k]['suPerson'])){
                            stag = " 명";
                        }
                        let spro = "";
                        if(json['data']['susi'][k]['suPro']){
                            spro = " / 교직이수 : " + json['data']['susi'][k]['suPro'];
                        }

                        html += `<div class="susi-Content">
                                    <div>
                                        <p class="p_title">모집 인원 : <span style="font-weight:normal;font-size:0.9em;">${json['data']['susi'][k]['suPerson']}${stag}${spro}</span></p>
                                        <p class="p_title">전형/상세 : <span style="font-weight:normal;font-size:0.9em;">${json['data']['susi'][k]['suType']} - ${json['data']['susi'][k]['suDetail']}</span></p>
                                        <p class="p_title">접수/마감일<span style="font-weight:normal;font-size:0.9em;"> - 학생부 기준일 : ${json['data']['susi'][k]['suSchool'].toString().replace(/^(\d{4})(\d{2})(\d{2})$/, '$1-$2-$3')}</span></p>
                                        <div class="subject_trans" style="padding: 0 0 0 10px;margin:0 0 0 5px;font-size:1em;">
                                            <table style="text-align:center;" border="1">
                                                <colgroup width='*'>
                                                <colgroup width='*'>
                                                <colgroup width='*'>
                                                <colgroup width='*'>
                                                <colgroup width='*'>
                                                <thead>
                                                    <tr>
                                                        <th colspan="2">원서</th>
                                                        <th colspan="2">실기</th>
                                                        <th rowspan="2">합격</th>
                                                    </tr>
                                                    <tr>
                                                        <th>접수</th>
                                                        <th>마감</th>
                                                        <th>시작</th>
                                                        <th>마감</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td style="padding:4px 3px;">${json['data']['susi'][k]['suAppStart'] ? json['data']['susi'][k]['suAppStart'].toString().replace(/^(\d{4})(\d{2})(\d{2})$/, '$1-$2-$3') : '-'}</td>
                                                        <td style="padding:4px 3px;">${json['data']['susi'][k]['suAppEnd'] ? json['data']['susi'][k]['suAppEnd'].toString().replace(/^(\d{4})(\d{2})(\d{2})$/, '$1-$2-$3') : '-'}</td>
                                                        <td style="padding:4px 3px;">${json['data']['susi'][k]['suSilStart'] ? json['data']['susi'][k]['suSilStart'].toString().replace(/^(\d{4})(\d{2})(\d{2})$/, '$1-$2-$3') : '-'}</td>
                                                        <td style="padding:4px 3px;">${json['data']['susi'][k]['suSilEnd'] ? json['data']['susi'][k]['suSilEnd'].toString().replace(/^(\d{4})(\d{2})(\d{2})$/, '$1-$2-$3') : '-'}</td>
                                                        <td style="padding:4px 3px;">${json['data']['susi'][k]['suPsDate'] ? json['data']['susi'][k]['suPsDate'].toString().replace(/^(\d{4})(\d{2})(\d{2})$/, '$1-$2-$3') : '-'}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>`;
                                    if(json['data']['susi'][k]['suGraduDate'] && json['data']['susi'][k]['suSchoolType']){
                                        html += `<p class="p_title">졸업/고등학교<span style="font-weight:normal;font-size:0.9em;">${json['data']['susi'][k]['suGraduDate'] && json['data']['susi'][k]['suGraduDate'] != '-' ? ' - ' + json['data']['susi'][k]['suGraduDate'] + '년' : ''} / ${json['data']['susi'][k]['suSchoolType']}</span></p>`;
                                    }
                                    html+=`
                                        <p class="p_title">내신반영구분</p>
                                        <div class="subject_trans" style="padding: 0 0 0 10px;margin:0 0 0 5px;font-size:0.9em;">
                                            <table style="text-align:center;" border="1">
                                                <colgroup width='*'>
                                                <colgroup width='*'>
                                                <colgroup width='*'>
                                                <colgroup width='*'>
                                                <colgroup width='*'>
                                                <thead>
                                                    <tr>
                                                        <th colspan="2">내신반영구분</th>
                                                        <th colspan="2">1단계</th>
                                                        <th colspan="4">2단계</th>
                                                        <th rowspan="2">총점</th>
                                                    </tr>
                                                    <tr>
                                                        <th>교과</th>
                                                        <th>비교과</th>
                                                        <th>적용</th>
                                                        <th>배수</th>
                                                        <th>내신</th>
                                                        <th>실기</th>
                                                        <th>면접</th>
                                                        <th>기타</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td style="padding:4px 3px;">${json['data']['susi'][k]['suNaeSinType'].split(",")[0] && json['data']['susi'][k]['suNaeSinType'].split(",")[0] != '0' ? json['data']['susi'][k]['suNaeSinType'].split(",")[0] + '%': '-'}</td>
                                                        <td style="padding:4px 3px;">${json['data']['susi'][k]['suNaeSinType'].split(",")[1] && json['data']['susi'][k]['suNaeSinType'].split(",")[1] != '0' ? json['data']['susi'][k]['suNaeSinType'].split(",")[1] + '%': '-'}</td>
                                                        <td style="padding:4px 3px;">${json['data']['susi'][k]['suFirst'].split(",")[0]? json['data']['susi'][k]['suFirst'].split(",")[0] : '-'}</td>
                                                        <td style="padding:4px 3px;">${json['data']['susi'][k]['suFirst'].split(",")[1] && json['data']['susi'][k]['suFirst'].split(",")[1] != '0' ? json['data']['susi'][k]['suFirst'].split(",")[1] + '%' : '-'}</td>
                                                        <td style="padding:4px 3px;">${json['data']['susi'][k]['suSecond'].split(",")[0] && json['data']['susi'][k]['suSecond'].split(",")[0] != '0' ? json['data']['susi'][k]['suSecond'].split(",")[0] + '%' : '-'}</td>
                                                        <td style="padding:4px 3px;">${json['data']['susi'][k]['suSecond'].split(",")[1] && json['data']['susi'][k]['suSecond'].split(",")[1] != '0' ? json['data']['susi'][k]['suSecond'].split(",")[1] + '%' : '-'}</td>
                                                        <td style="padding:4px 3px;">${json['data']['susi'][k]['suSecond'].split(",")[2] && json['data']['susi'][k]['suSecond'].split(",")[2] != '0' ? json['data']['susi'][k]['suSecond'].split(",")[2] + '%' : '-'}</td>
                                                        <td style="padding:4px 3px;">${json['data']['susi'][k]['suSecond'].split(",")[3] && json['data']['susi'][k]['suSecond'].split(",")[3] != '0' ? json['data']['susi'][k]['suSecond'].split(",")[3] + '%': '-'}</td>
                                                        <td style="padding:4px 3px;">${json['data']['susi'][k]['suTotalScore'] ? json['data']['susi'][k]['suTotalScore'] : '-'}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <p class="p_title">학년별 내신반영비율</p>
                                        <div class="subject_trans" style="padding: 0 0 0 10px;margin:0 0 0 5px;font-size:1em;">
                                            <table style="text-align:center;" border="1">
                                                <colgroup width='*'>
                                                <colgroup width='*'>
                                                <colgroup width='*'>
                                                <colgroup width='*'>
                                                <thead>
                                                    <tr>
                                                        <th>1</th>
                                                        <th>2</th>
                                                        <th>3</th>
                                                        <th>전학년</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td style="padding:4px 3px;">${json['data']['susi'][k]['suGradeScore'].split(",")[0] && json['data']['susi'][k]['suGradeScore'].split(",")[0] != '0' ? json['data']['susi'][k]['suGradeScore'].split(",")[0] + '%' : '-'}</td>
                                                        <td style="padding:4px 3px;">${json['data']['susi'][k]['suGradeScore'].split(",")[1] && json['data']['susi'][k]['suGradeScore'].split(",")[1] != '0' ? json['data']['susi'][k]['suGradeScore'].split(",")[1] + '%' : '-'}</td>
                                                        <td style="padding:4px 3px;">${json['data']['susi'][k]['suGradeScore'].split(",")[2] && json['data']['susi'][k]['suGradeScore'].split(",")[2] != '0' ? json['data']['susi'][k]['suGradeScore'].split(",")[2] + '%' : '-'}</td>
                                                        <td style="padding:4px 3px;">${json['data']['susi'][k]['suGradeScore'].split(",")[3] && json['data']['susi'][k]['suGradeScore'].split(",")[3] != '0' ? json['data']['susi'][k]['suGradeScore'].split(",")[3] + '%' : '-'}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                <div>`;
                                let sNormal = "";
                                let sNF = "";
                                let sFutuer = "";
                                if(json['data']['susi'][k]['suNaesinNormal']){
                                    sNormal = `일반<span style="font-weight:normal;font-size:0.9em;"> - ${json['data']['susi'][k]['suNaesinNormal']}</span>`;
                                    sNF = `<span style="font-weight:normal;font-size:0.9em;"> / </span>`;
                                }
                                if(json['data']['susi'][k]['suNaesinFutuer'] != '-' && json['data']['susi'][k]['suNaesinFutuer'] != ''){
                                    sFutuer = `진로<span style="font-weight:normal;font-size:0.9em;"> - </span>` + `<span style="font-weight:normal;font-size:0.9em;width:70%;">${json['data']['susi'][k]['suNaesinFutuer']}</span>`;
                                } else {
                                    sNF = "";
                                }
                                if(sNormal || sFutuer){
                                    html += `<p class="p_title">${sNormal}${sNF}${sFutuer}</p>`;
                                }
                                    
                                html += `
                                        <p class="p_title">내신적용<span style="font-weight:normal;font-size:0.9em;">${json['data']['susi'][k]['suNaesinOther'] && json['data']['susi'][k]['suNaesinOther'] != '-' ? ' - </span><span style="font-weight:normal;font-size:0.9em;width:80%;">' + json['data']['susi'][k]['suNaesinOther'] + '</span>' : '</span>'}</p>
                                        <div class="subject_trans" style="padding: 0 0 0 10px;margin:0 0 0 5px;font-size:1em;">
                                            <table style="text-align:center;" border="1">
                                                <colgroup width='15%'>
                                                <colgroup width='11%'>
                                                <colgroup width='11%'>
                                                <colgroup width='11%'>
                                                <colgroup width='11%'>
                                                <colgroup width='11%'>
                                                <colgroup width='30%'>
                                                <thead>
                                                    <tr>
                                                        <th colspan="6">교과반영과목</th>
                                                        <th rowspan="2">활용 지표</th>
                                                    </tr>
                                                    <tr>
                                                        <th>전과목</th>
                                                        <th>국어</th>
                                                        <th>수학</th>
                                                        <th>영어</th>
                                                        <th>사회</th>
                                                        <th>과학</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td style="padding:4px 3px;">${json['data']['susi'][k]['suNaesinSubject'].split(",")[0] ? json['data']['susi'][k]['suNaesinSubject'].split(",")[0] : '-'}</td>
                                                        <td style="padding:4px 3px;">${json['data']['susi'][k]['suNaesinSubject'].split(",")[1] ? json['data']['susi'][k]['suNaesinSubject'].split(",")[1] : '-'}</td>
                                                        <td style="padding:4px 3px;">${json['data']['susi'][k]['suNaesinSubject'].split(",")[2] ? json['data']['susi'][k]['suNaesinSubject'].split(",")[2] : '-'}</td>
                                                        <td style="padding:4px 3px;">${json['data']['susi'][k]['suNaesinSubject'].split(",")[3] ? json['data']['susi'][k]['suNaesinSubject'].split(",")[3] : '-'}</td>
                                                        <td style="padding:4px 3px;">${json['data']['susi'][k]['suNaesinSubject'].split(",")[4] ? json['data']['susi'][k]['suNaesinSubject'].split(",")[4] : '-'}</td>
                                                        <td style="padding:4px 3px;">${json['data']['susi'][k]['suNaesinSubject'].split(",")[5] ? json['data']['susi'][k]['suNaesinSubject'].split(",")[5] : '-'}</td>
                                                        <td style="padding:4px 3px;">${json['data']['susi'][k]['suNaesinGuide'] ? json['data']['susi'][k]['suNaesinGuide'] : '-'}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                    </div>`;
                                
                                    if(json['data']['susi'][k]['suNaesinGrade']&&json['data']['susi'][k]['suNaesinGrade'].split(',')[0] != '0'){
                                        html += `
                                            <div class="subject_trans" style="padding: 10px 0 0 10px;margin:0 0 0 5px;font-size:1em;">
                                                <table style="text-align:center;" border="1">
                                                    <colgroup width='*'>
                                                    <thead>
                                                        <tr>
                                                            <th colspan="10">내신등급 표</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td style="background-color: #334d63;color: white;font-weight:bold;">등급</td>
                                                            <td>1</td>
                                                            <td>2</td>
                                                            <td>3</td>
                                                            <td>4</td>
                                                            <td>5</td>
                                                            <td>6</td>
                                                            <td>7</td>
                                                            <td>8</td>
                                                            <td>9</td>
                                                        </tr>
                                                        <tr>
                                                            <td style="background-color: #334d63;color: white;font-weight:bold;">점수</td>
                                                            <td>${json['data']['susi'][k]['suNaesinGrade'].split(",")[0] == '000' ? '0' : json['data']['susi'][k]['suNaesinGrade'].split(",")[0]}</td>
                                                            <td>${json['data']['susi'][k]['suNaesinGrade'].split(",")[1] == '000' ? '0' : json['data']['susi'][k]['suNaesinGrade'].split(",")[1]}</td>
                                                            <td>${json['data']['susi'][k]['suNaesinGrade'].split(",")[2] == '000' ? '0' : json['data']['susi'][k]['suNaesinGrade'].split(",")[2]}</td>
                                                            <td>${json['data']['susi'][k]['suNaesinGrade'].split(",")[3] == '000' ? '0' : json['data']['susi'][k]['suNaesinGrade'].split(",")[3]}</td>
                                                            <td>${json['data']['susi'][k]['suNaesinGrade'].split(",")[4] == '000' ? '0' : json['data']['susi'][k]['suNaesinGrade'].split(",")[4]}</td>
                                                            <td>${json['data']['susi'][k]['suNaesinGrade'].split(",")[5] == '000' ? '0' : json['data']['susi'][k]['suNaesinGrade'].split(",")[5]}</td>
                                                            <td>${json['data']['susi'][k]['suNaesinGrade'].split(",")[6] == '000' ? '0' : json['data']['susi'][k]['suNaesinGrade'].split(",")[6]}</td>
                                                            <td>${json['data']['susi'][k]['suNaesinGrade'].split(",")[7] == '000' ? '0' : json['data']['susi'][k]['suNaesinGrade'].split(",")[7]}</td>
                                                            <td>${json['data']['susi'][k]['suNaesinGrade'].split(",")[8] == '000' ? '0' : json['data']['susi'][k]['suNaesinGrade'].split(",")[8]}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                        </div>`;
                                    }

                                        if(json['data']['susi'][k]['suSuneungCut'] != '-' && json['data']['susi'][k]['suSuneungCut'] != ''){
                                            html += `<p class="p_title">수능최저<span style="font-weight:normal;font-size:0.9em;"> - </span><span style="font-weight:normal;font-size:0.9em;width:80%;">${json['data']['susi'][k]['suSuneungCut']}</span></p>`;
                                        }

                                        html += `<p class="p_title">실기 ${json['data']['susi'][k]['suSilgiGap'] != '' && json['data']['susi'][k]['suSilgiGap'] != '-' ? ' 급감<span style="font-weight:normal;font-size:0.9em;"> - ' + json['data']['susi'][k]['suSilgiGap'] + '점</span>' : ''}</p>`;
                                        if(json['data']['susi'][k]['suSilgiGap'] != '' && json['data']['susi'][k]['suSilgiGap'] != '-'){
                                        }
                                        if(json['data']['susi'][k]['suSilgi'].split(",")[0]){
                                            html+=`
                                            <div style="padding: 0 10px;margin:0 5px;font-size:1em;">`;
                                            let Psub = json['data']['susi'][k]['suSilgi'].split(",");
                                            for(let j=0;j<Psub.length;j++){
                                                html += `<div class="lis">${Psub[j]}</div>`;
                                            }
                                            html+=`</div>`;
                                        }else {
                                            html += `<div style="padding: 0 10px;margin:0 5px;font-size:1em;">
                                                        <div class="lis">비실기</div>
                                                    </div>
                                            `;
                                        }
                                        html += `
                                </div>
                            </div>
                        `;
                    }
                }
               $("#collegeDiv").html(html);
               setTimeout(() => {

                    $(".top_menu").on('click',function(){
                        let wView =$(this).data('type');
                        let rView =$(this).data('rm');
                        
                        $(`#${wView}`).addClass('view');
                        $(`.${wView}`).addClass('active');
                        $(`#${rView}`).removeClass('view');
                        $(`.${rView}`).removeClass('active');
                        if(wView == 'sInfo'){
                            document.querySelectorAll(".susi-Order")[0].click();
                        } else {
                            document.querySelectorAll(".susi-Order").forEach((el,i,arr)=>{
                                el.classList.remove('view');
                                el.classList.remove('active');
                                document.querySelectorAll(".susi-Content")[i].classList.remove('view');
                            });
                        }
                    });

                    $(".susi-Order").on('click',function(){
                        let idx = $(this).data('idx');
                        document.querySelectorAll(".susi-Order").forEach((el,i,arr)=>{
                            if(i == idx){
                                el.classList.add('view');
                                el.classList.add('active');
                                document.querySelectorAll(".susi-Content")[i].classList.add('view');
                            } else {
                                el.classList.remove('view');
                                el.classList.remove('active');
                                document.querySelectorAll(".susi-Content")[i].classList.remove('view');
                            }
                        });
                    });
                    if($(".sInfo").hasClass('active')){
                        $("#sInfo").addClass('view');
                        $("#sInfo").addClass('active');
                        document.querySelectorAll(".susi-Order")[0].click();
                    }
                    if($(".ctype.active").data('value') == '수시'){
                        let wView = $(".top_menu.sInfo").data('type');
                        let rView = $(".top_menu.sInfo").data('rm');
                        $(`#${wView}`).addClass('view');
                        $(`.${wView}`).addClass('active');
                        $(`#${rView}`).removeClass('view');
                        $(`.${rView}`).removeClass('active');
                        document.querySelectorAll(".susi-Order")[0].click();
                    }
               }, 0);
            }
        });
        $('#popupBackground').fadeIn(); // 배경 표시
        $('#collegePopup').fadeIn(); // 팝업 표시
    }
    $('#closePopup, #popupBackground').click(function() {
        $('#popupBackground').fadeOut(); // 배경 숨기기
        $('#collegePopup').fadeOut(); // 팝업 숨기기
        if (popupWin && !popupWin.closed) {
            popupWin.close();
        }
    });
    
    function addCollege(e,type,idx){
        let icons = e.currentTarget;
        let title = "";
        let text = "";
        switch(type){
            case 'add':
                text = "관심 대학으로 등록 하시겠습니까?";
                break;
            case 'remove':
                text = "관심 대학에서 제외 하시겠습니까?";
                break;
        }
        
        swal({
            title : '',
            text : text,
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
                        url: "/bbs/inteCollege_update.php",
                        type: "POST",
                        data: {
                            type : type,
                            idx : idx,
                            id : '<?=$_SESSION['ss_mb_id']?>',
                        },
                        async: false,
                        error: function(data) {
                            alert('저장 실패! 관리자에게 문의하세요.');
                        },
                        success: function(data) {
                            if(data == 'success'){
                                swal('성공!','저장하였습니다.','success');
                                icons.parentNode.children.forEach((el,i,arr)=>{
                                    el.classList.toggle('view');
                                });
                                setTimeout(() => {
                                    swal.close();
                                }, 1500);
                            }
                        }
                    });
                }
            }
        );
    }

    function returnView(){
        document.querySelectorAll('.colleges').forEach((el,i,arr)=>{
            el.style.display = "";
        });
    }

    function viewTypeChange(e){
        document.querySelectorAll('.ctype').forEach((el,i,arr)=>{
            if(el == e.currentTarget){
                el.classList.add('active');
            } else {
                el.classList.remove('active');
            }
        });
        viewCollege();
    }

    function viewgTypeChange(e){
        document.querySelectorAll('.gtype').forEach((el,i,arr)=>{
            if(el == e.currentTarget){
                el.classList.add('active');
            } else {
                el.classList.remove('active');
            }
        });
        viewCollege();
    }

    function viewArea(e,area){
        e.currentTarget.classList.toggle('active');
        
        if(document.querySelectorAll('.areaCode').length - 1 == document.querySelectorAll('.areaCode.active').length || area == ''){
            document.querySelectorAll('.areaCode').forEach((el,i,arr)=>{
                if(el.textContent == '전체'){
                    el.classList.add('active');
                } else {
                    el.classList.remove('active');
                }
            });
        } else {
            
            if(document.querySelectorAll('.areaCode').length - 1 == document.querySelectorAll('.areaCode.active').length || area == ''){
                document.querySelectorAll('.areaCode').forEach((el,i,arr)=>{
                    if(el.textContent == '전체'){
                        el.classList.add('active');
                    } else {
                        el.classList.remove('active');
                    }
                });
            } else {
                document.querySelectorAll('.areaCode').forEach((el,i,arr)=>{
                    if(el.textContent == '전체'){
                        el.classList.remove('active');
                    }
                });
            }
        }
        viewCollege();
    }

    $(".textSearch").on('keydown',function(e){
        if(e.keyCode == 13){
            viewCollege();
        }
    });

    function viewCollege(){
        let cnt = 0;
        let area = [];
        let ctype = document.querySelector('.ctype.active').dataset.value;
        let textCol = $("#textCol").val();
        let textSub = $("#textSub").val();
        let gun = document.querySelector('.gtype.active').dataset.value;
        document.querySelectorAll('.areaCode.active').forEach((el,i,arr)=>{
            if(el.dataset.value){
                area.push(el.dataset.value);
            }
        });
        document.querySelectorAll('.colleges').forEach((el,i,arr)=>{
            if(area.length > 0 && ctype){
                if(ctype == '정시'){
                    if(area.includes(el.dataset.area) && el.dataset.college.includes(textCol) && el.dataset.subject.includes(textSub) && el.dataset.jungsi == 1 && el.dataset.gun.includes(gun)){
                        el.style.display = "";
                        cnt++;
                    } else {
                        el.style.display = "none";
                    }
                } else if(ctype == '수시'){
                    if(area.includes(el.dataset.area) && el.dataset.college.includes(textCol) && el.dataset.subject.includes(textSub) && el.dataset.susi == 1){
                        el.style.display = "";
                        cnt++;
                    } else {
                        el.style.display = "none";
                    }
                }
            } else if(area.length > 0 && !ctype){
                if(area.includes(el.dataset.area) && el.dataset.college.includes(textCol) && el.dataset.subject.includes(textSub) && el.dataset.gun.includes(gun)){
                    el.style.display = "";
                    cnt++;
                } else {
                    el.style.display = "none";
                }
            } else if(area.length == 0 && ctype){
                if(ctype == '정시'){
                    if(el.dataset.college.includes(textCol) && el.dataset.subject.includes(textSub) && el.dataset.jungsi == 1 && el.dataset.gun.includes(gun)){
                        el.style.display = "";
                        cnt++;
                    } else {
                        el.style.display = "none";
                    }
                } else if(ctype == '수시'){
                    if(el.dataset.college.includes(textCol) && el.dataset.subject.includes(textSub) && el.dataset.susi == 1){
                        el.style.display = "";
                        cnt++;
                    } else {
                        el.style.display = "none";
                    }
                }
    
            } else if(area.length == 0 && !ctype){
                if(el.dataset.college.includes(textCol) && el.dataset.subject.includes(textSub) && el.dataset.gun.includes(gun)){
                    el.style.display = "";
                    cnt++;
                } else {
                    el.style.display = "none";
                }
            }
        });

        $(".cntTotal").html(`<span style="font-size: small;" class="cntTotal">&nbsp;&nbsp;&nbsp; 학과 수 : ${cnt}</span>`);

        if(cnt == 0){
            $(".yes-college").css('display','none');
            $(".no-college").css('display','flex');
        } else{
            $(".yes-college").css('display','grid');
            $(".no-college").css('display','none');
        }
        checkScroll();
    }
    let popupWin = "";
    function transPopup(collNm,subIdx){
        var name = "변환 표준점수";
        var option = "width = 850, height = 600, location = no, toolbars = no, status = no";
        var url = "/shop/popupTransScore?subIdx=" + subIdx + "&colleageNm=" + collNm;
        
        
        popupWin = window.open(url, name, option);
    }
</script>
<!-- } 마이페이지 끝 -->

<?php
include_once("./_tail.php");
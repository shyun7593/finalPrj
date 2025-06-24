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

if($text){
    $sql_add .= " AND (gcs.sName like '%{$text}%' OR gc.cName like '%{$text}%')";
}

$cnt = sql_fetch("SELECT 
    COUNT(*) as 'cnt'
FROM g5_college gc JOIN g5_college_subject gcs on gcs.collegeIdx = gc.cIdx
WHERE {$sql_add}");

$res = sql_query("select 
                    gc.*,
                    gcs.*, 
                    (SELECT COUNT(*) FROM g5_susi gs WHERE gs.suSubIdx = gcs.sIdx) as 'su', 
                    (SELECT COUNT(*) FROM g5_jungsi gj WHERE gj.juSubIdx = gcs.sIdx) as 'ju',
                    gac.idx as 'myIdx',
                    gcc.codeName as 'areaName'
                from g5_college gc
                    LEFT JOIN g5_college_subject gcs on
                        gc.cIdx = gcs.collegeIdx
                    LEFT JOIN g5_add_college gac on
                        gac.subIdx = gcs.sIdx
                    JOIN g5_cmmn_code gcc on
                        gcc.code = gcs.areaCode
                where 
                    {$sql_add}
                ORDER BY gc.cName, gcs.sName");

$query_string = http_build_query(array(
    'stype' => $_GET['stype'],
    'text' => $_GET['text'],
));
?>
<style>
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
        align-items: center;
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
        padding: 0 10px;
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
</style>

<!-- 등급관리 시작 { -->
<div id="smb_my" style="display: grid;grid-template-columns: 1fr;">
            
            <div style="display: flex;flex-direction:column;row-gap:10px;margin-bottom:10px;background:white;padding:10px 5px;">
                <div style="display: flex;align-items:center;gap:10px;">
                    <div style="font-weight:800;">모&nbsp;&nbsp;&nbsp;집 : </div>
                    <div>
                        <button type="button" data-value="" class="ctype btn-n active" onclick="viewTypeChange(event)">전체</button>
                        <button type="button" data-value="정시" class="ctype btn-n" onclick="viewTypeChange(event)">정시</button>
                        <button type="button" data-value="수시" class="ctype btn-n" onclick="viewTypeChange(event)">수시</button>
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
                        <td style="padding:10px;"><input type="text" name="text" id="text" placeholder="대학명, 학과명" class="frm_input" style="width: 100%;padding:0 10px;" value="<?=$text?>"></td>
                        <td style="padding:10px;"><input type="button" class="search-btn" value="" onclick="viewCollege()"></td>
                    </div>
                </div>
            </div>
<div id="smb_my_list">
        <!-- 최근 주문내역 시작 { -->
        <section id="smb_my_od">
            <h2>대학 리스트<span style="font-size: small;" class="cntTotal">&nbsp;&nbsp;&nbsp; 학과 수 : <?= $cnt['cnt']?></h2>
            <div class="smb_my_more" style="cursor:pointer;">
                <!-- <a onclick="popupBranch('insert','')">등록</a> -->
            </div>
            
            <div class="yes-college" <?if($cnt['cnt'] == 0) echo ' no-view';?> style="display: grid;grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));grid-auto-rows: 150px;gap: 20px;margin-top:20px;height: 69vh;overflow-y: scroll;padding:10px;">
                <?
                    foreach($res as $k => $v){
                ?>
                    <div style="height:150px;border:2px solid #e4e4e480;border-radius:20px;box-shadow:0 5px 10px darkgray;max-width:400px;" class="college_hov colleges" data-area="<?=$v['areaName']?>" data-college="<?=$v['cName']?>" data-subject="<?=$v['sName']?>" data-susi="<?=$v['su']?>" data-jungsi="<?=$v['ju']?>">
                        <div style="display: grid;grid-template-columns:1fr 2fr 0.5fr;height:100%;align-items:center;padding:10px 20px;gap:10px;">
                            <div onclick="viewDetail('<?=$v['sIdx']?>')">
                                <img src="<?=$v['c_url']?>" style="max-width:110px;"/>
                            </div>
                            <div style="height:100%;padding:10px;display: flex;flex-direction: column;justify-content: space-between;" onclick="viewDetail('<?=$v['sIdx']?>')">
                                <div>
                                    <div style="font-weight: 900;font-size:1.3em;"><?=$v['cName']?></div>
                                    <div style="color: gray;font-size:1.1em"><?=$v['sName']?></div>
                                    <div style="color: gray;font-size:1em"># <?=$v['areaName']?></div>
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
                // <div style="font-size:1.5em;font-weight:800;padding:0 10px;">
                //         ${json['data']['college']['subjectNm']}
                //     </div>
                let html = `
                    <div style="display: flex;justify-content: center;align-items: center;gap: 15px;font-size: 2em;font-weight: 800;">
                        <img src="${json['data']['college']['img']}" style="max-width:80px;min-height:80px;"/>
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
                    if(!Array.isArray(json['data']['jungsi']) && !Array.isArray(json['data']['susi'])){
                        html+=`<div class="hashT">
                                # 정시/수시
                            </div>`;
                    } else if(!Array.isArray(json['data']['jungsi']) && Array.isArray(json['data']['susi'])){
                        html+=`<div class="hashT">
                                # 정시
                            </div>`;
                    } else if(Array.isArray(json['data']['jungsi']) && !Array.isArray(json['data']['susi'])){
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
                
                    if(!Array.isArray(json['data']['susi']) && !Array.isArray(json['data']['jungsi'])){
                        html += `<div class="top_menu sInfo" data-type="sInfo" data-rm="jInfo">수시</div>`;
                    } else if(!Array.isArray(json['data']['susi'])){
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
                    html += `
                        <div id="jInfo" class="info-Content view">
                            <div>
                            <p class="p_title">모집 인원 : <span style="font-weight:normal;">${json['data']['jungsi']['person']}${tag}</span></p>`;
                    
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
                                <thead>
                                    <tr>
                                        <th>과목</th>
                                        <th>비율</th>
                                        <th>선택여부</th>
                                        <th>제외 과목</th>
                                        <th>활용 지표</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>국어</td>
                                        <td>${json['data']['jungsi']['Korrate'] ? json['data']['jungsi']['Korrate'].replace(/,/g, ', ') : '-'}</td>
                                        <td>${json['data']['jungsi']['KorSelect'] ? json['data']['jungsi']['KorSelect'] : '-'}</td>
                                        <td>${json['data']['jungsi']['KorSub'] ? json['data']['jungsi']['KorSub'] : '-'}</td>
                                        <td>${json['data']['jungsi']['Char'] ? json['data']['jungsi']['Char'] : '-'}</td>
                                    </tr>
                                    <tr>
                                        <td>수학</td>
                                        <td>${json['data']['jungsi']['Mathrate'] ? json['data']['jungsi']['Mathrate'].replace(/,/g, ', ') : '-'}</td>
                                        <td>${json['data']['jungsi']['MathSelect'] ? json['data']['jungsi']['MathSelect'] : '-'}</td>
                                        <td>${json['data']['jungsi']['MathSub'] ? json['data']['jungsi']['MathSub'] : '-'}</td>
                                        <td>${json['data']['jungsi']['Mathrate'] ? json['data']['jungsi']['Char'] : '-'}</td>
                                    </tr>
                                    <tr>
                                        <td>영어</td>
                                        <td>${json['data']['jungsi']['Engrate'] ? json['data']['jungsi']['Engrate'].replace(/,/g, ', ') : '-'}</td>
                                        <td>${json['data']['jungsi']['EngSelect'] ? json['data']['jungsi']['EngSelect'] : '-'}</td>
                                        <td>-</td>
                                        <td>-</td>
                                    </tr>
                                    <tr>
                                        <td>탐구</td>
                                        <td>${json['data']['jungsi']['Tamrate'] ? json['data']['jungsi']['Tamrate'].replace(/,/g, ', ') : '-'}</td>
                                        <td>${json['data']['jungsi']['TamSelect'] ? json['data']['jungsi']['TamSelect'] : '-'}${json['data']['jungsi']['TamCnt'] > 0 ? '(' + json['data']['jungsi']['TamCnt'] + ')': ''}</td>
                                        <td>${json['data']['jungsi']['TamSub'] ? json['data']['jungsi']['TamSub'] : '-'}</td>
                                        <td>${json['data']['jungsi']['TamChar'] ? json['data']['jungsi']['TamChar'] : '-'}</td>
                                    </tr>
                                    <tr>
                                        <td>한국사</td>
                                        <td>${json['data']['jungsi']['HisAdd'] ? json['data']['jungsi']['HisAdd'] : '-'}</td>
                                        <td>${json['data']['jungsi']['HisSelect'] ? json['data']['jungsi']['HisSelect'] : '-'}</td>
                                        <td>-</td>
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
                            // html += `<div style="text-align:center;">
                            //     <p>영어</p>
                            //     <table style="text-align:center;" border="1">
                            //         <colgroup>
                            //              <col width="*">
                            //         </colgroup>
                            //         <thead>
                            //             <tr>
                            //                 <th>등급</th>
                            //                 <th>반영점수</th>
                            //             </tr>
                            //         </thead>
                            //         <tbody>
                            // `;
                            // for(let t = 1; t < 10; t++){
                            //     html += `<tr>
                            //                 <td>
                            //                     ${t}
                            //                 </td>
                            //                 <td>
                            //                     ${json['data']['jungsi']['eng'][t]['score']}
                            //                 </td>
                            //             </tr>
                            //                 `;
                            // }
                            // html += `
                            //         </tbody>
                            //     </table>
                            // </div>`;
                            html += `<div style="text-align:center;">
                                <p>영어</p>
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
                            // html += `<div style="text-align:center;">
                            //     <p>한국사</p>
                            //     <table style="text-align:center;" border="1">
                            //         <colgroup>
                            //              <col width="*">
                            //         </colgroup>
                            //         <thead>
                            //             <tr>
                            //                 <th>등급</th>
                            //                 <th>반영점수</th>
                            //             </tr>
                            //         </thead>
                            //         <tbody>
                            // `;
                            // for(let t = 1; t < 10; t++){
                            //     html += `<tr>
                            //                 <td>
                            //                     ${t}
                            //                 </td>
                            //                 <td>
                            //                     ${json['data']['jungsi']['history'][t]['score']}
                            //                 </td>
                            //             </tr>
                            //                 `;
                            // }
                            // html += `
                            //         </tbody>
                            //     </table>`;
                            html += `<div style="text-align:center;">
                                <p>한국사</p>
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
                        // html += `<div style="text-align:center;">
                        //     <p>제2외국어</p>
                        //     <table style="text-align:center;" border="1">
                        //         <colgroup>
                        //              <col width="*">
                        //         </colgroup>
                        //         <thead>
                        //             <tr>
                        //                 <th>등급</th>
                        //                 <th>반영점수</th>
                        //             </tr>
                        //         </thead>
                        //         <tbody>
                        // `;
                        // for(let t = 1; t < 10; t++){
                        //     html += `<tr>
                        //                 <td>
                        //                     ${t}
                        //                 </td>
                        //                 <td>
                        //                     ${json['data']['jungsi']['lang'][t]['score']}
                        //                 </td>
                        //             </tr>
                        //                 `;
                        // }
                        // html += `
                        //         </tbody>
                        //     </table>
                        // </div>`;
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
                    </div>`;
                    
                }
                if(!Array.isArray(json['data']['susi'])){
                    html += `
                        <div id="sInfo" class="info-Content">
                            수시 정보 :
                        </div>
                    `;
                    
                }
               $("#collegeDiv").html(html);
               setTimeout(() => {
                    $(".top_menu").on('click',function(){
                        let wView =$(this).data('type');
                        let rView =$(this).data('rm');
                         console.log(wView,rView);
                        
                            $(`#${wView}`).addClass('view');
                            $(`.${wView}`).addClass('active');
                            $(`#${rView}`).removeClass('view');
                            $(`.${rView}`).removeClass('active');
                        
                    });
               }, 0);
                // console.log(json['data']['college']);
                // console.log(json['data']['susi']);
                // console.log(json['data']['jungsi']);
            }
        });
        $('#popupBackground').fadeIn(); // 배경 표시
        $('#collegePopup').fadeIn(); // 팝업 표시
    }
    $('#closePopup, #popupBackground').click(function() {
        $('#popupBackground').fadeOut(); // 배경 숨기기
        $('#collegePopup').fadeOut(); // 팝업 숨기기
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

    $("#text").on('keydown',function(e){
        if(e.keyCode == 13){
            viewCollege();
        }
    });

    function viewCollege(){
        let cnt = 0;
        let area = [];
        let ctype = document.querySelector('.ctype.active').dataset.value;
        let text = $("#text").val();
        document.querySelectorAll('.areaCode.active').forEach((el,i,arr)=>{
            if(el.dataset.value){
                area.push(el.dataset.value);
            }
        });
        document.querySelectorAll('.colleges').forEach((el,i,arr)=>{
            if(area.length > 0 && ctype && text){
                if(ctype == '정시'){
                    if(area.includes(el.dataset.area) && (el.dataset.college.includes(text) || el.dataset.subject.includes(text)) && el.dataset.jungsi == 1){
                        el.style.display = "";
                        cnt++;
                    } else {
                        el.style.display = "none";
                    }
                } else if(ctype == '수시'){
                    if(area.includes(el.dataset.area) && (el.dataset.college.includes(text) || el.dataset.subject.includes(text)) && el.dataset.susi == 1){
                        el.style.display = "";
                        cnt++;
                    } else {
                        el.style.display = "none";
                    }
                }
            } else if(area.length > 0 && ctype && !text){
                if(ctype == '정시'){
                    if(area.includes(el.dataset.area) && el.dataset.jungsi == 1){
                        el.style.display = "";
                        cnt++;
                    } else {
                        el.style.display = "none";
                    }
                } else if(ctype == '수시'){
                    if(area.includes(el.dataset.area) && el.dataset.susi == 1){
                        el.style.display = "";
                        cnt++;
                    } else {
                        el.style.display = "none";
                    }
                }    
            } else if(area.length > 0 && !ctype && text){
                if(area.includes(el.dataset.area) && (el.dataset.college.includes(text) || el.dataset.subject.includes(text))){
                    el.style.display = "";
                    cnt++;
                } else {
                    el.style.display = "none";
                }
            } else if(area.length > 0 && !ctype && !text){
                if(area.includes(el.dataset.area)){
                    el.style.display = "";
                    cnt++;
                } else {
                    el.style.display = "none";
                }
            } else if(area.length == 0 && ctype && text){
                if(ctype == '정시'){
                    if((el.dataset.college.includes(text) || el.dataset.subject.includes(text)) && el.dataset.jungsi == 1){
                        el.style.display = "";
                        cnt++;
                    } else {
                        el.style.display = "none";
                    }
                } else if(ctype == '수시'){
                    if((el.dataset.college.includes(text) || el.dataset.subject.includes(text)) && el.dataset.susi == 1){
                        el.style.display = "";
                        cnt++;
                    } else {
                        el.style.display = "none";
                    }
                }
    
            } else if(area.length == 0 && ctype && !text){
                if(ctype == '정시'){
                    if(el.dataset.jungsi == 1){
                        el.style.display = "";
                        cnt++;
                    } else {
                        el.style.display = "none";
                    }
                } else if(ctype == '수시'){
                    if(el.dataset.susi == 1){
                        el.style.display = "";
                        cnt++;
                    } else {
                        el.style.display = "none";
                    }
                }
            } else if(area.length == 0 && !ctype && text){
                if((el.dataset.college.includes(text) || el.dataset.subject.includes(text))){
                    el.style.display = "";
                    cnt++;
                } else {
                    el.style.display = "none";
                }
            } else if(area.length == 0 && !ctype && !text){
                el.style.display = "";
                cnt++;
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

        if(ctype){
            console.log('모집', ctype);
        }

        if(text){
            console.log('검색', text);
        }
    }
</script>
<!-- } 마이페이지 끝 -->

<?php
include_once("./_tail.php");
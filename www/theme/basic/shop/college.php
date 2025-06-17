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
                    *, 
                    (SELECT COUNT(*) FROM g5_susi gs WHERE gs.suSubIdx = gcs.sIdx) as 'su', 
                    (SELECT COUNT(*) FROM g5_jungsi gj WHERE gj.juSubIdx = gcs.sIdx) as 'ju'
                from g5_college gc
                    LEFT JOIN g5_college_subject gcs on
                    gc.cIdx = gcs.collegeIdx
                where 
                    {$sql_add}
                ORDER BY gc.cName, gcs.sName");

$query_string = http_build_query(array(
    'stype' => $_GET['stype'],
    'text' => $_GET['text'],
));
?>

<!-- 등급관리 시작 { -->
<div id="smb_my" style="display: grid;grid-template-columns: 1fr;">
            <form id="fsearch" name="fsearch" onsubmit="return fsearch_submit(this);" class="local_sch01 local_sch" method="get">
                <div class="tbl_wrap border-tb" style="margin-bottom: 15px;">
                    <table class="tbl_head01">
                        <colgroup width="10%">
                        <colgroup width="10%">
                        <colgroup width="75%">
                        <colgroup width="5%">
                        <tbody>
                            <tr>
                                <td style="text-align: center;font-size:1.2em;font-weight:800;padding:10px;">검색</td>
                                <td style="padding:10px;">
                                    <select style="border:1px solid #e4e4e4;height: 45px;width:100%;padding:5px;" name="stype" id="stype">
                                        <option value="" <?if(!$stype) echo "selected";?>>정시/수시</option>
                                        <option value="jungsi" <?if($stype == 'jungsi') echo "selected";?>>정시</option>
                                        <option value="susi" <?if($stype == 'susi') echo "selected";?>>수시</option>
                                    </select>
                                </td>
                                <td style="padding:10px;"><input type="text" name="text" id="text" placeholder="대학명, 학과명" class="frm_input" style="width: 100%;padding:0 10px;" value="<?=$text?>"></td>
                                <td style="padding:10px;"><input type="submit" class="search-btn" value=""></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </form>
<div id="smb_my_list">
        <!-- 최근 주문내역 시작 { -->
        <section id="smb_my_od">
            <h2>대학 리스트<span style="font-size: small;">&nbsp;&nbsp;&nbsp; 학과 수 : <?= $cnt['cnt']?></h2>
            <div class="smb_my_more" style="cursor:pointer;">
                <!-- <a onclick="popupBranch('insert','')">등록</a> -->
            </div>
            <?if($cnt['cnt']>0){?>
            <div style="display: grid;grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));gap: 20px;margin-top:20px;">
                <?
                    foreach($res as $k => $v){
                ?>
                    <div style="height:150px;border:2px solid #e4e4e480;border-radius:20px;box-shadow:0 5px 10px darkgray;max-width:400px;" class="college_hov" onclick="viewDetail('<?=$v['sIdx']?>')">
                        <div style="display: grid;grid-template-columns:1fr 2fr 0.5fr;height:100%;align-items:center;padding:10px 20px;gap:10px;">
                            <div>
                                <img src="<?=$v['c_url']?>" style="max-width:110px;"/>
                            </div>
                            <div style="height:100%;padding:10px;display: flex;flex-direction: column;justify-content: space-between;">
                                <div>
                                    <div style="font-weight: 900;font-size:1.4em;"><?=$v['cName']?></div>
                                    <div style="color: gray;font-size:1.1em"><?=$v['sName']?></div>
                                </div>
                                <div style="display: flex;gap:15px;align-items:center;">
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
                        </div>
                    </div>
                <?}?>
            </div>
        <?} else{?>
                    <div style="display:flex;align-items:center;height: 200px;width:100%;justify-content:center;padding:20px;font-size: 1.5em;font-weight: 800;">검색 결과가 없습니다.</div>
                <?}?>
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
                        <img src="${json['data']['college']['img']}" style="max-width:80px;"/>
                        ${json['data']['college']['collegeNm']}
                    </div>
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
                    html += `
                        <div id="jInfo" class="info-Content view">
                            정시 정보 : 
                        </div>
                    `;
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

    function fsearch_submit(e) {
    }
    $("#stype").on("change",function(){
        $("#text").val('');
        $("#fsearch").submit();
    });

</script>
<!-- } 마이페이지 끝 -->

<?php
include_once("./_tail.php");
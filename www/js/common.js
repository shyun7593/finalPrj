// 전역 변수
var errmsg = "";
var errfld = null;

// 필드 검사
function check_field(fld, msg)
{
    if ((fld.value = trim(fld.value)) == "")
        error_field(fld, msg);
    else
        clear_field(fld);
    return;
}

// 필드 오류 표시
function error_field(fld, msg)
{
    if (msg != "")
        errmsg += msg + "\n";
    if (!errfld) errfld = fld;
    fld.style.background = "#BDDEF7";
}

// 필드를 깨끗하게
function clear_field(fld)
{
    fld.style.background = "#FFFFFF";
}

function trim(s)
{
    var t = "";
    var from_pos = to_pos = 0;

    for (i=0; i<s.length; i++)
    {
        if (s.charAt(i) == ' ')
            continue;
        else
        {
            from_pos = i;
            break;
        }
    }

    for (i=s.length; i>=0; i--)
    {
        if (s.charAt(i-1) == ' ')
            continue;
        else
        {
            to_pos = i;
            break;
        }
    }

    t = s.substring(from_pos, to_pos);
    //				alert(from_pos + ',' + to_pos + ',' + t+'.');
    return t;
}

// 자바스크립트로 PHP의 number_format 흉내를 냄
// 숫자에 , 를 출력
function number_format(data)
{

    var tmp = '';
    var number = '';
    var cutlen = 3;
    var comma = ',';
    var i;
    
    data = data + '';

    var sign = data.match(/^[\+\-]/);
    if(sign) {
        data = data.replace(/^[\+\-]/, "");
    }

    len = data.length;
    mod = (len % cutlen);
    k = cutlen - mod;
    for (i=0; i<data.length; i++)
    {
        number = number + data.charAt(i);

        if (i < data.length - 1)
        {
            k++;
            if ((k % cutlen) == 0)
            {
                number = number + comma;
                k = 0;
            }
        }
    }

    if(sign != null)
        number = sign+number;

    return number;
}

function drawPaging(val,page, total_page, fnc) {
    let html = '<ul class="paging-list">';
    page = parseInt(page);
    total_page = parseInt(total_page);
    let prev = "";
    let next = "";
    if (total_page > 1) {
        if (page == 1) {
            prev = " style='visibility:hidden;'";
        }
        if (page == total_page) {
            next = " style='visibility:hidden;'";
        }
        // 맨앞
        html += `<li${prev}>
                    <a href="javascript:void(0);" onclick="${fnc}(${val},1)" class="page-btn first">
                    <img src="/theme/basic/img/firstPage.png" alt="처음" style="width:22px; height:22px; vertical-align:middle;">
                    </a>
                </li>
                <li${prev}>
                    <a href="javascript:void(0);" onclick="${fnc}(${val},${page-1})" class="page-btn prev">
                    <img src="/theme/basic/img/prevPage.png" alt="이전" style="width:22px; height:22px; vertical-align:middle;">
                    </a>
                </li>`;
        
        // 숫자
        for (let i = 1; i <= total_page; i++) {
            if (i == page) {
                html += `<li><a href="javascript:void(0);" class="page-btn active">${i}</a></li>`;
            } else {
                html += `<li><a href="javascript:void(0);" onclick="${fnc}(${val},${i})" class="page-btn">${i}</a></li>`;
            }
        }
        // 다음
        html += `<li${next}>
                    <a href="javascript:void(0);" onclick="${fnc}(${val},${page+1})" class="page-btn next">
                    <img src="/theme/basic/img/nextPage.png" alt="다음" style="width:22px; height:22px; vertical-align:middle;">
                    </a>
                </li>
                <li${next}>
                    <a href="javascript:void(0);" onclick="${fnc}(${val},${total_page})" class="page-btn last">
                        <img src="/theme/basic/img/lastPage.png" alt="맨뒤" style="width:22px; height:22px; vertical-align:middle;">
                    </a>
                </li>`;

    }
    html += "</ul>";
    $(".paging").html(html);
    
}

// 새 창
function popup_window(url, winname, opt)
{
    window.open(url, winname, opt);
}


// 폼메일 창
function popup_formmail(url)
{
    opt = 'scrollbars=yes,width=417,height=385,top=10,left=20';
    popup_window(url, "wformmail", opt);
}

// , 를 없앤다.
function no_comma(data)
{
    var tmp = '';
    var comma = ',';
    var i;

    for (i=0; i<data.length; i++)
    {
        if (data.charAt(i) != comma)
            tmp += data.charAt(i);
    }
    return tmp;
}

// 삭제 검사 확인
function del(href)
{
    if(confirm("한번 삭제한 자료는 복구할 방법이 없습니다.\n\n정말 삭제하시겠습니까?")) {
        window.location.href = href;
    }
}

// 쿠키 입력
function set_cookie(name, value, expirehours, domain)
{
    var today = new Date();
    today.setTime(today.getTime() + (60*60*1000*expirehours));
    document.cookie = name + "=" + escape( value ) + "; path=/; expires=" + today.toGMTString() + ";";
    if (domain) {
        document.cookie += "domain=" + domain + ";";
    }
}

// 쿠키 얻음
function get_cookie(name)
{
	var match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
	if (match) return unescape(match[2]);
	return "";
}

// 쿠키 지움
function delete_cookie(name)
{
    var today = new Date();

    today.setTime(today.getTime() - 1);
    var value = get_cookie(name);
    if(value != "")
        document.cookie = name + "=" + value + "; path=/; expires=" + today.toGMTString();
}

var last_id = null;
function menu(id)
{
    if (id != last_id)
    {
        if (last_id != null)
            document.getElementById(last_id).style.display = "none";
        document.getElementById(id).style.display = "block";
        last_id = id;
    }
    else
    {
        document.getElementById(id).style.display = "none";
        last_id = null;
    }
}

function textarea_decrease(id, row)
{
    if (document.getElementById(id).rows - row > 0)
        document.getElementById(id).rows -= row;
}

function textarea_original(id, row)
{
    document.getElementById(id).rows = row;
}

function textarea_increase(id, row)
{
    document.getElementById(id).rows += row;
}

// 글숫자 검사
function check_byte(content, target)
{
    var i = 0;
    var cnt = 0;
    var ch = '';
    var cont = document.getElementById(content).value;

    for (i=0; i<cont.length; i++) {
        ch = cont.charAt(i);
        if (escape(ch).length > 4) {
            cnt += 2;
        } else {
            cnt += 1;
        }
    }
    // 숫자를 출력
    document.getElementById(target).innerHTML = cnt;

    return cnt;
}

// 브라우저에서 오브젝트의 왼쪽 좌표
function get_left_pos(obj)
{
    var parentObj = null;
    var clientObj = obj;
    //var left = obj.offsetLeft + document.body.clientLeft;
    var left = obj.offsetLeft;

    while((parentObj=clientObj.offsetParent) != null)
    {
        left = left + parentObj.offsetLeft;
        clientObj = parentObj;
    }

    return left;
}

// 브라우저에서 오브젝트의 상단 좌표
function get_top_pos(obj)
{
    var parentObj = null;
    var clientObj = obj;
    //var top = obj.offsetTop + document.body.clientTop;
    var top = obj.offsetTop;

    while((parentObj=clientObj.offsetParent) != null)
    {
        top = top + parentObj.offsetTop;
        clientObj = parentObj;
    }

    return top;
}

function flash_movie(src, ids, width, height, wmode)
{
    var wh = "";
    if (parseInt(width) && parseInt(height))
        wh = " width='"+width+"' height='"+height+"' ";
    return "<object classid='clsid:d27cdb6e-ae6d-11cf-96b8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0' "+wh+" id="+ids+"><param name=wmode value="+wmode+"><param name=movie value="+src+"><param name=quality value=high><embed src="+src+" quality=high wmode="+wmode+" type='application/x-shockwave-flash' pluginspage='http://www.macromedia.com/shockwave/download/index.cgi?p1_prod_version=shockwaveflash' "+wh+"></embed></object>";
}

function obj_movie(src, ids, width, height, autostart)
{
    var wh = "";
    if (parseInt(width) && parseInt(height))
        wh = " width='"+width+"' height='"+height+"' ";
    if (!autostart) autostart = false;
    return "<embed src='"+src+"' "+wh+" autostart='"+autostart+"'></embed>";
}

function doc_write(cont)
{
    document.write(cont);
}

var win_password_lost = function(href) {
    window.open(href, "win_password_lost", "left=50, top=50, width=617, height=330, scrollbars=1");
}

$(document).ready(function(){
    $("#login_password_lost, #ol_password_lost").click(function(){
        win_password_lost(this.href);
        return false;
    });
});

/**
 * 포인트 창
 **/
var win_point = function(href) {
    var new_win = window.open(href, 'win_point', 'left=100,top=100,width=600, height=600, scrollbars=1');
    new_win.focus();
}

/**
 * 쪽지 창
 **/
var win_memo = function(href) {
    var new_win = window.open(href, 'win_memo', 'left=100,top=100,width=620,height=500,scrollbars=1');
    new_win.focus();
}

/**
 * 쪽지 창
 **/
var check_goto_new = function(href, event) {
    if( !(typeof g5_is_mobile != "undefined" && g5_is_mobile) ){
        if (window.opener && window.opener.document && window.opener.document.getElementById) {
            event.preventDefault ? event.preventDefault() : (event.returnValue = false);
            window.open(href);
            //window.opener.document.location.href = href;
        }
    }
}

/**
 * 메일 창
 **/
var win_email = function(href) {
    var new_win = window.open(href, 'win_email', 'left=100,top=100,width=600,height=580,scrollbars=1');
    new_win.focus();
}

/**
 * 자기소개 창
 **/
var win_profile = function(href) {
    var new_win = window.open(href, 'win_profile', 'left=100,top=100,width=620,height=510,scrollbars=1');
    new_win.focus();
}

/**
 * 스크랩 창
 **/
var win_scrap = function(href) {
    var new_win = window.open(href, 'win_scrap', 'left=100,top=100,width=600,height=600,scrollbars=1');
    new_win.focus();
}

/**
 * 홈페이지 창
 **/
var win_homepage = function(href) {
    var new_win = window.open(href, 'win_homepage', '');
    new_win.focus();
}

/**
 * 우편번호 창
 **/
var win_zip = function(frm_name, frm_zip, frm_addr1, frm_addr2, frm_addr3, frm_jibeon) {
    if(typeof daum === "undefined"){
        alert("KAKAO 우편번호 서비스 postcode.v2.js 파일이 로드되지 않았습니다.");
        return false;
    }

    // 핀치 줌 현상 제거
    var vContent = "width=device-width,initial-scale=1.0,minimum-scale=0,maximum-scale=10";
    $("#meta_viewport").attr("content", vContent + ",user-scalable=no");

    var zip_case = 1;   //0이면 레이어, 1이면 페이지에 끼워 넣기, 2이면 새창

    var complete_fn = function(data){
        // 팝업에서 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.

        // 각 주소의 노출 규칙에 따라 주소를 조합한다.
        // 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
        var fullAddr = ''; // 최종 주소 변수
        var extraAddr = ''; // 조합형 주소 변수

        // 사용자가 선택한 주소 타입에 따라 해당 주소 값을 가져온다.
        if (data.userSelectedType === 'R') { // 사용자가 도로명 주소를 선택했을 경우
            fullAddr = data.roadAddress;

        } else { // 사용자가 지번 주소를 선택했을 경우(J)
            fullAddr = data.jibunAddress;
        }

        // 사용자가 선택한 주소가 도로명 타입일때 조합한다.
        if(data.userSelectedType === 'R'){
            //법정동명이 있을 경우 추가한다.
            if(data.bname !== ''){
                extraAddr += data.bname;
            }
            // 건물명이 있을 경우 추가한다.
            if(data.buildingName !== ''){
                extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
            }
            // 조합형주소의 유무에 따라 양쪽에 괄호를 추가하여 최종 주소를 만든다.
            extraAddr = (extraAddr !== '' ? ' ('+ extraAddr +')' : '');
        }

        // 우편번호와 주소 정보를 해당 필드에 넣고, 커서를 상세주소 필드로 이동한다.
        var of = document[frm_name];

        of[frm_zip].value = data.zonecode;

        of[frm_addr1].value = fullAddr;
        of[frm_addr3].value = extraAddr;

        if(of[frm_jibeon] !== undefined){
            of[frm_jibeon].value = data.userSelectedType;
        }
        
        setTimeout(function(){
            $("#meta_viewport").attr("content", vContent);
            of[frm_addr2].focus();
        } , 100);
    };

    switch(zip_case) {
        case 1 :    //iframe을 이용하여 페이지에 끼워 넣기
            var daum_pape_id = 'daum_juso_page'+frm_zip,
                element_wrap = document.getElementById(daum_pape_id),
                currentScroll = Math.max(document.body.scrollTop, document.documentElement.scrollTop);
            if (element_wrap == null) {
                element_wrap = document.createElement("div");
                element_wrap.setAttribute("id", daum_pape_id);
                element_wrap.style.cssText = 'display:none;border:1px solid;left:0;width:100%;height:300px;margin:5px 0;position:relative;-webkit-overflow-scrolling:touch;';
                element_wrap.innerHTML = '<img src="//t1.daumcdn.net/postcode/resource/images/close.png" id="btnFoldWrap" style="cursor:pointer;position:absolute;right:0px;top:-21px;z-index:1" class="close_daum_juso" alt="접기 버튼">';
                jQuery('form[name="'+frm_name+'"]').find('input[name="'+frm_addr1+'"]').before(element_wrap);
                jQuery("#"+daum_pape_id).off("click", ".close_daum_juso").on("click", ".close_daum_juso", function(e){
                    e.preventDefault();
                    $("#meta_viewport").attr("content", vContent);
                    jQuery(this).parent().hide();
                });
            }

            new daum.Postcode({
                oncomplete: function(data) {
                    complete_fn(data);
                    // iframe을 넣은 element를 안보이게 한다.
                    element_wrap.style.display = 'none';
                    // 우편번호 찾기 화면이 보이기 이전으로 scroll 위치를 되돌린다.
                    document.body.scrollTop = currentScroll;
                },
                // 우편번호 찾기 화면 크기가 조정되었을때 실행할 코드를 작성하는 부분.
                // iframe을 넣은 element의 높이값을 조정한다.
                onresize : function(size) {
                    element_wrap.style.height = size.height + "px";
                },
                maxSuggestItems : g5_is_mobile ? 6 : 10,
                width : '100%',
                height : '100%'
            }).embed(element_wrap);

            // iframe을 넣은 element를 보이게 한다.
            element_wrap.style.display = 'block';
            break;
        case 2 :    //새창으로 띄우기
            new daum.Postcode({
                oncomplete: function(data) {
                    complete_fn(data);
                }
            }).open();
            break;
        default :   //iframe을 이용하여 레이어 띄우기
            var rayer_id = 'daum_juso_rayer'+frm_zip,
                element_layer = document.getElementById(rayer_id);
            if (element_layer == null) {
                element_layer = document.createElement("div");
                element_layer.setAttribute("id", rayer_id);
                element_layer.style.cssText = 'display:none;border:5px solid;position:fixed;width:300px;height:460px;left:50%;margin-left:-155px;top:50%;margin-top:-235px;overflow:hidden;-webkit-overflow-scrolling:touch;z-index:10000';
                element_layer.innerHTML = '<img src="//i1.daumcdn.net/localimg/localimages/07/postcode/320/close.png" id="btnCloseLayer" style="cursor:pointer;position:absolute;right:-3px;top:-3px;z-index:1" class="close_daum_juso" alt="닫기 버튼">';
                document.body.appendChild(element_layer);
                jQuery("#"+rayer_id).off("click", ".close_daum_juso").on("click", ".close_daum_juso", function(e){
                    e.preventDefault();
                    $("#meta_viewport").attr("content", vContent);
                    jQuery(this).parent().hide();
                });
            }

            new daum.Postcode({
                oncomplete: function(data) {
                    complete_fn(data);
                    // iframe을 넣은 element를 안보이게 한다.
                    element_layer.style.display = 'none';
                },
                maxSuggestItems : g5_is_mobile ? 6 : 10,
                width : '100%',
                height : '100%'
            }).embed(element_layer);

            // iframe을 넣은 element를 보이게 한다.
            element_layer.style.display = 'block';
    }
}

/**
 * 새로운 비밀번호 분실 창 : 101123
 **/
win_password_lost = function(href)
{
    var new_win = window.open(href, 'win_password_lost', 'width=617, height=330, scrollbars=1');
    new_win.focus();
}

/**
 * 설문조사 결과
 **/
var win_poll = function(href) {
    var new_win = window.open(href, 'win_poll', 'width=616, height=500, scrollbars=1');
    new_win.focus();
}

/**
 * 쿠폰
 **/
var win_coupon = function(href) {
    var new_win = window.open(href, "win_coupon", "left=100,top=100,width=700, height=600, scrollbars=1");
    new_win.focus();
}


/**
 * 스크린리더 미사용자를 위한 스크립트 - 지운아빠 2013-04-22
 * alt 값만 갖는 그래픽 링크에 마우스오버 시 title 값 부여, 마우스아웃 시 title 값 제거
 **/
$(function() {
    $('a img').mouseover(function() {
        $a_img_title = $(this).attr('alt');
        $(this).attr('title', $a_img_title);
    }).mouseout(function() {
        $(this).attr('title', '');
    });
});

/**
 * 텍스트 리사이즈
**/
function font_resize(id, rmv_class, add_class, othis)
{
    var $el = $("#"+id);

	if((typeof rmv_class !== "undefined" && rmv_class) || (typeof add_class !== "undefined" && add_class)){
		$el.removeClass(rmv_class).addClass(add_class);

		set_cookie("ck_font_resize_rmv_class", rmv_class, 1, g5_cookie_domain);
		set_cookie("ck_font_resize_add_class", add_class, 1, g5_cookie_domain);
	}

    if(typeof othis !== "undefined"){
        $(othis).addClass('select').siblings().removeClass('select');
    }
}

/**
 * 댓글 수정 토큰
**/
function set_comment_token(f)
{
    if(typeof f.token === "undefined")
        $(f).prepend('<input type="hidden" name="token" value="">');

    $.ajax({
        url: g5_bbs_url+"/ajax.comment_token.php",
        type: "GET",
        dataType: "json",
        async: false,
        cache: false,
        success: function(data, textStatus) {
            f.token.value = data.token;
        }
    });
}

$(function(){
    $(".win_point").click(function() {
        win_point(this.href);
        return false;
    });

    $(".win_memo").click(function() {
        win_memo(this.href);
        return false;
    });

    $(".win_email").click(function() {
        win_email(this.href);
        return false;
    });

    $(".win_scrap").click(function() {
        win_scrap(this.href);
        return false;
    });

    $(".win_profile").click(function() {
        win_profile(this.href);
        return false;
    });

    $(".win_homepage").click(function() {
        win_homepage(this.href);
        return false;
    });

    $(".win_password_lost").click(function() {
        win_password_lost(this.href);
        return false;
    });

    /*
    $(".win_poll").click(function() {
        win_poll(this.href);
        return false;
    });
    */

    $(".win_coupon").click(function() {
        win_coupon(this.href);
        return false;
    });

    // 사이드뷰
    var sv_hide = false;
    $(".sv_member, .sv_guest").click(function() {
        $(".sv").removeClass("sv_on");
        $(this).closest(".sv_wrap").find(".sv").addClass("sv_on");
    });

    $(".sv, .sv_wrap").hover(
        function() {
            sv_hide = false;
        },
        function() {
            sv_hide = true;
        }
    );

    $(".sv_member, .sv_guest").focusin(function() {
        sv_hide = false;
        $(".sv").removeClass("sv_on");
        $(this).closest(".sv_wrap").find(".sv").addClass("sv_on");
    });

    $(".sv a").focusin(function() {
        sv_hide = false;
    });

    $(".sv a").focusout(function() {
        sv_hide = true;
    });

    // 셀렉트 ul
    var sel_hide = false;
    $('.sel_btn').click(function() {
        $('.sel_ul').removeClass('sel_on');
        $(this).siblings('.sel_ul').addClass('sel_on');
    });

    $(".sel_wrap").hover(
        function() {
            sel_hide = false;
        },
        function() {
            sel_hide = true;
        }
    );

    $('.sel_a').focusin(function() {
        sel_hide = false;
    });

    $('.sel_a').focusout(function() {
        sel_hide = true;
    });

    $(document).click(function() {
        if(sv_hide) { // 사이드뷰 해제
            $(".sv").removeClass("sv_on");
        }
        if (sel_hide) { // 셀렉트 ul 해제
            $('.sel_ul').removeClass('sel_on');
        }
    });

    $(document).focusin(function() {
        if(sv_hide) { // 사이드뷰 해제
            $(".sv").removeClass("sv_on");
        }
        if (sel_hide) { // 셀렉트 ul 해제
            $('.sel_ul').removeClass('sel_on');
        }
    });

    $(document).on( "keyup change", "textarea#wr_content[maxlength]", function(){
        var str = $(this).val();
        var mx = parseInt($(this).attr("maxlength"));
        if (str.length > mx) {
            $(this).val(str.substr(0, mx));
            return false;
        }
    });
});

function get_write_token(bo_table)
{
    var token = "";

    $.ajax({
        type: "POST",
        url: g5_bbs_url+"/write_token.php",
        data: { bo_table: bo_table },
        cache: false,
        async: false,
        dataType: "json",
        success: function(data) {
            if(data.error) {
                alert(data.error);
                if(data.url)
                    document.location.href = data.url;

                return false;
            }

            token = data.token;
        }
    });

    return token;
}

$(function() {
    $(document).on("click", "form[name=fwrite] input:submit, form[name=fwrite] button:submit, form[name=fwrite] input:image", function() {
        var f = this.form;

        if (typeof(f.bo_table) == "undefined") {
            return;
        }

        var bo_table = f.bo_table.value;
        var token = get_write_token(bo_table);

        if(!token) {
            alert("토큰 정보가 올바르지 않습니다.");
            return false;
        }

        var $f = $(f);

        if(typeof f.token === "undefined")
            $f.prepend('<input type="hidden" name="token" value="">');

        $f.find("input[name=token]").val(token);

        return true;
    });
});

function getTopNScores(arr, n) {
    return arr
        .slice() // 원본 배열 보호
        .sort((a, b) => b.score - a.score) // 점수 내림차순 정렬
        .slice(0, n); // 상위 n개만 추출
}

function formatScore(value,rate,his=0) {
    // const rounded = Math.round((value*(rate/100)) * 100) / 100; // 소수 둘째 자리에서 반올림
    // const final = Math.round(rounded * 10) / 10;   // 소수 첫째 자리에서 다시 반올림
    const final = Math.floor((value * (rate / 100) + his) * 10) / 10;

  
    // 정수면 소수점 없이, 아니면 소수 첫째 자리까지
    return Number.isInteger(final) ? String(final) : final.toFixed(1);
  }
  
function getTransScore(val, rate){
    let res = 0;
    if(rate.includes('33')){
        res = val / 3;
    } else if(rate.includes('66')){
        res = (val / 3) * 2;
    } else {
        res = val * (rate/100);
    }

    return res;
}
  

// subArr[] 
//  0 - 국
//  1 - 수
//  2 - 탐1
//  3 - 탐2
//  4 - 영어
//  5 - 한국사

// 
//  TopRate - 최고표점
//  Pscore - 표점
//  Sscore - 백분위
//  Grade - 등급
//  Rate - 반영 비율
//  Score - type에 따른 변환 점수
//  TransScore - 최종 변환점수

// TopPs - 최고표점, Ps - 표점, Ss - 백분위, Gr - 등급
// tamCnt - 1 : 둘 중 높은점수로, 2 : 두 과목 평균점수로
// sPercent - 수능 비율 , nPercent - 내신 비율, pPercent - 실기 비율, oPercent - 기타 비율
// total - 전형 총점
// korType - 국어 백,표,최, mathType - 수학 백,표,최, tamType - 탐구 백,표,최
// hisPM - 한국사 가감점
// korRate - 국어 반영비율, mathRate - 수학 반영비율, engRate - 영어 반영비율, tamRate - 탐구 반영비율
// TransScore - 최종 변환 점수, Score - type에 따른 변환 점수

function calcJuScore(json){
    let science = ['물리1','화학1','생명과학1','지구과학1','물리2','화학2','생명과학2','지구과학2']; // 과탐
    let social = ['생활과윤리','윤리와사상','한국지리','세계지리','동아시아사','세계사','정치와법','경제','사회문화']; // 사탐

    let tam1 = $("input[name='tam1Sub']").val();
    let tam2 = $("input[name='tam2Sub']").val();

    if(science.includes(tam1)){
        tam1 = "과탐";
    } else if(social.includes(tam1)){
        tam1 = "사탐";
    } else {
        tam1 = "직탐";
    }

    if(science.includes(tam2)){
        tam2 = "과탐";
    } else if(social.includes(tam2)){
        tam2 = "사탐";
    } else {
        tam2 = "직탐";
    }
    
    let subArr = [];
    let subNm = ['kor','math','tam1','tam2','eng','his'];
    for(let u = 0; u < subNm.length; u++){
        subArr.push({
            'TopRate' : $(`input[name='${subNm[u]}_TopRate']`).val(),
            'Pscore' : $(`input[name='${subNm[u]}_Pscore']`).val(),
            'Sscore' : $(`input[name='${subNm[u]}_Sscore']`).val(),
            'Grade' : $(`input[name='${subNm[u]}_Grade']`).val() - 1,
            'Rate': 0,
            'Score' : 0,
            'TransScore' : 0
        });
    }
    
    let changeScore = 0;
    for(let i = 0; i < json.length; i++){
        changeScore = 0;
        subArr[0]['Score'] = 0;
        subArr[1]['Score'] = 0;
        subArr[2]['Score'] = 0;
        subArr[3]['Score'] = 0;
        subArr[4]['Score'] = 0;
        subArr[5]['Score'] = 0;

        subArr[0]['TransScore'] = 0;
        subArr[1]['TransScore'] = 0;
        subArr[2]['TransScore'] = 0;
        subArr[3]['TransScore'] = 0;
        subArr[4]['TransScore'] = 0;
        subArr[5]['TransScore'] = 0;

        subArr[0]['Rate'] = json[i]['juKorrate'];
        subArr[1]['Rate'] = json[i]['juMathrate'];
        subArr[2]['Rate'] = json[i]['juTamrate'];
        subArr[3]['Rate'] = json[i]['juTamrate'];
        subArr[4]['Rate'] = json[i]['juEngrate'];
        
        switch(json[i]['juChar']){
            case '표최': case '표':
                subArr[0]['Score'] = (subArr[0]['Pscore']/subArr[0]['TopRate'])*json[i]['juTotal'];
                subArr[1]['Score'] = (subArr[1]['Pscore']/subArr[1]['TopRate'])*json[i]['juTotal'];
                break;
            case '표200':
                subArr[0]['Score'] = (subArr[0]['Pscore']/200)*json[i]['juTotal'];
                subArr[1]['Score'] = (subArr[1]['Pscore']/200)*json[i]['juTotal'];
                break;
            case '백':
                subArr[0]['Score'] = (subArr[0]['Sscore']/100)*json[i]['juTotal'];
                subArr[1]['Score'] = (subArr[1]['Sscore']/100)*json[i]['juTotal'];
                break;
            case '등급':
                subArr[0]['Score'] = subArr[0]['Grade']*json[i]['juTotal'];
                subArr[1]['Score'] = subArr[1]['Grade']*json[i]['juTotal'];
                break;
            case '변표최':
                subArr[0]['Score'] = "";
                subArr[1]['Score'] = "";
                break;
            case '변표200':
                subArr[0]['Score'] = "";
                subArr[1]['Score'] = "";
                break;
            default:
                subArr[0]['Score'] = "";
                subArr[1]['Score'] = "";
                break;
        }

        switch(json[i]['juTamChar']){
            case '표최': case '표':
                if(json[i]['juTamSub'] == tam1){
                    subArr[2]['Score'] = 0;
                } else {
                    subArr[2]['Score'] = (subArr[2]['Pscore']/subArr[2]['TopRate'])*json[i]['juTotal'];
                }
                if(json[i]['juTamSub'] == tam2){
                    subArr[3]['Score'] = 0;
                }else {
                    subArr[3]['Score'] = (subArr[3]['Pscore']/subArr[3]['TopRate'])*json[i]['juTotal'];
                }
                break;
            case '표200':
                if(json[i]['juTamSub'] == tam1){
                    subArr[2]['Score'] = 0;
                } else {
                    subArr[2]['Score'] = (subArr[2]['Pscore']/200)*json[i]['juTotal'];
                }
                if(json[i]['juTamSub'] == tam2){
                    subArr[3]['Score'] = 0;
                }else {
                    subArr[3]['Score'] = (subArr[3]['Pscore']/200)*json[i]['juTotal'];
                }
                break;
            case '백':
                if(json[i]['juTamSub'] == tam1){
                    subArr[2]['Score'] = 0;
                } else {
                    subArr[2]['Score'] = (subArr[2]['Sscore']/100)*json[i]['juTotal'];
                }
                if(json[i]['juTamSub'] == tam2){
                    subArr[3]['Score'] = 0;
                }else {
                    subArr[3]['Score'] = (subArr[3]['Sscore']/100)*json[i]['juTotal'];
                }
                break;
            case '등급':
                if(json[i]['juTamSub'] == tam1){
                    subArr[2]['Score'] = 0;
                } else {
                    subArr[2]['Score'] = subArr[2]['Grade']*json[i]['juTotal'];
                }
                if(json[i]['juTamSub'] == tam2){
                    subArr[3]['Score'] = 0;
                }else {
                    subArr[3]['Score'] = subArr[3]['Grade']*json[i]['juTotal'];
                }
                break;
            case '변표최':
                if(json[i]['juTamSub'] == tam1){
                    subArr[2]['Score'] = 0;
                } else {
                    subArr[2]['Score'] = (transDatas['transData'][json[i]['subIdx']]['data'][tam1]['data'][Math.round(subArr[2]['Sscore'])]['transScore']/transDatas['transData'][json[i]['subIdx']]['data'][tam1]['data'][100]['transScore'])*json[i]['juTotal'];
                }
                if(json[i]['juTamSub'] == tam2){
                    subArr[3]['Score'] = 0;
                }else {
                    subArr[3]['Score'] = (transDatas['transData'][json[i]['subIdx']]['data'][tam2]['data'][Math.round(subArr[3]['Sscore'])]['transScore']/transDatas['transData'][json[i]['subIdx']]['data'][tam2]['data'][100]['transScore'])*json[i]['juTotal'];
                }
                break;
            case '변표200':
                if(json[i]['juTamSub'] == tam1){
                    subArr[2]['Score'] = 0;
                } else {
                    subArr[2]['Score'] = (transDatas['transData'][json[i]['subIdx']]['data'][tam1]['data'][Math.round(subArr[2]['Sscore'])]['transScore']/200)*json[i]['juTotal'];
                }
                if(json[i]['juTamSub'] == tam2){
                    subArr[3]['Score'] = 0;
                }else {
                    subArr[3]['Score'] = (transDatas['transData'][json[i]['subIdx']]['data'][tam2]['data'][Math.round(subArr[3]['Sscore'])]['transScore']/200)*json[i]['juTotal'];
                }
                break;
            default:
                subArr[2]['Score'] = "";
                subArr[3]['Score'] = "";
                break;
        }
        if(json[i]['engList']){
            if(subArr[4]['Rate'].includes('점')){
                subArr[4]['Score'] = Number(json[i]['engList'].split(",")[subArr[4]['Grade']]);
            } else {
                subArr[4]['Score'] = Number(json[i]['engList'].split(",")[subArr[4]['Grade']])*(json[i]['juTotal']/json[i]['engList'].split(",")[0]);
            }
        }

        if(json[i]['histList']){
            subArr[5]['Score'] = Number(json[i]['histList'].split(",")[subArr[5]['Grade']]);
        }
        
    
        let selPil = [];
    
        switch(json[i]['juKorSelect']){
            case '필수':
                subArr[0]['TransScore'] = getTransScore(subArr[0]['Score'],(subArr[0]['Rate'].split("%")[0]));
                break;
            case '선택':
                selPil.push(0);
                break;
        }
    
        switch(json[i]['juMathSelect']){
            case '필수':
                subArr[1]['TransScore'] = getTransScore(subArr[1]['Score'],(subArr[1]['Rate'].split("%")[0]));
                break;
            case '선택':
                selPil.push(1);
                break;
        }
    
        switch(json[i]['juEngSelect']){
            case '필수':
                if(subArr[4]['Rate'].includes('점')){
                    subArr[4]['TransScore'] = subArr[4]['Score'];
                } else {
                    subArr[4]['TransScore'] = getTransScore(subArr[4]['Score'],(subArr[4]['Rate'].split("%")[0]));
                }
                break;
            case '선택':
                selPil.push(4);
                break;
        }
    
        switch(json[i]['juTamSelect']){
            case '필수':
                if(json[i]['juTamCnt'] == 1){
                    if(subArr[2]['Score'] >= subArr[3]['Score']){
                        subArr[2]['TransScore'] = getTransScore(subArr[2]['Score'],(subArr[2]['Rate'].split("%")[0]));
                    } else {
                        subArr[3]['TransScore'] = getTransScore(subArr[3]['Score'],(subArr[3]['Rate'].split("%")[0]));
                    }                
                } else if(json[i]['juTamCnt'] == 2){
                    subArr[2]['TransScore'] = getTransScore((subArr[2]['Score'] + subArr[3]['Score']) / 2,(subArr[3]['Rate'].split("%")[0]));
                }
                break;
            case '선택':
                selPil.push(2);
                break;
        }
        switch(json[i]['juHisSelect']){
            case '필수':
                subArr[5]['TransScore'] = subArr[5]['Score'];
                break;
            case '선택':
                selPil.push(5);
                break;
        }
        
        if(selPil.length > 0){
            let sumbs = 0;
            let selCnt = ""; // 선택할 과목 수
            let selSubScores = [];
            if(subArr[selPil[0]]['Rate'].includes(',')){
                selCnt = subArr[selPil[0]]['Rate'].split(',').length;
            } else {
                if(selPil.length == 2){
                    selCnt = 1;
                } else {
                    for(let q = 0; q < selPil.length; q++){
                        sumbs += Number(subArr[selPil[0]]['Rate'].split("%")[0]);
                        if(sumbs <= 100){
                            selCnt++;
                        }
                    }
                }
            }
            for(let j = 0; j < selPil.length ;j++){
                let score = subArr[selPil[j]]['Score'];
                if(selPil[j] == 2){
                    if(json[i]['juTamCnt'] == 1){
                        if(subArr[2]['Score']>=subArr[3]['Score']){
                            score = subArr[2]['Score'];
                        } else {
                            score = subArr[3]['Score'];
                        }
                    } else {
                        score = (subArr[2]['Score'] + subArr[3]['Score'])/2;
                    }
                }
                selSubScores.push({
                   'score' : score,
                   'idx' : selPil[j]
                }); // 선택 과목들 변환 점수
            }

            let rSubs = getTopNScores(selSubScores,selCnt);
            console.log(rSubs);
            if(subArr[selPil[0]]['Rate'].includes(',')){
                for(let t = 0; t < subArr[selPil[0]]['Rate'].split(',').length; t++){
                    subArr[rSubs[t]['idx']]['TransScore']= getTransScore(rSubs[t]['score'],subArr[selPil[0]]['Rate'].split(',')[t].split("%")[0]);
                }
            } else {
                for(let t = 0; t < rSubs.length; t++){
                    subArr[rSubs[t]['idx']]['TransScore']= getTransScore(rSubs[t]['score'],subArr[selPil[0]]['Rate'].split("%")[0]);
                }
            }
        }
        for(let o = 0; o < 5; o++){
            changeScore += subArr[o]['TransScore'];
        }
        console.log(subArr);
        let popTam = "";
        if(json[i]['juTamSub'] == tam1 || json[i]['juTamSub'] == tam2){
            popTam = "<br><span style='color:red;'>(" + (tam1 ? tam1 : tam2) + "제외)</span>";
        }
        $(`.changeScore${i+1}`).html(formatScore(changeScore,json[i]['juSrate'].split("%")[0],subArr[5]['TransScore']) > json[i]['juTotal']*(json[i]['juSrate'].split("%")[0]/100) ? json[i]['juTotal']*(json[i]['juSrate'].split("%")[0]/100) : formatScore(changeScore,json[i]['juSrate'].split("%")[0],subArr[5]['TransScore']) + popTam);
    }
}
function checkScroll(){
    let div = document.getElementById('scrollTopBtn').parentElement;
    let btn = document.getElementById('scrollTopBtn');

    if(div.scrollHeight > div.clientHeight){
        btn.style.display = '';
    } else {
        btn.style.display = 'none';
    }
}
function goScrollTop(e){
    let div = e.currentTarget.parentNode;
    div.scrollTo({ top: 0, behavior: 'smooth' });
}
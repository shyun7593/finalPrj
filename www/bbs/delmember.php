<?php
include_once('./_common.php');


$mb_no = $_POST['mb_no'];

$mb = sql_fetch("SELECT * FROM g5_member WHERE mb_no = {$mb_no}");

// 추천, 관심대학 삭제
sql_query("DELETE FROM g5_add_college WHERE memId = '{$mb['mb_id']}'");
sql_query("DELETE FROM g5_add_college WHERE regId = '{$mb['mb_id']}'");

// 대학별 실기정보 삭제
sql_query("DELETE FROM g5_college_silgi WHERE memId = '{$mb['mb_id']}'");

// 모의고사 점수 삭제
sql_query("DELETE FROM g5_member_score WHERE insID = '{$mb['mb_id']}'");

// 실기테스트 정보 삭제
sql_query("DELETE FROM g5_student_Practice WHERE memberIdx = '{$mb['mb_id']}'");

// 게시판 삭제
sql_query("DELETE FROM g5_notice WHERE regid = '{$mb['mb_id']}'");
sql_query("DELETE FROM g5_notice_read WHERE memIdx = '{$mb_no}'");

// 상담내역 삭제
sql_query("DELETE FROM g5_member_note WHERE mbIdx = '{$mb_no}'");
sql_query("DELETE FROM g5_memo WHERE memberIdx = '{$mb_no}'");

// 계정 삭제
sql_query("DELETE FROM g5_member WHERE mb_no = {$mb_no}");

echo 'success';
exit;
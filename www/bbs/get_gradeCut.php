<?php
include_once('./_common.php');

$code = $_POST['subjectCode'];
$month = $_POST['month'];
$score = $_POST['score'];

$row = sql_fetch("SELECT gradeScore AS origin, gradeSscore AS sscore, gradePscore AS pscore, gGrade 
                  FROM g5_gradeCut 
                  WHERE gradeCode = '{$code}' AND gradeType = '{$month}' AND gradeScore = '{$score}'");

echo json_encode($row);
?>

<?php
require_once "./_common.php";

// 예: $_POST 로 넘어온 값 처리
$mb_id      = $_POST['mb_id'] ?? '';
$mb_nick    = $_POST['mb_nick'] ?? '';
$bo_table   = $_POST['bo_table'] ?? '';
$c_datetime = date('Y-m-d H:i:s');
$c_date     = date('Y-m-d');
$mb_year    = $_POST['mb_year'] ?? null;
$mb_company = $_POST['mb_company'] ?? '';
$mb_type    = $_POST['mb_type'] ?? '';
$mb_name    = $_POST['mb_name'] ?? '';
$mb_1       = $_POST['mb_1'] ?? '';
$mb_2       = $_POST['mb_2'] ?? '';
$mb_3       = $_POST['mb_3'] ?? '';

$sql_common = "
    mb_id      = '{$mb_id}',
    mb_nick    = '{$mb_nick}',
    bo_table   = '{$bo_table}',
    c_datetime = '{$c_datetime}',
    c_date     = '{$c_date}',
    mb_year    = " . ($mb_year ? "'{$mb_year}'" : "NULL") . ",
    mb_company = '{$mb_company}',
    mb_type    = '{$mb_type}',
    mb_name    = '{$mb_name}',
    mb_1       = '{$mb_1}',
    mb_2       = '{$mb_2}',
    mb_3       = '{$mb_3}'
";

if ($w == '') { 
    // 새로 등록
    $sql = "INSERT INTO qr_member_student SET {$sql_common}";
    sql_query($sql);

} else if ($w == 'u') {
    // 수정 (예: idx 기준)
    $idx = intval($_POST['idx']);
    $sql = "UPDATE qr_member_student SET {$sql_common} WHERE idx = '{$idx}'";
    sql_query($sql);
}


goto_url('./member_student_list.php?' . $qstr . '&amp;w=u&amp;mb_id=' . $mb_id, false);
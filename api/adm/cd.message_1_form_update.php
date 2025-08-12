<?php
$sub_menu = "300310";
include_once('./_common.php');

if ($w == 'u')
    check_demo();

auth_check($auth[$sub_menu], 'w');

check_admin_token();


$lno = isset($_POST['lno']) ? trim($_POST['lno']) : '';
$lssn_kind = "LS02";
$lssn_title = isset($_POST['lssn_title']) ? trim(strip_tags($_POST['lssn_title'])) : '';
$lssn_total = isset($_POST['lssn_total']) ? trim(strip_tags($_POST['lssn_total'])) : '';
$lssn_class = isset($_POST['lssn_class']) ? trim(strip_tags($_POST['lssn_class'])) : '';
$lssn_sdate = isset($_POST['lssn_sdate']) ? trim(strip_tags($_POST['lssn_sdate'])) : '';
$lssn_edate = isset($_POST['lssn_edate']) ? trim(strip_tags($_POST['lssn_edate'])) : '';
$lssn_time = isset($_POST['lssn_time']) ? trim(strip_tags($_POST['lssn_time'])) : '';
$lssn_cond = isset($_POST['lssn_cond']) ? trim(strip_tags($_POST['lssn_cond'])) : '';
$lssn_point = isset($_POST['lssn_point']) ? trim(strip_tags($_POST['lssn_point'])) : '';
$lssn_regdate = isset($_POST['lssn_regdate']) ? trim(strip_tags($_POST['lssn_regdate'])) : '';
$lssn_link = isset($_POST['lssn_link']) ? trim(strip_tags($_POST['lssn_link'])) : '';
$lssn_img_link = isset($_POST['lssn_img_link']) ? trim(strip_tags($_POST['lssn_img_link'])) : '';
$lssn_view = isset($_POST['lssn_view']) ? trim(strip_tags($_POST['lssn_view'])) : '';
$lssn_company = isset($_POST['lssn_company']) ? trim(strip_tags($_POST['lssn_company'])) : '';
$lssn_div = isset($_POST['lssn_div']) ? trim(strip_tags($_POST['lssn_div'])) : '';
$lssn_status = isset($_POST['lssn_status']) ? trim(strip_tags($_POST['lssn_status'])) : '';
$lssn_year = isset($_POST['lssn_year']) ? trim(strip_tags($_POST['lssn_year'])) : '';




$sql_common =	"	lssn_kind = '{$lssn_kind}',
					lssn_div = '{$lssn_div}',
					lssn_status = '{$lssn_status}',
					lssn_year = '{$lssn_year}',
					lssn_company = '{$lssn_company}',
					lssn_title = '{$lssn_title}',
					lssn_total = '{$lssn_total}',
					lssn_sdate = '{$lssn_sdate}',
					lssn_class = '{$lssn_class}',
					lssn_edate = '{$lssn_edate}',
					lssn_time = '{$lssn_time}',
					lssn_cond = '{$lssn_cond}',
					lssn_point = '{$lssn_point}',
					lssn_rdate = '{$lssn_regdate}',
					lssn_url = '{$lssn_link}',
					lssn_rimg = '{$lssn_img_link}',
					lssn_view = '{$lssn_view}'
				";

if ($w == '') 
{
	//echo "insert into {$g5['lesson_table']} set {$sql_common} ";
	//exit();
	sql_query(" insert into {$g5['lesson_table']} set {$sql_common} ");
}
else if ($w == 'u')
{
	sql_query(" update {$g5['lesson_table']} set {$sql_common} where lssn_no = {$lno} ");
}

// echo "update {$g5['lesson_table']} set {$sql_common} where lssn_no = {$lno}";
// return;
goto_url('./cd.message_list1.php?'.$qstr.'&amp;w=u&amp;', false);
<?php
$sub_menu = "300310";
include_once('./_common.php');
$timestamp = time();
if ($w == 'u')
    check_demo();

auth_check($auth[$sub_menu], 'w');

check_admin_token();


$idx = isset($_POST['idx']) ? trim($_POST['idx']) : '';
$lssn_kind = "LS02";
$cd_name = isset($_POST['cd_name']) ? trim(strip_tags($_POST['cd_name'])) : '';
$cd_code = isset($_POST['cd_code']) ? trim(strip_tags($_POST['cd_code'])) : '';
$cd_date = isset($_POST['cd_date']) ? trim(strip_tags($_POST['cd_date'])) : '';
$mode = isset($_POST['mode']) ? trim(strip_tags($_POST['mode'])) : '';







if($mode == 'cd_co_cate') {
$sql_common =	"	cd_name = '{$cd_name}',
					cd_code = '{$cd_code}',
					cd_date = '{$cd_date}'
				";

if ($w == '') 
{
	//echo "insert into cd_co_cate set {$sql_common} ";
	//exit();
	sql_query(" insert into cd_co_cate set {$sql_common} ");
}
else if ($w == 'u')
{
	sql_query(" update cd_co_cate set {$sql_common} where idx = {$idx} ");
}

// echo "update cd_co_cate set {$sql_common} where lssn_no = {$idx}";
// return;
goto_url('./co_cat.php?'.$qstr.'&amp;w=u&amp;', false);
}




if($mode == 'cd_co_cate2') {
$sql_common =	"	cd_name = '{$cd_name}',
					cd_idx = '{$cd_idx}',
					cd_code = '{$cd_code}',
					cd_date = '{$cd_date}'
				";

if ($w == '') 
{
	//echo "insert into cd_co_cate set {$sql_common} ";
	//exit();
	sql_query(" insert into cd_co_cate2 set {$sql_common} ");
}
else if ($w == 'u')
{
	sql_query(" update cd_co_cate2 set {$sql_common} where idx = {$idx} ");
}

// echo "update cd_co_cate set {$sql_common} where lssn_no = {$idx}";
// return;
goto_url('./co_cat2.php?'.$qstr.'&amp;w=u&amp;', false);
}



if($mode == 'cd_co_cate3') {
$sql_common =	"	cd_name = '{$cd_name}',
					cd_idx = '{$cd_idx}',
					cd_code = '{$cd_code}',
					cd_date = '{$cd_date}'
				";

if ($w == '') 
{
	//echo "insert into cd_co_cate set {$sql_common} ";
	//exit();
	sql_query(" insert into cd_co_cate3 set {$sql_common} ");
}
else if ($w == 'u')
{
	sql_query(" update cd_co_cate3 set {$sql_common} where idx = {$idx} ");
}

// echo "update cd_co_cate set {$sql_common} where lssn_no = {$idx}";
// return;
goto_url('./co_cat3.php?'.$qstr.'&amp;w=u&amp;', false);
}


if($mode == 'cd_co_cate4') {
$sql_common =	"	cd_name = '{$cd_name}',
cd_idx = '{$cd_cate3}',
cd_file = '{$cd_file}',
cd_connect = '{$cd_connect}',
cd_time = '{$cd_time}',
cd_date = '{$cd_date}',
cd_dev_date = '{$cd_dev_date}',
cd_cate1 = '{$cd_cate1}',
cd_cate2 = '{$cd_cate2}',
cd_cate3 = '{$cd_cate3}'
				";

if ($w == '') 
{
	//echo "insert into cd_co_cate set {$sql_common} ";
	//exit();
	sql_query(" insert into cd_co_cate4 set {$sql_common} ");
}
else if ($w == 'u')
{
	sql_query(" update cd_co_cate4 set {$sql_common} where idx = {$idx} ");
}

// echo "update cd_co_cate set {$sql_common} where lssn_no = {$idx}";
// return;
goto_url('./co_list.php?'.$qstr.'&amp;w=u&amp;', false);
}

if($mode == 'cd_co_detail') {
$sql_common = "  ";
if(isset($_FILES['cd_thum'])) {
            $ext = substr($_FILES['cd_thum']['name'], strrpos($_FILES['cd_thum']['name'], '.') + 1); 
            $file_name = $timestamp.'.'.$ext;
            $uploaddir = "../data/";
            $uploadfile = $uploaddir . basename($file_name);
            if (move_uploaded_file($_FILES['cd_thum']['tmp_name'], $uploadfile)) {
            
            } else {
                print "ERROR";
            }
            $sql_common = " cd_thum = '{$file_name}', ";
        }

$sql_common .=	"
cd_cate1 = '{$cd_cate1}',
cd_cate2 = '{$cd_cate2}',
cd_cate3 = '{$cd_cate3}',
cd_cate4 = '{$cd_cate4}',
cd_title = '{$cd_title}',
cd_w = '{$cd_w}',
cd_h = '{$cd_h}',
cd_use = '{$cd_use}',
cd_stu = '{$cd_stu}',
cd_file = '{$cd_file}',
cd_ym = '{$cd_ym}',
cd_time = '{$cd_time}',
cd_content = '{$cd_content}',
cd_target = '{$cd_target}',
cd_con_list = '{$cd_con_list}',
cd_age = '{$cd_age}',
cd_expert = '{$cd_expert}'
				";

if ($w == '') 
{
	//echo "insert into cd_co_cate set {$sql_common} ";
	//exit();
	sql_query(" insert into cd_co_detail set {$sql_common} ");
}
else if ($w == 'u')
{
	sql_query(" update cd_co_detail set {$sql_common} where idx = {$idx} ");
}

// echo "update cd_co_cate set {$sql_common} where lssn_no = {$idx}";
// return;
goto_url('./co_detail.php?'.$qstr.'&amp;w=u&amp;', false);
}
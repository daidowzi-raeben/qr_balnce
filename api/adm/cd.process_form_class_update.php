<?php
$sub_menu = "300110";
include_once('./_common.php');

if ($w == 'u')
    check_demo();

auth_check($auth[$sub_menu], 'w');

check_admin_token();


$lno = isset($_POST['lno']) ? trim($_POST['lno']) : '';
$cno = isset($_POST['lno']) ? trim($_POST['cno']) : '';
$lssn_kind = "LS01";
$c_name = isset($_POST['c_name']) ? trim(strip_tags($_POST['c_name'])) : '';
$c_page = isset($_POST['c_page']) ? trim(strip_tags($_POST['c_page'])) : '';
$cpt_seq = isset($_POST['cpt_seq']) ? trim(strip_tags($_POST['cpt_seq'])) : '';
$lssn_point = isset($_POST['lssn_point']) ? trim(strip_tags($_POST['lssn_point'])) : '';

$c_rdate = isset($_POST['c_rdate']) ? trim(strip_tags($_POST['c_rdate'])) : date("Y-m-d");
$c_udate = isset($_POST['c_udate']) ? trim(strip_tags($_POST['c_udate'])) : date("Y-m-d");




/*
$lssn_time = isset($_POST['lssn_time']) ? trim(strip_tags($_POST['lssn_time'])) : '';
$lssn_cond = isset($_POST['lssn_cond']) ? trim(strip_tags($_POST['lssn_cond'])) : '';
$lssn_point = isset($_POST['lssn_point']) ? trim(strip_tags($_POST['lssn_point'])) : '';
$lssn_regdate = isset($_POST['lssn_regdate']) ? trim(strip_tags($_POST['lssn_regdate'])) : '';
$lssn_link = isset($_POST['lssn_link']) ? trim(strip_tags($_POST['lssn_link'])) : '';
$lssn_img_link = isset($_POST['lssn_img_link']) ? trim(strip_tags($_POST['lssn_img_link'])) : '';
$lssn_view = isset($_POST['lssn_view']) ? trim(strip_tags($_POST['lssn_view'])) : '';
$lssn_company = isset($_POST['lssn_company']) ? trim(strip_tags($_POST['lssn_company'])) : '';
*/

$sql_common =	"	c_name = '{$c_name}',
					c_kind = '{$c_kind}',
					c_url = 'W',
					c_size_w = '1024',
					c_size_h = '768',
					c_runtime = '20',
					c_cont = '',
					c_realfile = '',
					c_userfile = '',
					c_admin = '',
					c_rdate = '{$c_rdate}',
					c_udate = '{$c_udate}',
					c_page = '{$c_page}'
				";

if ($w == '') 
{
	//echo "insert into {$g5['lesson_table']} set {$sql_common} ";
	//exit();
	sql_query(" 
	INSERT INTO `cd_lms_contents` 
	( `c_name`, `c_kind`, `c_type`, `c_url`, `c_size_w`, `c_size_h`, 
	`c_runtime`, `c_page`, `c_cont`, `c_realfile`, `c_userfile`, `c_admin`, 
	`c_rdate`, `c_udate`, `c_view`, `c_use`, `c_del`) VALUES 
	('{$c_name}', '{$c_kind}', 'W', '".$c_url."', 1024, 700, 20, {$c_page}, '', '', '', '', '{$c_rdate}', {$c_udate}, 'N', 'Y', 'N');
	");
}
else if ($w == 'u')
{
	sql_query("
	UPDATE `cd_lms_contents` SET  `c_name`='{$c_name}', `c_kind`='{$c_kind}', `c_type`='W',
	`c_url`='".$c_url."', `c_size_w`=1024, `c_size_h`=700, `c_runtime`=20, `c_page`={$c_page}, 
	`c_cont`='', `c_realfile`='', `c_userfile`='', `c_admin`='', `c_rdate`=NULL, `c_udate`=NULL,

	c_rdate='{$c_rdate}', 
		c_udate='{$c_udate}', 
	
	`c_view`='N', `c_use`='Y', `c_del`='N' WHERE `c_no`={$cno};

	");
}


$sql_d = " select * from cd_lms_contents order by c_no desc limit 1";
$result_d = sql_fetch($sql_d);


$c_name = isset($_POST['c_name']) ? trim(strip_tags($_POST['c_name'])) : '';
$c_page = isset($_POST['c_page']) ? trim(strip_tags($_POST['c_page'])) : '';
$cpt_seq = isset($_POST['cpt_seq']) ? trim(strip_tags($_POST['cpt_seq'])) : '';
$lssn_point = isset($_POST['lssn_point']) ? trim(strip_tags($_POST['lssn_point'])) : '';
/*
$lssn_time = isset($_POST['lssn_time']) ? trim(strip_tags($_POST['lssn_time'])) : '';
$lssn_cond = isset($_POST['lssn_cond']) ? trim(strip_tags($_POST['lssn_cond'])) : '';
$lssn_point = isset($_POST['lssn_point']) ? trim(strip_tags($_POST['lssn_point'])) : '';
$lssn_regdate = isset($_POST['lssn_regdate']) ? trim(strip_tags($_POST['lssn_regdate'])) : '';
$lssn_link = isset($_POST['lssn_link']) ? trim(strip_tags($_POST['lssn_link'])) : '';
$lssn_img_link = isset($_POST['lssn_img_link']) ? trim(strip_tags($_POST['lssn_img_link'])) : '';
$lssn_view = isset($_POST['lssn_view']) ? trim(strip_tags($_POST['lssn_view'])) : '';
$lssn_company = isset($_POST['lssn_company']) ? trim(strip_tags($_POST['lssn_company'])) : '';
*/

$sql_common =	"	c_name = '{$c_name}',
					c_kind = '{$c_kind}',
					c_url = 'W',
					c_size_w = '1024',
					c_size_h = '768',
					c_runtime = '20',
					c_cont = '',
					c_realfile = '',
					c_userfile = '',
					c_admin = '',
					c_rdate = '',
					c_udate = '',

					c_page = '{$c_page}'
				";

if ($w == '') 
{
	//echo "insert into {$g5['lesson_table']} set {$sql_common} ";
	//exit();
	sql_query(" 
	INSERT INTO `cd_lms_chapter` ( `cpt_lesson`, `cpt_seq`, `cpt_contents`, `cpt_quiz`,
	`cpt_type`, `cpt_rdate`, `cpt_use`) VALUES ( '".$_GET['idx']."', '{$cpt_seq}', '".$result_d['c_no']."',0, '', now(), '');
");
}
else if ($w == 'u')
{
	sql_query(" 
	UPDATE `cd_lms_chapter` SET 
 `cpt_lesson`='".$idx."', `cpt_seq`='{$cpt_seq}'
	  WHERE `cpt_contents`={$cno};

	");


}


/*
echo "UPDATE `cd_lms_chapter` SET 
 `cpt_lesson`='".$idx."', `cpt_seq`='{$cpt_seq}',
	`  WHERE `cpt_no`={$lno};";

	echo "<br>
		UPDATE `cd_lms_contents` SET `c_no`=29, `c_name`='{$c_name}', `c_kind`='{$c_kind}', `c_type`='W',
	`c_url`='06', `c_size_w`=1024, `c_size_h`=700, `c_runtime`=20, `c_page`={$c_page}, 
	`c_cont`='', `c_realfile`='', `c_userfile`='', `c_admin`='', `c_rdate`=NULL, `c_udate`=NULL, `c_view`='N', `c_use`='Y', `c_del`='N' WHERE `c_no`={$cno};

	";
exit;
*/
// echo "update {$g5['lesson_table']} set {$sql_common} where lssn_no = {$lno}";
// return;
goto_url('./cd.process_class_list.php?idx='.$idx.'&'.$qstr.'&amp;w=u&amp;', false);
<?php
$sub_menu = "400300";
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, 'w');

check_admin_token();

$lssn_kind = 'LS04';
$sm_no = trim($_POST['sm_no']);
$sm_title = trim($_POST['sm_title']);
$sm_startdate = trim($_POST['sm_startdate']);
$sm_enddate = trim($_POST['sm_enddate']);
$sm_time = trim($_POST['sm_time']);
$sm_active = trim($_POST['sm_active']);
$sm_point = trim($_POST['sm_point']);
$sm_link = isset($_POST['sm_link']) ? $_POST['sm_link'] : '';
$sm_text = isset($_POST['sm_text']) ? $_POST['sm_text'] : '';

$sql_common = "  
				lssn_kind = '{$lssn_kind}',
				lssn_title = '{$sm_title}',
				lssn_intro = '{$sm_text}',
				lssn_sdate = '{$sm_startdate}',
				lssn_edate = '{$sm_enddate}',
				lssn_view = '{$sm_active}',
				lssn_allowtime = '{$sm_time}',
				lssn_url = '{$sm_link}',
				lssn_point = '{$sm_point}' ";

if ($w == '')
{
    sql_query(" insert into {$g5['lesson_table']} set lssn_rdate = '".G5_TIME_YMDHIS."', {$sql_common} ");
}
else if ($w == 'u')
{
	$sm = get_lesson($sm_no);
    
	if (!$sm['lssn_no'])
        alert('존재하지 않는 자료입니다.');

    $sql = " update {$g5['lesson_table']}
                set {$sql_common}
                where lssn_no = '{$sm_no}' ";
    sql_query($sql);
	
}
else
    alert('제대로 된 값이 넘어오지 않았습니다.');

goto_url('./cd.seminar_on_list.php?'.$qstr.'&amp;', false);
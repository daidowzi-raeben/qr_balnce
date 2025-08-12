<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가;

/*************************************************************************
**
**  클린데스크 함수 모음
**
*************************************************************************/

##교육마당 관련
// 설문지 정보를 얻는다.
function get_survey($code)
{
	global $g5;

    return sql_fetch(" select * from {$g5['survey_table']} where srvy_code = TRIM('$code')");
}

//학습자 사이버과정 학습정보 가져오기(아이디, 과정 no)
function get_lessonApply($mb_id, $lssn_no)
{
	global $g5;
	
    return sql_fetch(" select * from {$g5['less_apply_table']} where app_lssn_no = '$lssn_no' and app_uid='$mb_id' ");
}

//과정에서 컨텐츠 이름 가져오기
function get_chapterName($no)
{
	global $g5;
	
	$row = sql_fetch(" select c_name from {$g5['contents_table']} where c_no = TRIM('$no')");
    return $row['c_name'];
}

//사이버과정 정보 가져오기 (과정 no)
function get_lesson($lssn_no)
{
	global $g5;
	
    return sql_fetch(" select * from {$g5['lesson_table']} where lssn_no = '$lssn_no' ");
}

//해당차시 정보 가져오기(차시 no)
function get_chapter($chapt_no)
{
	global $g5;
	
    return sql_fetch(" select * from {$g5['chapter_table']} where cpt_no = '$chapt_no' ");
}

//해당 차시 학습 정보 가져오기
function chapter_attend_exist($arr_info='')
{
	global $g5;
	
	$sql = "select * from {$g5['chapter_att_table']} where 
				att_uid = '{$arr_info['uid']}' and 
				att_lssn_no = '{$arr_info['lesson']}' and 
				att_chapter_no = '{$arr_info['chapter']}' and 
				att_contents = '{$arr_info['contents']}' ";
	
	return sql_fetch($sql);
}

//해당 컨텐츠 정보 가져오기
function get_contents($cpt_no)
{
	global $g5;
	
	return sql_fetch(" select * from {$g5['contents_table']} where c_no = '$cpt_no' ");
}

// 학습진도관리 > 교육과정 가져오기
function get_lesson_select($name, $selected='', $event='')
{
    global $g5, $is_admin, $member, $strLesson;

    $sql = " select lssn_no, lssn_kind, lssn_title from {$g5['lesson_table']} where lssn_kind in ('LS01', 'LS02', 'LS12', 'LS22') ";
    $sql .= " order by lssn_no ";
	
    $result = sql_query($sql);
    $str = "<select id=\"$name\" name=\"$name\" class=\"mb_form\" $event>\n";
    for ($i=0; $row=sql_fetch_array($result); $i++) {
        if ($i == 0) $str .= "<option value=\"\">선택</option>";
		if($row['lssn_no'] == $selected)
			$strLesson = $row['lssn_title'];
        $str .= option_selected($row['lssn_no'], $selected, $row['lssn_title']);
    }
    $str .= "</select>";
    return $str;
}

//과정별 마일리지 정보 가져오기
function get_mileage_point($mb_id, $rel_table, $num)
{
	global $config;
    global $g5;
	
	$res = sql_fetch(" SELECT * FROM {$g5['point_table']} where mb_id = '{$mb_id}' and po_rel_table = '{$rel_table}' and po_rel_action = '@{$num}' ");
    
	return $res;
}

##########################################################################################
##기타
//number format
function get_int2num($num){
	return number_format($num, 0, "", ",");
}

//날짜 데이터 텍스트 변환
function get_str_date($str)
{
	return date("y-m-d", strtotime($str));
}

<?php
$sub_menu = "100100";
include_once('./_common.php');


if ( ! function_exists('utf2euc')) {
    function utf2euc($str) {
        return iconv("UTF-8","cp949//IGNORE", $str);
    }
}
if ( ! function_exists('is_ie')) {
    function is_ie() {
        return isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== false || strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false);
    }
}

auth_check($auth[$sub_menu], "r");

$sql_common = " from {$g5['member_table']} as m  ";

$sql_search = " where (1) and m.mb_level = '1' and mb_profile = '".$company."'";

if ($is_admin != 'super')
    $sql_search .= " and mb_level <= '{$member['mb_level']}' ";

if (!$sst) {
    $sst = "mb_datetime";
    $sod = "desc";
}

$sql_order = " order by {$sst} {$sod} ";

$sql = " select count(*) as cnt {$sql_common} {$sql_search} {$sql_order} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

//if (!$total_count) alert_just('데이터가 없습니다.');

//$sql = " select * {$sql_common} {$sql_search} {$sql_order} limit {$from_record}, {$rows} ";
//$result = sql_query($sql);

$qry = sql_query("select * {$sql_common} {$sql_search} {$sql_order}");

/*================================================================================
php_writeexcel http://www.bettina-attack.de/jonny/view.php/projects/php_writeexcel/
=================================================================================*/

include_once(G5_LIB_PATH.'/Excel/php_writeexcel/class.writeexcel_workbook.inc.php');
include_once(G5_LIB_PATH.'/Excel/php_writeexcel/class.writeexcel_worksheet.inc.php');

$fname = tempnam(G5_DATA_PATH, "tmp.xls");
$workbook = new writeexcel_workbook($fname);
$worksheet = $workbook->addworksheet();

$num2_format =& $workbook->addformat(array(num_format => '\0#'));

// Put Excel data
$data = array(
"mb_no"=>"번호",
"mb_name"=>"이름",
"mb_id"=>"아이디",
"mb_3"=>"부서명",
"사이버 윤리교육1",
"사이버 윤리교육1",
"이해충돌 사이버교육1",
"이해충돌 사이버교육1",
"갑질예방 사이버교육2",
"갑질예방 사이버교육2",
"직장내 괴롭힘 사이버교육3",
"직장내 괴롭힘 사이버교육3",
"고위직 부패 위험성진단",
"고위직 부패 위험성진단",
"mb_point"=>"마일리지",
);

$data = array_map('iconv_euckr', $data);

$col = 0;
foreach($data as $cell) {
    $worksheet->write(0, $col++, $cell);
}

for($i=1; $res=sql_fetch_array($qry); $i++)
{
	for($j=18; $j<19; $j++)
	{
		${'cyber'.$j} = get_lessonApply($res['mb_id'], $j);
		${'str_cyber'.$j} = "미완료";
		${'str_cyber'.$j.'0'} = get_int2num(${'cyber'.$j}['app_study_rate']) ? get_int2num(${'cyber'.$j}['app_study_rate']) : '0';
		
		if(get_int2num(${'cyber'.$j}['app_study_rate']) == 100)
		{
			${'str_cyber'.$j} = "완료";
			${'str_cyber'.$j.'0'} = get_int2num(${'cyber'.$j}['app_study_rate']) ? get_int2num(${'cyber'.$j}['app_study_rate']) : '0';
		}
	}	
	
	$res[0] = $str_cyber18;
	$res[1] = $str_cyber180.'%';
	$res[2] = $str_cyber9;
	$res[3] = $str_cyber90.'%';
	$res[4] = $str_cyber10;
	$res[5] = $str_cyber100.'%';
	$res[6] = $str_cyber11;
	$res[7] = $str_cyber110.'%';
	
    $res = array_map('iconv_euckr', $res);

	$col = 0;
	foreach($data as $key=>$cell) {
		if($col == 0)
			$worksheet->write($i, $col++, $i);
		//else if($col == 6)
		//	$worksheet->write($i, $col++, substr($res[$key],2,8));
		else
			$worksheet->write($i, $col++, $res[$key]);
	}
}

$workbook->close();

$filename = "학습진도-".date("ymd", time()).".xls";
if( is_ie() ) $filename = utf2euc($filename);

header("Content-Type: application/x-msexcel; name=".$filename);
header("Content-Disposition: inline; filename=".$filename);
$fh=fopen($fname, "rb");
fpassthru($fh);
unlink($fname);
?>
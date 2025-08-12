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


$L = 'LS03';
$qry2 = sql_query("SELECT * from cd_lms_lesson WHERE lssn_kind = '".$L."' ORDER BY lssn_no asc");


// Put Excel data
$data = array(
"mb_no"=>"번호",
"mb_name"=>"이름",
"mb_id"=>"아이디",
"mb_3"=>"부서명"
);


for($b=0; $res4=sql_fetch_array($qry2); $b++)
{
	$data[$b + 4] = $b + 1;
}

$data = array_map('iconv_euckr', $data);
$col = 0;
foreach($data as $cell) {
    $worksheet->write(0, $col++, $cell);
}




for($i=1; $res=sql_fetch_array($qry); $i++)
{


$qry2 = sql_query("SELECT * from cd_lms_lesson WHERE lssn_kind = 'LS03' ORDER BY lssn_no asc");
for($c=0; $res2=sql_fetch_array($qry2); $c++)
{

$qry3 = sql_fetch("SELECT * from cd_lms_lesson_result WHERE mb_id = '".$res['mb_id']."' AND lssn_no = '".$res2['lssn_no']."' limit 1");
if(!isset($qry3['mb_id'])) {
$res[$c + 4] = '0';
} else  {
$res[$c + 4] = '1';
}

}

#print_r($res);




	
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
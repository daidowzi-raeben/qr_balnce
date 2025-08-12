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

$sql_common = " from {$g5['member_table']} as m ";

$sql_search = " where (1) and m.mb_level = '1' ";

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
"mb_10"=>"년도",
"mb_name"=>"이름",
"mb_id"=>"아이디",
"mb_3"=>"부서1",
"mb_4"=>"부서2",
"mb_datetime"=>"가입일",
"mb_today_login"=>"최종접속",
"mb_8"=>"학습기간",
"mb_9"=>"학습기간",
"mb_point"=>"마일리지",
"mb_stat"=>"상태",
);

$data = array_map('iconv_euckr', $data);

$col = 0;
foreach($data as $cell) {
    $worksheet->write(0, $col++, $cell);
}

for($i=1; $res=sql_fetch_array($qry); $i++)
{
    $res = array_map('iconv_euckr', $res);

	$col = 0;
	foreach($data as $key=>$cell) {
		if($col == 0)
			$worksheet->write($i, $col++, $i);
		else if($col == 6)
			$worksheet->write($i, $col++, substr($res[$key],2,8));
		else if($col == 7)
			$worksheet->write($i, $col++, substr($res[$key],2,8));
		else if($col == 8)
			$worksheet->write($i, $col++, date("y-m-d", strtotime($res[$key])));
		else if($col == 9)
			$worksheet->write($i, $col++, date("y-m-d", strtotime($res[$key])));
		else
			$worksheet->write($i, $col++, $res[$key]);
	}
}

$workbook->close();

$filename = "회원목록-".date("ymd", time()).".xls";
if( is_ie() ) $filename = utf2euc($filename);

header("Content-Type: application/x-msexcel; name=".$filename);
header("Content-Disposition: inline; filename=".$filename);
$fh=fopen($fname, "rb");
fpassthru($fh);
unlink($fname);
?>
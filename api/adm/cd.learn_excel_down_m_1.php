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
$qry = sql_query("SELECT *,
(SELECT start_dt FROM cd_lms_lesson_result WHERE mb_id = cm.mb_id AND lssn_no = cll.lssn_no ORDER BY idx desc LIMIT 1) AS start_date,
(SELECT end_dt FROM cd_lms_lesson_result WHERE mb_id = cm.mb_id AND lssn_no = cll.lssn_no ORDER BY idx desc LIMIT 1) AS end_date
FROM cd_lms_lesson AS cll
INNER JOIN cd_member AS cm ON cll.lssn_company = cm.mb_profile
WHERE cll.lssn_company = '".$company."' AND cll.lssn_kind = '".$_GET['kind']."' ");

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
"mb_4"=>"부서명",
"lssn_title"=>"학습명",
"start_date"=>"학습시작일",
"end_data"=>"학습종료일",
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
		//else if($col == 6)
		//	$worksheet->write($i, $col++, substr($res[$key],2,8));
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
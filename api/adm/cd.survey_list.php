<?php
include_once('./_common.php');

switch($type)
{
	case "B":
		$sub_menu = "200200";
		$g5['title'] = '윤리경영 의식수준 진단관리';
		$btnTitle = "윤리경영 의식수준 진단관리";
		break;
	case "C":
		$sub_menu = "200300";
		$g5['title'] = '직장 내 괴롭힘 설문조사관리';
		$btnTitle = "직장 내 괴롭힘 설문조사관리";
		break;
	case "D":
		$sub_menu = "200400";
		$g5['title'] = '인권의식 인식도 설문조사관리';
		$btnTitle = "인권의식 인식도 설문조사관리";
		break;
	default:
		$sub_menu = "200100";
		$g5['title'] = '윤리의식 자가점검관리';
		$btnTitle = "윤리의식 자가점검관리";
}

auth_check_menu($auth, $sub_menu, 'r');

$sql_common = " from {$g5['member_table']} as m ";
$sql_common .= " left join {$g5['survey_data_table']} as sd ";
$sql_common .= " on m.mb_id = sd.srvd_uid and sd.srvy_type = '{$type}' ";

$sql_search = " where (1) and m.mb_level = '1' ";

if ($mb_4)
	$sql_search .= " and m.mb_4 like '{$mb_4}%' ";

if ($mb_id)
	$sql_search .= " and m.mb_id like '{$mb_id}%' ";

if ($mb_name)
	$sql_search .= " and m.mb_name like '{$mb_name}%' ";

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

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$startNum = 1 + (($page-1) * 15);


$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체목록</a>';

//$g5['title'] = '윤리의식 자가점검관리';
include_once('./cd.admin.head.php');

$sql = " select * {$sql_common} {$sql_search} {$sql_order} limit {$from_record}, {$rows} ";
$result = sql_query($sql);

$colspan = 16;
?>

<div class="tbl_frm02 tbl_wrap">
    <?php include_once('./admin.fsearch.php'); ?>
</div>

<div class="local_ov02">
	<div class="l_div">
		<a href="#" class="btn btn_03">진도관리</a>
		<a href="cd.survey_result.php?type=<?php echo $type?>" class="btn btn_02">설문데이터</a>
	</div>
	<div class="r_div">
		<?php if ($is_admin == 'super' || $is_admin == 'manager') { ?>
		<a href="./srvy_excel_down.php?type=<?php echo $type?>&amp;str=1" target="_blank" id="member_add" class="btn btn_04">EXCEL</a>
		<?php } ?>
	</div>
</div>

<div class="tbl_head01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
        <th scope="col" id="mb_list_no">No</th>
		<th scope="col" id="mb_list_id">이름</th>
		<th scope="col" id="mb_list_id">아이디</th>
		<th scope="col" id="mb_list_id">부서명</th>
		<th scope="col" id="mb_list_id">설문조사 작성일</th>
		<th scope="col" id="mb_list_id">회차</th>
		<th scope="col" id="mb_list_id">적용마일리지</th>
    </tr>
    </thead>
    <tbody>
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++) {
        $mb_id = $row['mb_id'];
		$mb_name = $row['mb_name'];		
    ?>

    <tr class="<?php echo $bg; ?>">
		<td headers="cb_list"><?php echo $startNum ?></td>
		<td headers="cb_list_name" class="td_name2">
            <?php echo $mb_name ?>
        </td>  
		<td headers="cb_list_"><?php echo $mb_id ?></td>
		<td headers="cb_list_"><?php echo get_text($row['mb_4']); ?></td>
		<td headers="cb_list_"><?php echo $row['srvd_rdate'] ?></td>
		<td headers="cb_list_"><?php echo $row['srvy_name'] ?></td>
		<td headers="cb_list_"><?php echo $row['srvy_point'] ?></td>
    </tr>
    <?php
		$startNum++;
    }
    if ($i == 0)
        echo "<tr><td colspan=\"".$colspan."\" class=\"empty_table\">자료가 없습니다.</td></tr>";
    ?>
    </tbody>
    </table>
</div>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, '?'.$qstr.$NSqstr.'&amp;type='.$type.'&amp;page='); ?>

<?php
include_once ('./cd.admin.tail.php');
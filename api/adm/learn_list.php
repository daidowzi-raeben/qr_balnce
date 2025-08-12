<?php
$sub_menu = "600100";
include_once('./_common.php');

if($type == "B")
{
	$sub_menu = "600110";
	$g5['title'] = '개인별진도관리';
}
else if($type == "C")
{
	$sub_menu = "600120";
	$g5['title'] = '부서별진도관리';
}
else
{
	$sub_menu = "600100";
	$g5['title'] = '통합진도관리';
}

auth_check($auth[$sub_menu], 'r');

if (!$bl_year)
	$bl_year = "2020";

if (!$bl_cate)
	$bl_cate = "B";

$sql_common = " from {$g5['member_point_table']} as mp ";
$sql_common .= " left join {$g5['member_table']} as m ";
$sql_common .= " on mp.mb_no = m.mb_no ";

$sql_search = " where (1) and m.mb_level = '1' and mp.mp_year = '$bl_year' and mp.mp_semi = '$bl_cate'";

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

$startNum = 1 + (($page-1) * 10);


$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체목록</a>';

include_once('./admin.head.php');

$sql = " select * {$sql_common} {$sql_search} {$sql_order} limit {$from_record}, {$rows} ";
#echo $sql;
$result = sql_query($sql);

$colspan = 18;
?>

<!--<div class="local_ov01 local_ov">
    <?php echo $listall ?>
    <span class="btn_ov01"><span class="ov_txt">총회원수 </span><span class="ov_num"> <?php echo number_format($total_count) ?>명 </span></span>
    <a href="?sst=mb_intercept_date&amp;sod=desc&amp;sfl=<?php echo $sfl ?>&amp;stx=<?php echo $stx ?>" class="btn_ov01"> <span class="ov_txt">차단 </span><span class="ov_num"><?php echo number_format($intercept_count) ?>명</span></a>
    <a href="?sst=mb_leave_date&amp;sod=desc&amp;sfl=<?php echo $sfl ?>&amp;stx=<?php echo $stx ?>" class="btn_ov01"> <span class="ov_txt">탈퇴  </span><span class="ov_num"><?php echo number_format($leave_count) ?>명</span></a>
</div>-->

<div class="tbl_frm02 tbl_wrap" style="text-align:center;">
    <?php include_once('./admin.fsearch.php'); ?>
</div>

<form name="fmemberlist" id="fmemberlist" action="./member_list_update.php" onsubmit="return fmemberlist_submit(this);" method="post">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="type" value="<?php echo $type ?>">
<input type="hidden" name="token" value="">

<div class="local_ov02">
	<div class="l_div">
		
	</div>
	<div class="r_div">
		<?php if ($is_admin == 'super' || $is_admin == 'manager') { ?>
		<a href="./learnexcel_down.php?sfl=<?php echo $sfl ?>&amp;str=<?php echo $type?>&amp;bl_year=<?php echo $bl_year?>&amp;bl_cate=<?php echo $bl_cate?>" target="_blank" id="member_add" class="btn btn_04">EXCEL</a>
		<?php } ?>
	</div>
</div>

<div class="tbl_head01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
        <th scope="col" id="mb_list_no" rowspan="2">No</th>
		<th scope="col" id="mb_list_id" rowspan="2">이름</th>
		<th scope="col" id="mb_list_id" rowspan="2">아이디</th>
		<th scope="col" id="mb_list_id" rowspan="2">부서명</th>
		<th scope="col" id="mb_list_name" colspan="<?php echo $colspan?>">항목별 수행결과</th>
		<th scope="col" id="mb_list_id" rowspan="2">마일리지 합계</th>
		<!--<th scope="col" id="mb_list_id" rowspan="2">순위</th>-->
    </tr>
	<tr>
		<th scope="col" id="mb_list_name">CEO메세지</th>
		<th scope="col" id="mb_list_name">자율준수메세지</th>
		<th scope="col" id="mb_list_name">윤리자가진단</th>
		<th scope="col" id="mb_list_name">준법자가진단</th>
		<th scope="col" id="mb_list_name">준법캠페인</th>
		<th scope="col" id="mb_list_name">윤리영상캠페인</th>
		<th scope="col" id="mb_list_name">윤리캠페인</th>
		<th scope="col" id="mb_list_name">윤리이야기</th>
		<th scope="col" id="mb_list_name">사이버교육1</th>
		<th scope="col" id="mb_list_name">사이버교육2</th>
		<th scope="col" id="mb_list_name">사이버교육3</th>
		<th scope="col" id="mb_list_name">사이버교육4</th>
		<th scope="col" id="mb_list_name">사이버교육5</th>
		<th scope="col" id="mb_list_name">자율준수편람</th>
		<th scope="col" id="mb_list_name">공정거래가이드라인</th>
		<th scope="col" id="mb_list_name">청탁금지법가이드라인</th>
		<th scope="col" id="mb_list_name">CP교육만족도조사</th>
		<th scope="col" id="mb_list_name">윤리CP인식도조사</th>
	</tr>
    </thead>
    <tbody>
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++) {
        // 접근가능한 그룹수
        $sql2 = " select count(*) as cnt from {$g5['group_member_table']} where mb_id = '{$row['mb_id']}' ";
        $row2 = sql_fetch($sql2);
        $group = '';
        if ($row2['cnt'])
            $group = '<a href="./boardgroupmember_form.php?mb_id='.$row['mb_id'].'">'.$row2['cnt'].'</a>';

        if ($is_admin == 'group') {
            $s_mod = '';
        } else {
            $s_mod = '<a href="./member_form.php?'.$qstr.'&amp;w=u&amp;mb_id='.$row['mb_id'].'" class="btn btn_03">수정</a>';
        }
        $s_grp = '<a href="./boardgroupmember_form.php?mb_id='.$row['mb_id'].'" class="btn btn_02">그룹</a>';

        $leave_date = $row['mb_leave_date'] ? $row['mb_leave_date'] : date('Ymd', G5_SERVER_TIME);
        $intercept_date = $row['mb_intercept_date'] ? $row['mb_intercept_date'] : date('Ymd', G5_SERVER_TIME);

        $mb_nick = get_sideview($row['mb_id'], get_text($row['mb_nick']), $row['mb_email'], $row['mb_homepage']);

        $mb_id = $row['mb_id'];
        $leave_msg = '';
        $intercept_msg = '';
        $intercept_title = '';
        if ($row['mb_leave_date']) {
            $mb_id = $mb_id;
            $leave_msg = '<span class="mb_leave_msg">탈퇴함</span>';
        }
        else if ($row['mb_intercept_date']) {
            $mb_id = $mb_id;
            $intercept_msg = '<span class="mb_intercept_msg">차단됨</span>';
            $intercept_title = '차단해제';
        }
        if ($intercept_title == '')
            $intercept_title = '차단하기';

        $address = $row['mb_zip1'] ? print_address($row['mb_addr1'], $row['mb_addr2'], $row['mb_addr3'], $row['mb_addr_jibeon']) : '';

        $bg = 'bg'.($i%2);

        switch($row['mb_certify']) {
            case 'hp':
                $mb_certify_case = '휴대폰';
                $mb_certify_val = 'hp';
                break;
            case 'ipin':
                $mb_certify_case = '아이핀';
                $mb_certify_val = '';
                break;
            case 'admin':
                $mb_certify_case = '관리자';
                $mb_certify_val = 'admin';
                break;
            default:
                $mb_certify_case = '&nbsp;';
                $mb_certify_val = 'admin';
                break;
        }
    ?>

    <tr class="<?php echo $bg; ?>">
		<td headers="mb_list_"><?php echo $startNum ?></td>
		<td headers="mb_list_name" class="td_mbname2"><?php echo get_text($row['mb_name']); ?></td>
		<td headers="mb_list_id" class="td_name2">
            <?php echo $mb_id ?>
        </td>  
		<td headers="mb_list_"><?php echo get_text($row['mb_4']); ?></td>
		<td headers="mb_list_"><?php echo get_text($row['mp_3']); ?></td>
		<td headers="mb_list_"><?php echo get_text($row['mp_4']); ?></td>
		<td headers="mb_list_"><?php echo get_text($row['mp_5']); ?></td>
		<td headers="mb_list_"><?php echo get_text($row['mp_6']); ?></td>
		<td headers="mb_list_"><?php echo get_text($row['mp_7']); ?></td>
		<td headers="mb_list_"><?php echo get_text($row['mp_8']); ?></td>
		<td headers="mb_list_"><?php echo get_text($row['mp_1']); ?></td>
		<td headers="mb_list_"><?php echo get_text($row['mp_2']); ?></td>
		<td headers="mb_list_"><?php echo get_text($row['mp_12']); ?></td>
		<td headers="mb_list_"><?php echo get_text($row['mp_15']); ?></td>
		<td headers="mb_list_"><?php echo get_text($row['mp_16']); ?></td>
		<td headers="mb_list_"><?php echo get_text($row['mp_17']); ?></td>
		<td headers="mb_list_"><?php echo get_text($row['mp_18']); ?></td>
		<td headers="mb_list_"><?php echo get_text($row['mp_9']); ?></td>
		<td headers="mb_list_"><?php echo get_text($row['mp_10']); ?></td>
		<td headers="mb_list_"><?php echo get_text($row['mp_11']); ?></td>
		<td headers="mb_list_"><?php echo get_text($row['mp_13']); ?></td>
		<td headers="mb_list_"><?php echo get_text($row['mp_14']); ?></td>
		<td headers="mb_list_"><?php echo get_text($row['mp_point']); ?></td>
		<!--<td headers="mb_list_">0</td>-->
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

</form>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, '?'.$qstr.$NSqstr.'&amp;page='); ?>

<script>
function fmemberlist_submit(f)
{
    if (!is_checked("chk[]")) {
        alert(document.pressed+" 하실 항목을 하나 이상 선택하세요.");
        return false;
    }

    if(document.pressed == "선택삭제") {
        if(!confirm("선택한 자료를 정말 삭제하시겠습니까?")) {
            return false;
        }
    }

    return true;
}

// 회원 엑셀 다운로드 추가
function excel_down(f){ 

	f.action = "./learnexcel_down.php?str=1";

	f.submit();

	f.action = "";

}
</script>

<?php
include_once ('./admin.tail.php');
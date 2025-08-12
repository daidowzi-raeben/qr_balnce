<?php
$sub_menu = "500100";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$sql_common = " from {$g5['member_table']} as m ";

$sql_search = " where (1) and m.mb_level = '1' ";

if ($type) {
    if ($mb_4)
		$sql_search .= " and m.mb_4 like '{$mb_4}%' ";

	if ($mb_id)
		$sql_search .= " and m.mb_id like '{$mb_id}%' ";

	if ($mb_name)
		$sql_search .= " and m.mb_name like '{$mb_name}%' ";
}

if ($is_admin != 'super')
    $sql_search .= " and mb_level <= '{$member['mb_level']}' ";

if (!$sst) {
    $sst = "mb_datetime";
    $sod = "asc";
}

$sql_order = " order by {$sst} {$sod} ";

$sql = " select count(*) as cnt {$sql_common} {$sql_search} {$sql_order} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$startNum = 1 + (($page-1) * $rows);

if (!$lssn) {
	$lssn = "1";
}
$strLesson = "";

$g5['title'] = '통합진도관리';
include_once('./cd.admin.head.php');

$sql = " select * {$sql_common} {$sql_search} {$sql_order} limit {$from_record}, {$rows} ";
$result = sql_query($sql);

?>

<div class="tbl_frm02 tbl_wrap">
    <?php include_once('./admin.fsearch.php'); ?>
</div>



<div class="local_ov02">
	<div class="l_div">
		<span class="btn_ov01"><span class="ov_txt">Total </span><span class="ov_num"> <?php echo number_format($total_count) ?> 건 </span></span>
	</div>
	<div class="r_div">
		<?php if ($is_admin == 'super') { ?>
			<a href="./cyber_excel_down.php?lssn=<?php echo $app_lssn_no?>&amp;sfl=<?php echo $sfl ?>&amp;str=1" target="_blank" id="member_add" class="btn btn_04">EXCEL</a>
		<?php } ?>
	</div>
</div>

    <div class="tbl_head01 tbl_wrap">
        <table>
            <caption><?php echo $g5['title']; ?> 목록</caption>
            <thead>
            <tr>
				<th scope="col" id="mb_list_no">No</th>
				<th scope="col" id="mb_list_id">과정명</th>
				<th scope="col" id="mb_list_id">이름</th>
				<th scope="col" id="mb_list_id">아이디</th>
				<th scope="col" id="mb_list_id">부서명</th>
				<th scope="col" id="mb_list_name">학습 시작일</th>
				<th scope="col" id="mb_list_id">학습 종료일</th>
				<th scope="col" id="mb_list_id">학습 진도율</th>
				<th scope="col" id="mb_list_id">시험점수</th>
				<th scope="col" id="mb_list_id">수료여부</th>
			</tr>
            </thead>
            <tbody>
            <?php 
			for ($i=0; $row=sql_fetch_array($result); $i++) {
				$mb_id = $row['mb_id'];
				$mb_name = $row['mb_name'];
				
				//학습 진도 가져오기
				$sql = " select * from {$g5['less_apply_table']} where app_uid = '{$mb_id}' and app_lssn_no = '{$lssn}' limit 0, 1 ";
				$row2 = sql_fetch($sql);
				
				if($row2['app_study_rate'])
					$aRate = $row2['app_study_rate'] . " %";
				else
					$aRate = "";
				
				if($row2['app_evaluation'] == "Y" && $testScore >= 60)
					$strEval = "수료";
				else
					$strEval = "";
			?>
            <tr class="<?php echo $bg; ?>">
				<td headers="cb_list"><?php echo $startNum ?></td>
				<td headers="cb_list"><?php echo $strLesson ?></td>
				<td headers="cb_list_name" class="td_name2">
					<?php echo $mb_name ?>
				</td>  
				<td headers="cb_list_"><?php echo $mb_id ?></td>
				<td headers="cb_list_"><?php echo get_text($row['mb_4']); ?></td>
				<td headers="cb_list_"><?php echo $row2['app_rdate'] ?></td>
				<td headers="cb_list_"><?php echo $row2['app_edate'] ?></td>
				<td headers="cb_list_"><?php echo $aRate ?></td>
				<td headers="cb_list_"><?php echo $testScore ?></td>
				<td headers="cb_list_"><?php echo $strEval ?></td>
			</tr>
            <?php 
				$startNum++;
			} ?>
            </tbody>
        </table>
    </div>

</form>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, '?'.$qstr.'&amp;page='); ?>

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


    function view_priview(v_dir) {

        var size_w = 1280;
        var size_h = 720;

        if( v_dir.length > 0 ) {
            window.open("<?php echo G5_URL ?>/process/ethics/"+v_dir+"/01/01.htm","content_preview","width="+size_w+"px,height="+size_h+"px");
        } else {
            alert("URL 입력후 미리보기가 가능합니다.")
        }
    }
</script>

<?php
include_once ('./cd.admin.tail.php');
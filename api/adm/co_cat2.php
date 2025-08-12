<?php
$sub_menu = "800200";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$sql_common = " from cd_co_cate2 as m where (1) ";

$sql_search = "  and cd_use = 'Y' ";


#if ($is_admin != 'super')
   # $sql_search .= " and mb_level <= '{$member['mb_level']}' ";


    $sst = "idx";
    $sod = "desc";


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

$g5['title'] = '중분류 관리';
include_once('./cd.admin.head.php');

$sql = " select * {$sql_common} {$sql_search} {$sql_order} limit {$from_record}, {$rows} ";
$result = sql_query($sql);
# echo $sql;
$colspan = 10;
?>

<div class="tbl_frm02 tbl_wrap">
<!--     <?php include_once('./cd.admin.fsearch.php'); ?> -->
</div>



<div class="local_ov02">
	<div class="l_div">
		<span class="btn_ov01"><span class="ov_txt">Total </span><span class="ov_num"> <?php echo number_format($total_count) ?> 건 </span></span>
			<select class="" name="lssn_company" onChange="selectLocation(this)" style="display:none;">
				<option value="">선택</option>
						<?php 
			$sql_list = "SELECT mb_profile from cd_member WHERE mb_profile != '' GROUP BY mb_profile ORDER BY mb_profile asc";
			$result_list = sql_query($sql_list);
			for ($i=0; $row=sql_fetch_array($result_list); $i++) {
				?>
				<option value="<?php echo $row['mb_profile']?>" <?php if($company == $row['mb_profile']) { echo ' selected ';} ?>><?php echo $row['mb_profile']?></option>
			<?php }?>
				</select>
	</div>
	<div class="r_div">
		<?php if ($is_admin == 'super') { ?>
			<a style="display:none;" href="./cd.learn_excel_down.php?company=<?php echo $company?>&lssn=<?php echo $app_lssn_no?>&amp;sfl=<?php echo $sfl ?>&amp;str=1" target="_blank" id="member_add" class="btn btn_04">EXCEL</a>
		<?php } ?>
		<a href="#" class="btn btn_01" onclick="onClickDel()">선택 삭제</a>
		<a href="./co_cat_w2.php" class="btn btn_03">등록</a>
	</div>
</div>
<div>

</div>
    <div class="tbl_head01 tbl_wrap">
		<form method="post" action="./co_del.php" id="frm_del">
		<input type="hidden" name="re" value="./co_cat2.php">
		<input type="hidden" name="table" value="cd_co_cate2">
        <table>
            <caption><?php echo $g5['title']; ?> 목록</caption>
            <thead>
			<tr>
				<th scope="col" id="mb_list_no">선택</th>
				<th scope="col" id="mb_list_no">No</th>
				<th scope="col" id="mb_list_id">대분류명</th>
				<th scope="col" id="mb_list_id">분류명</th>
				<th scope="col" id="mb_list_id">생성일자</th>
				<th scope="col" id="mb_list_id">과정분류코드</th>
			</tr>
            </thead>
            <tbody>
            <?php 
			for ($i=0; $row=sql_fetch_array($result); $i++) {
			?>
            <tr class="<?php echo $bg; ?>">
				<td>
					<input type="checkbox" name="idxArr[]" value="<?php echo $row['idx']?>">
				</td>
				<td headers="cb_list"><?php echo $startNum ?></td>
				<td>
					<?php 
			$sql_latest = "select * from cd_co_cate where idx = {$row['cd_idx']}";
			$result_latest = sql_fetch($sql_latest);	
			echo $result_latest['cd_name'];
			?>
				</td>
				<td><a href="./co_cat_w2.php?idx=<?php echo $row['idx']?>&w=u"><?php echo $row['cd_name']?></a></td>
				<td><?php echo $row['cd_date']?></td>
				<td>
			<?php echo $result_latest['cd_code']; ?>-<?php echo $row['cd_code']?></td>
			</tr>
            <?php 
				$startNum++;
			} ?>
            </tbody>
        </table>
		</form>
    </div>



<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, '?'.$qstr.'&amp;company='.$company.'&page='); ?>

<script>
function onClickDel() {
	if(confirm("삭제하시겠습니까?")) {
		document.getElementById("frm_del").submit()
	} else {
	}
}


function selectLocation(v) {
	console.log(v.value)
		location.href='./cd.learn_list.php?company='+v.value;
}
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
<?php
$sub_menu = "800500";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$sql_common = " from cd_co_detail as m where (1) ";

$sql_search = "   ";


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

$g5['title'] = '과정 정보 등록 관리';
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
		<a href="./co_detail_w.php"  id="member_add" class="btn btn_02">등록</a>
	</div>
</div>
<div>

</div>
    <div class="tbl_head01 tbl_wrap">
	<form method="post" action="./co_del.php" id="frm_del">
		<input type="hidden" name="re" value="./co_detail.php">
		<input type="hidden" name="table" value="cd_co_detail">
        <table>
            <caption><?php echo $g5['title']; ?> 목록</caption>
            <thead>
			<tr>
				<th scope="col" id="mb_list_no">선택</th>
				<th scope="col" id="mb_list_no">No</th>
				<th scope="col" id="mb_list_id">대분류명</th>
				<th scope="col" id="mb_list_id">중분류명</th>
				<th scope="col" id="mb_list_id">영상분류명</th>
				<th scope="col" id="mb_list_id">과정주제</th>
				<th scope="col" id="mb_list_id">과정정보명</th>
				<th scope="col" id="mb_list_id">개발년월일</th>
				<th scope="col" id="mb_list_id">학습시간</th>
				<th scope="col" id="mb_list_id">과정파일저장</th>
				<th scope="col" id="mb_list_id">과정파일연동</th>
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
				<td><?php 
$sql_latest2 = "SELECT cd_name FROM cd_co_cate WHERE idx = ". $row['cd_cate1'];
$result_latest2 = sql_fetch($sql_latest2);	
echo $result_latest2['cd_name']?></td>
				<td><?php 
$sql_latest2 = "SELECT cd_name FROM cd_co_cate2 WHERE idx = ". $row['cd_cate2'];
$result_latest2 = sql_fetch($sql_latest2);	
echo $result_latest2['cd_name']?></td>
				<td><?php 
$sql_latest2 = "SELECT cd_name FROM cd_co_cate3 WHERE idx = ". $row['cd_cate3'];
$result_latest2 = sql_fetch($sql_latest2);	
echo $result_latest2['cd_name']?></td>
				<td><?php 
$sql_latest2 = "SELECT cd_name FROM cd_co_cate4 WHERE idx = ". $row['cd_cate4'];
$result_latest2 = sql_fetch($sql_latest2);	
echo $result_latest2['cd_name']?></td>
				<td><a href="./co_detail_w.php?idx=<?php echo $row['idx']?>&w=u"><?php echo $row['cd_title']?></a></td>
				<td><?php echo $row['cd_ym']?></td>
				<td><?php echo $row['cd_time']?></td>
				<td><?php echo $row['cd_stu']?></td>
				<td><?php echo $row['cd_file']?></td>
			</tr>
            <?php 
				$startNum++;
			} ?>
            </tbody>
        </table>
		</form>
    </div>

</form>

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
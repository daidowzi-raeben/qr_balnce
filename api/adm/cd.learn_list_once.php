<?php
$sub_menu = "500200";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$sql_common = " from {$g5['member_table']} as m ";

$sql_search = "  ";

if ($type) {
    if ($mb_4)
		$sql_search .= " and cm.mb_4 like '{$mb_4}%' ";

	if ($mb_id)
		$sql_search .= " and cm.mb_id like '{$mb_id}%' ";

	if ($mb_name)
		$sql_search .= " and cm.mb_name like '{$mb_name}%' ";
}
if ($y)
 $sql_search .= " AND lssn_year = '{$y}' ";

 if ($t)
 $sql_search .= " AND lssn_title = '{$t}' ";



if ($is_admin != 'super')
    $sql_search .= " and mb_level <= '{$member['mb_level']}' ";

if (!$sst) {
    $sst = "cm.mb_datetime";
    $sod = "asc";
}

$sql_order = " order by {$sst} {$sod} ";

$sql = " SELECT count(*) as cnt,
(
SELECT att_study_rate
FROM cd_lms_chapter_attend
WHERE att_lssn_no = cll.lssn_no AND att_uid = cm.mb_id
ORDER BY att_study_last DESC
LIMIT 1) AS is_rate,
(
SELECT app_rdate
FROM cd_lesson_apply
WHERE mb_id = cm.mb_id AND app_lssn_no = cll.lssn_no
ORDER BY app_lssn_no ASC
LIMIT 1) AS start_date,
(
SELECT att_study_last
FROM cd_lms_chapter_attend
WHERE att_lssn_no = cll.lssn_no AND att_uid = cm.mb_id
ORDER BY att_study_last DESC
LIMIT 1) AS end_data
FROM cd_lms_lesson AS cll
INNER JOIN cd_member AS cm ON cll.lssn_company = cm.mb_profile
WHERE cll.lssn_company = '".$company."' AND cll.lssn_kind = 'LS01'
{$sql_search} {$sql_order}  ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
#$rows = 300;
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$startNum = 1 + (($page-1) * $rows);

if (!$lssn) {
	$lssn = "1";
}
$strLesson = "";

$g5['title'] = '사이버교육진도관리';
include_once('./cd.admin.head.php');

$sql = " SELECT *,
(
SELECT COUNT(*) FROM cd_lms_lesson AS a
INNER JOIN cd_lms_chapter AS b ON a.lssn_no = b.cpt_lesson
WHERE a.lssn_no = cll.lssn_no
) AS chCnt,
(SELECT COUNT(*) FROM cd_lms_chapter_attend WHERE att_uid = cm.mb_id and att_lssn_no = cll.lssn_no) AS mCnt,
(
SELECT att_study_rate
FROM cd_lms_chapter_attend
WHERE att_lssn_no = cll.lssn_no AND att_uid = cm.mb_id
ORDER BY att_study_last DESC
LIMIT 1) AS is_rate,
(
SELECT app_rdate
FROM cd_lesson_apply
WHERE app_uid = cm.mb_id AND app_lssn_no = cll.lssn_no
ORDER BY app_lssn_no ASC
LIMIT 1) AS start_date,
(
SELECT att_study_last
FROM cd_lms_chapter_attend
WHERE att_lssn_no = cll.lssn_no AND att_uid = cm.mb_id
ORDER BY att_study_last DESC
LIMIT 1) AS end_data
FROM cd_lms_lesson AS cll
INNER JOIN cd_member AS cm ON cll.lssn_company = cm.mb_profile
WHERE cll.lssn_company = '".$company."' AND cll.lssn_kind = 'LS01' 
{$sql_search} {$sql_order} limit {$from_record}, {$rows} ";
$result = sql_query($sql);
# echo $sql;
$colspan = 10;

#echo $sql;
?>

<div class="tbl_frm02 tbl_wrap">
    <?php include_once('./cd.admin.fsearch.php'); ?>
</div>



<div class="local_ov02">
	<div class="l_div">
		<span class="btn_ov01"><span class="ov_txt">Total </span><span class="ov_num"> <?php echo number_format($total_count) ?> 건 </span></span>
			<select class="" name="lssn_company" onChange="selectLocation(this)">
				<option value="">선택</option>
						<?php 
			$sql_list = "SELECT mb_profile from cd_member WHERE mb_profile != '' GROUP BY mb_profile ORDER BY mb_profile asc";
			$result_list = sql_query($sql_list);
			for ($i=0; $row=sql_fetch_array($result_list); $i++) {
				?>
				<option value="<?php echo $row['mb_profile']?>" <?php if($company == $row['mb_profile']) { echo ' selected ';} ?>><?php echo $row['mb_profile']?></option>
			<?php }?>
				</select>
					<select class="" name="lssn_company" onChange="selectLocation2(this)">
				<option value="">선택</option>
				<option value="2023" <?php if($y == "2023") echo "selected";?>>2023</option>
				<option value="2024" <?php if($y == "2024") echo "selected";?>>2024</option>
				<option value="2025" <?php if($y == "2025") echo "selected";?>>2025</option>
				</select>


				<select class="" name="lssn_company" onChange="selectLocation3(this)" style="width:100px;">
				<option value="">선택</option>
						<?php 
			$sql_list = "SELECT * FROM cd_lms_lesson WHERE lssn_company = '{$company}' and lssn_year = '{$y}'";
			
			$result_list = sql_query($sql_list);
			for ($i=0; $row=sql_fetch_array($result_list); $i++) {
				?>
				<option value="<?php echo $row['lssn_title']?>" <?php if($t == $row['lssn_title']) { echo ' selected ';} ?>><?php echo $row['lssn_title']?></option>
			<?php }?>
				</select>



				
	</div>
	<div class="r_div">
		<?php if ($is_admin == 'super') { ?>
			<a href="./cd.learn_excel_down_once.php?company=<?php echo $company?>&y=<?php echo $y?>&t=<?php echo $t?>&lssn=<?php echo $app_lssn_no?>&amp;sfl=<?php echo $sfl ?>&amp;str=1" target="_blank" id="member_add" class="btn btn_04">EXCEL</a>
		<?php } ?>
	</div>
</div>
<div>

</div>
    <div class="tbl_head01 tbl_wrap">
        <table>
            <caption><?php echo $g5['title']; ?> 목록</caption>
            <thead>
			<tr>
				<th scope="col" id="mb_list_no" >No</th>
				<th scope="col" id="mb_list_id">이름</th>
				<th scope="col" id="mb_list_id" >아이디</th>
				<th scope="col" id="mb_list_id" >부서명</th>
				<th scope="col" id="mb_list_id" >학습명</th>
				<th scope="col" id="mb_list_id" >학습시작일</th>
				<th scope="col" id="mb_list_id" >학습종료일</th>
				<th scope="col" id="mb_list_id" >학습진도율</th>
				<th scope="col" id="mb_list_id" >수료여부</th>							
			</tr>

	

            <tr>
			</tr>
            </thead>
            <tbody>
			<?php for ($i=0; $row=sql_fetch_array($result); $i++) { ?>
				<tr>
					<td><?php echo $i+1 ?></td>
					<td><?php echo $row['mb_name'] ?></td>
					<td><?php echo $row['mb_id'] ?></td>
					<td><?php echo $row['mb_4'] ?></td>
					<td><?php echo $row['lssn_title'] ?></td>
					<td><?php echo $row['start_date'] ?></td>
					<td><?php echo $row['end_data'] ?></td>
					<?php if($row['mCnt'] > 0) { ?>
					<td><?php echo floor($row['mCnt']/$row['chCnt']*100) ?>%</td>
					<?php } else  {?>
					<td>0</td>
					<?php }?>
					<td><?php if( floor($row['mCnt']/$row['chCnt']*100) == '100') { echo '수료'; } else { echo '미수료'; } ?></td>
				</tr>
			<?php } ?>
            </tbody>
        </table>
    </div>

</form>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, '?'.$qstr.'&amp;company='.$company.'&page='); ?>

<script>
function selectLocation(v) {
	console.log(v.value)
		location.href='./cd.learn_list_once.php?company='+v.value;
}
function selectLocation2(v) {
	console.log(v.value)
		location.href='./cd.learn_list_once.php?company=<?php echo $company;?>&y='+v.value;
}

function selectLocation3(v) {
	console.log(v.value)
		location.href='./cd.learn_list_once.php?company=<?php echo $company;?>&y=<?php echo $y;?>&t='+v.value;
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
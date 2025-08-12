<?php
$sub_menu = "500100";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$sql_common = " from {$g5['member_table']} as m ";

$sql_search = " where (1) and m.mb_level = '1' and mb_profile = '".$company."' ";

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
#echo $sql;
$colspan = 10;
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



			


	</div>
	<div class="r_div">
		<?php if ($is_admin == 'super') { ?>
			<a href="./cd.learn_excel_down.php?company=<?php echo $company?>&lssn=<?php echo $app_lssn_no?>&amp;sfl=<?php echo $sfl ?>&amp;str=1" target="_blank" id="member_add" class="btn btn_04">EXCEL</a>
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
				<th scope="col" id="mb_list_no" rowspan="2">No</th>
				<th scope="col" id="mb_list_id" rowspan="2">이름</th>
				<th scope="col" id="mb_list_id" rowspan="2">아이디</th>
				<th scope="col" id="mb_list_id" rowspan="2">부서명</th>
				<th scope="col" id="mb_list_name" colspan="<?php echo $colspan?>">항목별 수행결과</th>
				<th scope="col" id="mb_list_id" rowspan="2">마일리지 합계</th>
			</tr>

	

            <tr>
			<?php 
			$cyber = array();
			$str_cyber = array();
			$str_cyber_list = array();
			$sql_list = "SELECT * from cd_lms_adm_list ORDER BY idx asc";
			$result_list = sql_query($sql_list);
			for ($i=0; $row=sql_fetch_array($result_list); $i++) {
				?>
				<th scope="col" id="mb_list_name" colspan="2"><?php echo $row['mb_content']?></th>
			<?php }?>
			</tr>
            </thead>
            <tbody>
            <?php 
			for ($i=0; $row=sql_fetch_array($result); $i++) {
				$mb_id = $row['mb_id'];
				$mb_name = $row['mb_name'];
				$mb_3 = $row['mb_3'];
				$mb_point = $row['mb_point'];
				
				
				
				

				$sql_list = "SELECT * from cd_lms_adm_list ORDER BY idx asc";
				$result_list = sql_query($sql_list);
				for ($k=0; $row2=sql_fetch_array($result_list); $k++) {
					$lssn_data = sql_fetch("SELECT * from cd_lms_lesson AS a WHERE a.lssn_class = '".$row2['idx']."' and lssn_company = '".$_GET['company']."'");
					$lssn_data2 = sql_fetch("SELECT * from cd_lesson_apply AS a WHERE a.app_lssn_no = '".$lssn_data['lssn_no']."' 
					and app_uid = '".$mb_id."'
					");
					$cyber[$i][$k] = get_lessonApply($mb_id, $lssn_data['lssn_no']);
					$str_cyber[$i][$k] = "미완료";
					$str_cyber_list[$i][$k] = $cyber[$i][$k]['app_study_rate'] ? $cyber[$i][$k]['app_study_rate'] : '0';

				if(get_int2num($cyber[$i][$k]['app_study_rate']) == 100)
				{
					$str_cyber[$i][$k] = "완료";
					$str_cyber_list[$i][$k] = $cyber[$i][$k]['app_study_rate'] ? $cyber[$i][$k]['app_study_rate'] : '0';
				}


				}

				#print_r($cyber[$i]);



				//사이버교육 정보 가져오기
				$cyber1 = get_lessonApply($mb_id, 0);
				$cyber2 = get_lessonApply($mb_id, 0);
				$cyber3 = get_lessonApply($mb_id, 0);
				//사이버 윤리교육1
				$cyber4 = get_lessonApply($mb_id, 13);
				

				$str_cyber1 = "미완료";
				$str_cyber10 = "0/1";
				$str_cyber2 = "미완료";
				$str_cyber20 = "0/1";
				$str_cyber3 = "미완료";
				$str_cyber30 = "0/1";
				$str_cyber4 = "미완료";
				$str_cyber40 = "0/1";

				
				
				if(get_int2num($cyber1['app_study_rate']) == 100)
				{
					$str_cyber1 = "완료";
					$str_cyber10 = "1/1";
				}
				
				if(get_int2num($cyber2['app_study_rate']) == 100)
				{
					$str_cyber2 = "완료";
					$str_cyber20 = "1/1";
				}
				
				if(get_int2num($cyber3['app_study_rate']) == 100)
				{
					$str_cyber3 = "완료";
					$str_cyber30 = "1/1";
				}
				
				if(get_int2num($cyber4['app_study_rate']) == 100)
				{
					$str_cyber4 = "완료";
					$str_cyber40 = "1/1";
				}
			?>
            <tr class="<?php echo $bg; ?>">
				<td headers="cb_list"><?php echo $startNum ?></td>
				<td headers="cb_list_name" class="td_name2">
					<?php echo $mb_name ?>
				</td>  
				<td headers="cb_list_"><?php echo $mb_id ?></td>
				<td headers="cb_list_"><?php echo $mb_3 ?></td>
				<?php 
				$sql_list = "SELECT * from cd_lms_adm_list ORDER BY idx asc";
				$result_list = sql_query($sql_list);
				for ($u=0; $row3=sql_fetch_array($result_list); $u++) { ?>
				<td headers="cb_list_"><?php echo $str_cyber[$i][$u]?></td>
				<td headers="cb_list_"><?php echo $str_cyber_list[$i][$u] ?>%</td>
				<?php }?>
<!-- 				<td headers="cb_list_"><?php echo $str_cyber4 ?></td>
				<td headers="cb_list_"><?php echo $str_cyber40 ?></td>
				<td headers="cb_list_"><?php echo $str_cyber1 ?></td>
				<td headers="cb_list_"><?php echo $str_cyber10 ?></td>
				<td headers="cb_list_"><?php echo $str_cyber2 ?></td>
				<td headers="cb_list_"><?php echo $str_cyber20 ?></td>
				<td headers="cb_list_"><?php echo $str_cyber3 ?></td>
				<td headers="cb_list_"><?php echo $str_cyber30 ?></td> -->
			<!-- 	<td headers="cb_list_"></td>
				<td headers="cb_list_"></td> -->
				<td headers="cb_list_"><?php echo $mb_point ?></td>
			</tr>
            <?php 
				$startNum++;
			} ?>
            </tbody>
        </table>
    </div>

</form>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, '?'.$qstr.'&amp;company='.$company.'&page='); ?>

<script>
function selectLocation(v) {
	console.log(v.value)
		location.href='./cd.learn_list.php?company='+v.value;
}

function selectLocation2(v) {
	console.log(v.value)
		location.href='./cd.learn_list.php?company=<?php echo $company;?>&y='+v.value;
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
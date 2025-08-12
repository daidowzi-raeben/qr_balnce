<?php
$sub_menu = '100000';
include_once('./_common.php');

@include_once('./safe_check.php');
if(function_exists('social_log_file_delete')){
    social_log_file_delete(86400);      //소셜로그인 디버그 파일 24시간 지난것은 삭제
}

$g5['title'] = '관리시스템 메인정보';
include_once ('./cd.admin.head.php');

$new_member_rows = 5;
$new_point_rows = 5;
$new_write_rows = 5;

$sql_common = " from {$g5['member_table']} ";

$sql_search = " where (1) and mb_level = 1 ";

if ($is_admin != 'super')
    $sql_search .= " and mb_level <= '{$member['mb_level']}' ";

if (!$sst) {
    $sst = "mb_datetime";
    $sod = "desc";
}

$sql_order = " order by {$sst} {$sod} ";

//오늘 학습회원 수
$sql = " select count(distinct att_uid) as cnt from {$g5['chapter_att_table']} where att_study_last >= curdate() ";
$row = sql_fetch($sql);
$today_lrn_count = $row['cnt'];

//어제 학습회원 수
$sql = " select count(distinct att_uid) as cnt from {$g5['chapter_att_table']} where att_study_last > CURDATE() - INTERVAL 1 DAY and att_study_last <= curdate() ";
$row = sql_fetch($sql);
$last_lrn_count = $row['cnt'];

//학습자 총원
$sql = " select count(*) as cnt {$sql_common} {$sql_search} {$sql_order} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

// 탈퇴회원수
$sql = " select count(*) as cnt {$sql_common} {$sql_search} and mb_leave_date <> '' {$sql_order} ";
$row = sql_fetch($sql);
$leave_count = $row['cnt'];

$sql = " select * {$sql_common} {$sql_search} {$sql_order} limit {$new_member_rows} ";
$result = sql_query($sql);

$colspan = 12;
?>

<section>
	<div class="tbl_head01 tbl_wrap">
		<table>
        <caption>현황</caption>
        <thead>
        <tr>
            <th style="height:50px;" scope="col">금일학습인원</th>
			<th scope="col">전일학습인원</th>
            <th scope="col">학습자총원</th>
			<th scope="col">탈퇴인원</th>
        </tr>
        </thead>
		<tbody>
			<tr>
				<td style="height:150px; font-size: xx-large;"><?php echo $today_lrn_count?></td>
				<td style="height:150px; font-size: xx-large;"><?php echo $last_lrn_count?></td>
				<td style="height:150px; font-size: xx-large;"><?php echo $total_count?></td>
				<td style="height:150px; font-size: xx-large;"><?php echo $leave_count?></td>
			</tr>
		</tbody>
		</table>
	</div>
	<div class="btn_list03 btn_list">
		*신규등록 학습자 및 탈퇴 학습자는 3개월 이내 통계
        <!--<a href="./member_list.php">회원 전체보기</a>-->
    </div>

</section>

<section>
    <h2>신규등록학습자 <?php echo $new_member_rows ?>건 목록</h2>
	<div class="local_desc02 local_desc">
        총회원수 <?php echo number_format($total_count) ?>명 중 차단 <?php echo number_format($intercept_count) ?>명, 탈퇴 : <?php echo number_format($leave_count) ?>명
    </div>
    <div class="tbl_head01 tbl_wrap">
        <table>
        <caption>신규가입회원</caption>
        <thead>
        <tr>
            <th scope="col">이름</th>
			<th scope="col">아이디</th>
            <th scope="col">부서1</th>
			<th scope="col">부서2</th>
            <th scope="col">등록일</th>
			<th scope="col">접속일</th>
			<th scope="col">상태</th>
        </tr>
        </thead>
        <tbody>
        <?php
        for ($i=0; $row=sql_fetch_array($result); $i++)
        {
            // 접근가능한 그룹수
            $sql2 = " select count(*) as cnt from {$g5['group_member_table']} where mb_id = '{$row['mb_id']}' ";
            $row2 = sql_fetch($sql2);
            $group = "";
            if ($row2['cnt'])
                $group = '<a href="./boardgroupmember_form.php?mb_id='.$row['mb_id'].'">'.$row2['cnt'].'</a>';

            if ($is_admin == 'group')
            {
                $s_mod = '';
                $s_del = '';
            }
            else
            {
                $s_mod = '<a href="./member_form.php?$qstr&amp;w=u&amp;mb_id='.$row['mb_id'].'">수정</a>';
                $s_del = '<a href="./member_delete.php?'.$qstr.'&amp;w=d&amp;mb_id='.$row['mb_id'].'&amp;url='.$_SERVER['SCRIPT_NAME'].'" onclick="return delete_confirm(this);">삭제</a>';
            }
            $s_grp = '<a href="./boardgroupmember_form.php?mb_id='.$row['mb_id'].'">그룹</a>';

            $leave_date = $row['mb_leave_date'] ? $row['mb_leave_date'] : date("Ymd", G5_SERVER_TIME);
            $intercept_date = $row['mb_intercept_date'] ? $row['mb_intercept_date'] : date("Ymd", G5_SERVER_TIME);

            $mb_nick = get_sideview($row['mb_id'], get_text($row['mb_nick']), $row['mb_email'], $row['mb_homepage']);

            $mb_id = $row['mb_id'];
            if ($row['mb_leave_date'])
                $mb_id = $mb_id;
            else if ($row['mb_intercept_date'])
                $mb_id = $mb_id;

        ?>
        <tr>
            <td class="td_mbname"><?php echo get_text($row['mb_name']); ?></td>
			<td class="td_mbid"><?php echo $mb_id ?></td>
			<td class=""><?php echo $row['mb_3'] ?></td>
			<td class=""><?php echo $row['mb_4'] ?></td>
            <td class=""><?php echo $row['mb_datetime'] ?></td>
            <td class=""><?php echo $row['mb_today_login'] ?></td>
			<td class=""></td>
        </tr>
        <?php
            }
        if ($i == 0)
            echo '<tr><td colspan="'.$colspan.'" class="empty_table">자료가 없습니다.</td></tr>';
        ?>
        </tbody>
        </table>
    </div>
	<div class="btn_list03 btn_list">
        <a href="./cd.member_list.php">회원 전체보기</a>
    </div>

</section>
<?php
include_once ('./admin.tail.php');
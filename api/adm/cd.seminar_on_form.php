<?php
$sub_menu = "400300";
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, 'w');

$sm = array(
'lssn_no' => null,
'lssn_title' => null,
'lssn_intro' => null,
'lssn_sdate' => null,
'lssn_edate' => null,
'lssn_allowtime' => null,
'lssn_view' => null,
'lssn_point' => null,
'lssn_url' => null,
);

$sound_only = '';
$required_mb_id_class = '';
$required_mb_password = '';

if ($w == '')
{
    $required_mb_id = 'required';
    $required_mb_id_class = 'required alnum_';
    $required_mb_password = 'required';
    $sound_only = '<strong class="sound_only">필수</strong>';

    $html_title = '추가';
	
	$sm_active_y = 'checked';
}
else if ($w == 'u')
{
    $sm = get_lesson($ls_no);
	
    if (!$sm['lssn_no'])
        alert('존재하지 않는 자료입니다.');

    //if ($is_admin != 'super')
    //    alert('자신보다 권한이 높거나 같은 회원은 수정할 수 없습니다.');

    $required_mb_id = 'readonly';
    $html_title = '수정';

    $sm['lssn_title'] = get_text($sm['lssn_title']);
	$sm['lssn_intro'] = get_text($sm['lssn_intro']);
	$sm['lssn_sdate'] = get_text($sm['lssn_sdate']);
	$sm['lssn_edate'] = get_text($sm['lssn_edate']);
	$sm['lssn_allowtime'] = get_text($sm['lssn_allowtime']);
	$sm['lssn_view'] = get_text($sm['lssn_view']);
	$sm['lssn_point'] = get_text($sm['lssn_point']);
	$sm['lssn_url'] = get_text($sm['lssn_url']);
	
	if($sm['lssn_view'] == 'Y')
		$sm_active_y = 'checked';
	else
		$sm_active_n = 'checked';
}
else
    alert('제대로 된 값이 넘어오지 않았습니다.');


if ($mb['mb_intercept_date']) $g5['title'] = "차단된 ";
else $g5['title'] .= "";
$g5['title'] .= '웨비나 '.$html_title;
include_once('./admin.head.php');

include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');
// add_javascript('js 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
?>

<form name="fmember" id="fmember" action="./cd.seminar_on_form_update.php" onsubmit="return fmember_submit(this);" method="post" enctype="multipart/form-data">
<input type="hidden" name="w" value="<?php echo $w ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="token" value="">
<input type="hidden" name="sm_no" value="<?php echo $sm['lssn_no'] ?>" />

<div class="tbl_frm01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?></caption>
    <colgroup>
        <col class="grid_4">
        <col>
        <col class="grid_4">
        <col>
    </colgroup>
    <tbody>
	<tr>
		<th scope="row">교육명</th>
		<td colspan="3">
			<input type="text" name="sm_title" value="<?php echo $sm['lssn_title'] ?>" id="sm_title" <?php echo $required_mb_password ?> class="frm_input <?php echo $required_mb_password ?>" size="30"  maxlength="40" />
		</td>
	</tr>
    <tr>
		<th scope="row">일정</th>
		<td>
			<input type="text" name="sm_startdate" value="<?php echo $sm['lssn_sdate'] ?>" id="sm_startdate" <?php echo $required_mb_id ?> class="frm_input <?php echo $required_mb_password ?>" size="30"  maxlength="10"> ~ 
			<input type="text" name="sm_enddate" value="<?php echo $sm['lssn_edate'] ?>" id="sm_enddate" <?php echo $required_mb_id ?> class="frm_input <?php echo $required_mb_password ?>" size="30"  maxlength="10">
		</td>
		<th scope="row">교육시간</th>
		<td>
			<input type="text" name="sm_time" value="<?php echo $sm['lssn_allowtime'] ?>" id="sm_time" class="frm_input <?php echo $required_mb_password ?>" size="30" maxlength="10" /> 분
		</td>
	</tr>
	<tr>
		<th scope="row">활성여부</th>
		<td>
			<input type="radio" name="sm_active" value="Y" id="sm_active" <?php echo $sm_active_y; ?>>
            <label for="sm_active">활성</label>
            <input type="radio" name="sm_active" value="N" id="sm_active" <?php echo $sm_active_n; ?>>
            <label for="sm_active">비활성</label>
		</td>
		<th scope="row">마일리지</th>
		<td>
			<input type="text" name="sm_point" value="<?php echo $sm['lssn_point'] ?>" id="sm_point" class="frm_input <?php echo $required_mb_password ?>" size="30" maxlength="4" /> 점
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="mb_year">URL</label></th>
		<td colspan="3">
			<input type="text" name="sm_link" value="<?php echo $sm['lssn_url'] ?>" id="sm_link" class="frm_input <?php echo $required_mb_password ?>" size="100" maxlength="100" />
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="mb_year">이미지</label></th>
		<td colspan="3">
			<input type="file" name="bf_file[]" id="bf_file" title="파일첨부 : 용량 <?php echo $upload_max_filesize ?> 이하만 업로드 가능" class="frm_file ">
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="sm_text">기타</label></th>
        <td colspan="3">
			<textarea name="sm_text" id="sm_text"><?php echo $sm['lssn_intro'] ?></textarea>
		</td>
    </tr>
	
    <?php if ($w == 'u') { ?>	
    <?php } ?>
	
    </tbody>
    </table>
</div>

<div class="btn_fixed_top">
    <a href="./cd.seminar_on_list.php?<?php echo $qstr ?>" class="btn btn_02">목록</a>
    <input type="submit" value="확인" class="btn btn_03" accesskey='s'>
</div>
</form>

<script>
$( function() {
	$( "#sm_startdate, #sm_enddate" ).datepicker();
} );

$('#findRslt').on('click', function(){
	$('#searchPop-rslt').show();
});

function fmember_submit(f)
{
    if (!f.mb_icon.value.match(/\.(gif|jpe?g|png)$/i) && f.mb_icon.value) {
        alert('아이콘은 이미지 파일만 가능합니다.');
        return false;
    }

    if (!f.mb_img.value.match(/\.(gif|jpe?g|png)$/i) && f.mb_img.value) {
        alert('회원이미지는 이미지 파일만 가능합니다.');
        return false;
    }

    return true;
}
</script>
<?php

include_once('./admin.tail.php');
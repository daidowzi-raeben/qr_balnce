<?php
$sub_menu = "100100";
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, 'w');

if ($w == '')
{
    $required_mb_id = 'required';
    $required_mb_id_class = 'required alnum_';
    $required_mb_password = 'required';
    $sound_only = '<strong class="sound_only">필수</strong>';

    $mb['mb_mailling'] = 1;
    $mb['mb_open'] = 1;
    $mb['mb_level'] = $config['cf_register_level'];
    $html_title = '추가';
	
	$mb_1_c = "checked";
}
else
    alert('제대로 된 값이 넘어오지 않았습니다.');

// 본인확인방법
switch($mb['mb_certify']) {
    case 'hp':
        $mb_certify_case = '휴대폰';
        $mb_certify_val = 'hp';
        break;
    case 'ipin':
        $mb_certify_case = '아이핀';
        $mb_certify_val = 'ipin';
        break;
    case 'admin':
        $mb_certify_case = '관리자 수정';
        $mb_certify_val = 'admin';
        break;
    default:
        $mb_certify_case = '';
        $mb_certify_val = 'admin';
        break;
}

// 본인확인
$mb_certify_yes  =  $mb['mb_certify'] ? 'checked="checked"' : '';
$mb_certify_no   = !$mb['mb_certify'] ? 'checked="checked"' : '';

// 성인인증
$mb_adult_yes       =  $mb['mb_adult']      ? 'checked="checked"' : '';
$mb_adult_no        = !$mb['mb_adult']      ? 'checked="checked"' : '';

//메일수신
$mb_mailling_yes    =  $mb['mb_mailling']   ? 'checked="checked"' : '';
$mb_mailling_no     = !$mb['mb_mailling']   ? 'checked="checked"' : '';

// SMS 수신
$mb_sms_yes         =  $mb['mb_sms']        ? 'checked="checked"' : '';
$mb_sms_no          = !$mb['mb_sms']        ? 'checked="checked"' : '';

// 정보 공개
$mb_open_yes        =  $mb['mb_open']       ? 'checked="checked"' : '';
$mb_open_no         = !$mb['mb_open']       ? 'checked="checked"' : '';


if ($mb['mb_intercept_date']) $g5['title'] = "차단된 ";
else $g5['title'] .= "";
$g5['title'] .= '엑셀 회원 '.$html_title;
include_once('./cd.admin.head.php');


include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');
// add_javascript('js 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_javascript(G5_POSTCODE_JS, 0);    //다음 주소 js
?>

<form name="fmember" id="fmember" action="./cd.member_excel_form_update.php" onsubmit="return fmember_submit(this);" method="post" enctype="multipart/form-data">
<input type="hidden" name="w" value="<?php echo $w ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="token" value="">

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
		<td colspan="4">
			<label for="comment">
				1. 회원 샘플양식을 다운로드 받아 작성한 후 업로드 하십시오.</br>
				2. 업로드 된 회원 정보를 확인한 후 등록하기 버튼을 클릭하면 회원등록이 완료 됩니다.
			</label>
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="mb_profile">회사</label></th>
        <td>
			<input type="text" name="mb_profile" value="<?php echo $mb_profile ?>" id="mb_profile" class="frm_input" />
		</td>
        <th scope="row"></th>
        <td>
            
        </td>
    </tr>
	<tr>
		<th scope="row"><label for="mb_10">년도</label></th>
        <td>
			<input type="text" name="mb_10" value="<?php echo $mb_10 ?>" id="mb_10" class="frm_input" />
		</td>
        <th scope="row"><label for="bl_name">소속<?php echo $sound_only ?></label></th>
        <td>
            <input type="text" name="mb_1" value="<?php echo $mb_1 ?>" id="mb_1" class="frm_input" />
        </td>
    </tr>
		<tr>
		<th scope="row"><label for="file">업로드파일<?php echo $sound_only ?></label></th>
        <td>
			<label for="file_comment">업로드파일은 .xls 파일만 가능합니다. 복수 시트는 지원하지 않습니다.</label><br />
			<input type="file" name="mb_excel" id="mb_excel" required />
		</td>
		<th scope="row"><label for="mb_11">학습기간</label></th>
        <td>
			<input type="text" name="mb_8" value="<?php echo $mb_8 ?>" id="mb_8" class="required frm_input" />
			~
            <input type="text" name="mb_9" value="<?php echo $mb_9 ?>" id="mb_9" class="required frm_input" />
		</td>
    </tr>
	</tbody>
    </table>
</div>

<div class="btn_fixed_top">
	<a href="./file/cd.memberexcel_form.xls" target="_blank" id="sample_down" class="btn btn_04">샘플양식다운로드</a>
    <a href="./cd.member_list.php?<?php echo $qstr ?>" class="btn btn_02">목록</a>
    <input type="submit" value="확인" class="btn btn_03" accesskey='s'>
</div>
</form>

<script>
$( function() {
	$( "#mb_8, #mb_9" ).datepicker();
} );

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
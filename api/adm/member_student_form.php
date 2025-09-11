<?php
$sub_menu = "200110";
require_once './_common.php';
require_once './admin.head.php';


$mb = array(
'mb_id'  => null,
'mb_nick'  => null,
'bo_table'  => null,
'c_datetime'  => null,
'c_date'  => null,
'mb_year'  => null,
'mb_company'  => null,
'mb_type'  => null,
'mb_name'  => null,
'mb_1'  => null,
'mb_2'  => null,
'mb_3'  => null,
);

if($w == 'u') {
$mb = sql_fetch("select * from qr_member_student where idx = '{$idx}'");
}

?>

<form name="fmember" id="fmember" action="./member_student_form_update.php" onsubmit="return fmember_submit(this);"
    method="post" enctype="multipart/form-data">
    <input type="hidden" name="w" value="<?php echo $w ?>">
    <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
    <input type="hidden" name="stx" value="<?php echo $stx ?>">
    <input type="hidden" name="sst" value="<?php echo $sst ?>">
    <input type="hidden" name="sod" value="<?php echo $sod ?>">
    <input type="hidden" name="page" value="<?php echo $page ?>">
    <input type="hidden" name="idx" value="<?php echo $idx ?>">
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
                    <th scope="row">
                        <label for="mb_id">사번<?php echo $sound_only ?></label>
                    </th>
                    <td>
                        <input type="text" name="mb_id" value="<?php echo $mb['mb_id'] ? $mb['mb_id'] : '' ?>"
                            id="mb_id" class="frm_input" maxlength="255" size="15">
                    </td>
                    <th scope="row">
                        <label for="mb_id">닉네임<?php echo $sound_only ?></label>
                    </th>
                    <td>
                        <input type="text" name="mb_nick" value="<?php echo $mb['mb_nick'] ? $mb['mb_nick'] : '' ?>"
                            id="mb_nick" class="frm_input" maxlength="255" size="15">
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="mb_id">연도<?php echo $sound_only ?></label>
                    </th>
                    <td>
                        <input type="text" name="mb_year" value="<?php echo $mb['mb_year'] ? $mb['mb_year'] : '' ?>"
                            id="mb_year" class="frm_input" maxlength="255" size="15">
                    </td>
                    <th scope="row">
                        <label for="mb_id">소속<?php echo $sound_only ?></label>
                    </th>
                    <td>
                        <input type="text" name="mb_company"
                            value="<?php echo $mb['mb_company'] ? $mb['mb_company'] : '' ?>" id="mb_company"
                            class="frm_input" maxlength="255" size="15">
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="mb_id">이름<?php echo $sound_only ?></label>
                    </th>
                    <td>
                        <input type="text" name="mb_name" value="<?php echo $mb['mb_name'] ? $mb['mb_name'] : '' ?>"
                            id="mb_name" class="frm_input" maxlength="255" size="15">
                    </td>
                    <th scope="row">
                        <label for="mb_id">유형<?php echo $sound_only ?></label>
                    </th>
                    <td>
                        <input type="text" name="mb_type" value="<?php echo $mb['mb_type'] ? $mb['mb_type'] : '' ?>"
                            id="mb_type" class="frm_input" maxlength="255" size="15">
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="mb_1">부서1<?php echo $sound_only ?></label>
                    </th>
                    <td>
                        <input type="text" name="mb_1" value="<?php echo $mb['mb_1'] ? $mb['mb_1'] : '' ?>" id="mb_1"
                            class="frm_input" maxlength="255" size="15">
                    </td>
                    <th scope="row">
                        <label for="mb_2">부서2<?php echo $sound_only ?></label>
                    </th>
                    <td>
                        <input type="text" name="mb_2" value="<?php echo $mb['mb_2'] ? $mb['mb_2'] : '' ?>" id="mb_2"
                            class="frm_input" maxlength="255" size="15">
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="mb_3">부서3<?php echo $sound_only ?></label>
                    </th>
                    <td>
                        <input type="text" name="mb_3" value="<?php echo $mb['mb_3'] ? $mb['mb_3'] : '' ?>" id="mb_3"
                            class="frm_input" maxlength="255" size="15">
                    </td>
                </tr>
        </table>
    </div>
    <div class="btn_fixed_top">
        <a href="./member_student_list.php?<?php echo $qstr ?>" class="btn btn_02">목록</a>
        <input type="submit" value="확인" class="btn_submit btn" accesskey='s'>
    </div>
</form>


<script>
function fmember_submit(f) {
    return true;
}
</script>

<?php
// run_event('admin_member_form_after', $mb, $w);

require_once './admin.tail.php';
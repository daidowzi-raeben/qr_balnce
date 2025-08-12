
<form id="fsearch" name="fsearch" class="local_sch01 local_sch" method="get">
<input type="hidden" name="type" value="search">
<input type="hidden" name="company" value="<?php echo $company?>">
<table>
<caption><?php echo $g5['title']; ?> 검색</caption>
<colgroup>
	<col class="grid_4">
	<col>
	<col class="grid_4">
	<col>
</colgroup>
<tbody>
<?php
if($sub_menu == "50010000")
{
?>
<tr>
	<th scope="row"><label for="mb_year">과정</label></th>
	<td colspan="3">
		<?=get_lesson_select("lssn", $lssn, "")?>
	</td>
</tr>
<?php
}
?>
<tr>
	<th scope="row"><label for="mb_10">년도</label></th>
	<td>
		<input type="text" name="mb_10" id="mb_10" <?php echo $required_mb_password ?> class="frm_input <?php echo $required_mb_password ?>" size="15" maxlength="20" value="<?php echo $mb_10; ?>" />
	</td>
	<th scope="row"><label for="mb_profile">회사명</label></th>
	<td>
		<input type="text" name="mb_profile" id="mb_profile" <?php echo $required_mb_password ?> class="frm_input <?php echo $required_mb_password ?>" size="15" maxlength="20" value="<?php echo $mb_profile; ?>" />
	</td>
</tr>
<tr>
	<th scope="row"><label for="mb_3">부서명</label></th>
	<td>
		<input type="text" name="mb_3" id="mb_3" <?php echo $required_mb_password ?> class="frm_input <?php echo $required_mb_password ?>" size="15" maxlength="20" value="<?php echo $mb_3; ?>" />
	</td>
	<th scope="row"><label for="mb_4">부서2</label></th>
	<td>
		<input type="text" name="mb_4" id="mb_4" <?php echo $required_mb_password ?> class="frm_input <?php echo $required_mb_password ?>" size="15" maxlength="20" value="<?php echo $mb_4; ?>" />
	</td>
</tr>
<tr>
	<th scope="row"><label for="mb_id">아이디</label></th>
	<td>
		<input type="text" name="mb_id" id="mb_id" value="<?php echo $mb_id ?>" <?php echo $required_mb_password ?> class="frm_input <?php echo $required_mb_password ?>" size="15" maxlength="20" />
	</td>
	<th scope="row"><label for="mb_name">이름</label></th>
	<td>
		<input type="text" name="mb_name" id="mb_name" value="<?php echo $mb_name ?>" <?php echo $required_mb_password ?> class="frm_input <?php echo $required_mb_password ?>" size="15" maxlength="20" />
	</td>
</tr>
</tbody>
</table>
<br/>
<input type="submit" name="act_button" value="검색" class="btn btn_02">
</form>

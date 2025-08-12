<?php
include_once('./_common.php');
print_r($_REQUEST);

if(isset($_POST['idxArr'])) {
	foreach($_POST['idxArr'] as $key=>$val) {
		sql_query("UPDATE {$_POST['table']} SET cd_use = 'N' WHERE idx = {$val}");
	}
}

goto_url($_POST['re']."?".$qstr.'&amp;w=u&amp;', false);

?>
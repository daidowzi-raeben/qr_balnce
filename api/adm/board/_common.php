<?php
define('G5_IS_ADMIN', true);
include_once ('../../common.php');
switch($board['bo_table'])
{
	case "free":
		$sub_menu = "400100";
		break;
	case "notice":
		$sub_menu = "700100";
		break;
	case "archv":
		$sub_menu = "700200";
		break;
	case "qa":
		$sub_menu = "700300";
		break;
}
include_once(G5_ADMIN_PATH.'/admin.lib.php');
?>
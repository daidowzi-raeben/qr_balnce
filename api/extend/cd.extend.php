<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

add_event('admin_common', 'include_admin_lib', -10, -1);

function include_admin_lib() {
	include_once(G5_ADMIN_PATH.'/admin.cd.lib.php');
}
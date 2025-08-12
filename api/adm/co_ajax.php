<?php
include_once('./_common.php');
$data = [];

$sql_list = "SELECT * FROM {$_GET['table']} WHERE cd_idx = {$_GET['idx']}";
$result_list = sql_query($sql_list);
for ($k=0; $row2=sql_fetch_array($result_list); $k++) {
	$data[] = $row2;
}

echo json_encode($data);

?>
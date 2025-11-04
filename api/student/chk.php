<?php
include_once('./_common.php');

$che = "
select * from qr_board where bo_table = '{$bo_table}' limit 1
";
$chkNum = sql_fetch($che);
$chkNumConst = $chkNum['bo_1'] == '' ? 0 : $chkNum['bo_1'];

echo $chkNumConst;
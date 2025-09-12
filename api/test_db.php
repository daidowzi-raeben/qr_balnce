<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = '175.123.253.193';
$user = 'root';
$pass = 'sejong~5273!!';
$db   = 'qr_balance';

$start = microtime(true);
$mysqli = @mysqli_connect($host, $user, $pass, $db);
$elapsed = round((microtime(true) - $start), 3);

if (!$mysqli) {
    http_response_code(500);
    die("DB 연결 실패 ({$elapsed}s): " . mysqli_connect_error());
}
echo "DB 연결 성공 ({$elapsed}s)";
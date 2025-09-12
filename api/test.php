<?php
/**
 * db_test.php — MySQL 연결/쿼리 테스트 페이지
 * - mysqli, PDO 두 방식 모두 테스트
 * - utf8mb4 적용, 타임아웃/에러 보기 좋게 출력
 */

ini_set('display_errors', '1');
error_reporting(E_ALL);

if (!defined('G5_MYSQL_HOST')) define('G5_MYSQL_HOST', '175.123.253.193');
if (!defined('G5_MYSQL_USER')) define('G5_MYSQL_USER', 'root');
if (!defined('G5_MYSQL_PASSWORD')) define('G5_MYSQL_PASSWORD', 'sejong~5273!!');
if (!defined('G5_MYSQL_DB')) define('G5_MYSQL_DB', 'qr_balance');
if (!defined('G5_MYSQL_SET_MODE')) define('G5_MYSQL_SET_MODE', false);

$host = G5_MYSQL_HOST;
$user = G5_MYSQL_USER;
$pass = G5_MYSQL_PASSWORD;
$db   = G5_MYSQL_DB;

function h($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

?>
<!doctype html>
<html lang="ko">

<head>
    <meta charset="utf-8">
    <title>DB 연결 테스트</title>
    <style>
    body {
        font: 14px/1.5 system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, Apple SD Gothic Neo, "맑은 고딕", sans-serif;
        padding: 24px;
        color: #222;
    }

    h1 {
        margin: 0 0 12px;
    }

    .ok {
        color: #0a7a0a;
        font-weight: 700;
    }

    .bad {
        color: #b00020;
        font-weight: 700;
    }

    pre,
    code {
        background: #f6f8fa;
        padding: 8px 10px;
        border-radius: 6px;
        overflow: auto;
    }

    .box {
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 16px;
        margin: 16px 0;
    }

    table {
        border-collapse: collapse;
    }

    td,
    th {
        border: 1px solid #e5e7eb;
        padding: 6px 10px;
    }
    </style>
</head>

<body>
    <h1>DB 연결 테스트</h1>
    <div class="box">
        <strong>Target</strong><br>
        Host: <code><?php echo h($host); ?></code>,
        DB: <code><?php echo h($db); ?></code>,
        User: <code><?php echo h($user); ?></code>
    </div>

    <?php
// ------- 1) mysqli 테스트 --------
echo '<div class="box"><h2>1) mysqli</h2>';
$start = microtime(true);
$mysqli = mysqli_init();
mysqli_options($mysqli, MYSQLI_OPT_CONNECT_TIMEOUT, 5); // 5초
$connected = @$mysqli->real_connect($host, $user, $pass, $db);
$elapsed = sprintf('%.3f', microtime(true) - $start);

if (!$connected) {
    echo '<p class="bad">[실패] 연결 불가 ('.h($elapsed).'s)</p>';
    echo '<pre>'.h(mysqli_connect_errno().' : '.mysqli_connect_error()).'</pre>';
} else {
    $mysqli->set_charset('utf8mb4');
    echo '<p class="ok">[성공] 연결 완료 ('.h($elapsed).'s)</p>';

    // 서버 정보
    echo '<p><strong>서버 정보</strong></p><table><tbody>';
    echo '<tr><th>host_info</th><td>'.h($mysqli->host_info).'</td></tr>';
    echo '<tr><th>server_info</th><td>'.h($mysqli->server_info).'</td></tr>';
    echo '<tr><th>protocol_version</th><td>'.h($mysqli->protocol_version).'</td></tr>';
    echo '</tbody></table>';

    // 간단 쿼리
    $q1 = $mysqli->query("SELECT 1 AS ok, VERSION() AS version, DATABASE() AS dbname, USER() AS who");
    if ($q1) {
        $row = $q1->fetch_assoc();
        echo '<p><strong>SELECT 1 테스트</strong></p>';
        echo '<pre>'.h(print_r($row, true)).'</pre>';
        $q1->free();
    } else {
        echo '<p class="bad">SELECT 1 실패</p><pre>'.h($mysqli->error).'</pre>';
    }

    // 테이블 10개 미리보기
    $q2 = $mysqli->query("SHOW TABLES");
    if ($q2) {
        echo '<p><strong>테이블 목록 (최대 10개)</strong></p><table><tbody>';
        $count = 0;
        while ($r = $q2->fetch_array(MYSQLI_NUM)) {
            echo '<tr><td>'.h($r[0]).'</td></tr>';
            if (++$count >= 10) break;
        }
        echo '</tbody></table>';
        $q2->free();
    } else {
        echo '<p class="bad">테이블 조회 실패</p><pre>'.h($mysqli->error).'</pre>';
    }

    $mysqli->close();
}
echo '</div>';

// ------- 2) PDO 테스트 --------
echo '<div class="box"><h2>2) PDO (mysql)</h2>';
try {
    $dsn = "mysql:host={$host};dbname={$db};charset=utf8mb4";
    $pdoStart = microtime(true);
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
        PDO::ATTR_TIMEOUT            => 5, // 일부 드라이버에서만 동작
    ]);
    $pdoElapsed = sprintf('%.3f', microtime(true) - $pdoStart);
    echo '<p class="ok">[성공] PDO 연결 완료 ('.h($pdoElapsed).'s)</p>';

    $row = $pdo->query("SELECT NOW() now_ts, VERSION() version, DATABASE() dbname, USER() who")->fetch();
    echo '<p><strong>환경 확인</strong></p><pre>'.h(print_r($row, true)).'</pre>';

    // 간단 쿼리 실행
    $stmt = $pdo->query("SELECT 1 AS ok");
    echo '<p><strong>SELECT 1</strong> : '.h($stmt->fetch()['ok']).'</p>';

} catch (Throwable $e) {
    echo '<p class="bad">[실패] '.h($e->getMessage()).'</p>';
}
echo '</div>';
?>

    <div class="box">
        <p><strong>참고</strong></p>
        <ul>
            <li>연결이 느리거나 실패하면: <code>포트 3306 방화벽</code>, <code>MySQL bind-address</code>, <code>사용자/비번/권한</code> 확인.
            </li>
            <li>Nginx/Apache에서 3030으로 프록시 중이면, 백엔드(예: PHP-FPM, Node)가 지연되어도 게이트웨이 타임아웃이 발생할 수 있어요.</li>
        </ul>
    </div>
</body>

</html>
<?php
require_once "./_common.php";
/**
 * member_student_form_update.php
 * - XLSX 업로드 전용
 * - 1행: 헤더(필드명), 2행: 무시, 3행부터 등록
 * - sql_query()만 사용 (DB 연결/트랜잭션 없음)
 *
 * 필수: mb_id
 * 선택: bo_table 외 기타 컬럼
 * 옵션: idx 헤더가 있으면 해당 값으로 UPDATE 수행
 */

/* =========================
 * 1) 라이브러리 로드 (PhpSpreadsheet)
 * ========================= */
$autoload = __DIR__ . '/vendor/autoload.php';
if (!is_file($autoload)) {
    echo "라이브러리(PhpSpreadsheet)가 설치되어 있지 않습니다.<br>".
         "이 파일과 같은 경로에서 아래 명령을 실행하세요.<br>".
         "<pre>composer require phpoffice/phpspreadsheet</pre>";
    exit;
}
require_once $autoload;

use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

/* =========================
 * 2) 유틸
 * ========================= */
if (!function_exists('sql_escape_string')) {
    function sql_escape_string($v){ return addslashes($v); }
}

/** 허용 컬럼 화이트리스트 */
const ALLOWED_COLUMNS = [
  'mb_id','mb_nick','bo_table','c_datetime','c_date','mb_year',
  'mb_company','mb_type','mb_name','mb_1','mb_2','mb_3'
];

/** 날짜/시간 보정 */
function normalize_datetime($v){
    if ($v === '' || $v === null) return null;
    if (is_numeric($v)) { // 엑셀 시리얼
        $base = new DateTime('1899-12-30 00:00:00', new DateTimeZone('UTC'));
        $base->modify('+' . intval($v) . ' days');
        return $base->format('Y-m-d H:i:s');
    }
    $ts = strtotime($v);
    return $ts !== false ? date('Y-m-d H:i:s', $ts) : null;
}
function normalize_date($v){
    if ($v === '' || $v === null) return null;
    if (is_numeric($v)) {
        $base = new DateTime('1899-12-30 00:00:00', new DateTimeZone('UTC'));
        $base->modify('+' . intval($v) . ' days');
        return $base->format('Y-m-d');
    }
    $ts = strtotime($v);
    return $ts !== false ? date('Y-m-d', $ts) : null;
}
function normalize_year($v){
    if ($v === '' || $v === null) return null;
    if (preg_match('/^\d{4}$/', (string)$v)) return (string)$v;
    $ts = strtotime((string)$v);
    return $ts !== false ? date('Y', $ts) : null;
}

/** {$sql_common} 생성 */
function build_sql_common(array $row){
    $pairs = [];
    foreach (ALLOWED_COLUMNS as $c) {
        if (!array_key_exists($c, $row)) continue;
        $v = $row[$c];
        // 타입별 보정
        if ($c === 'c_datetime') $v = normalize_datetime($v);
        if ($c === 'c_date')     $v = normalize_date($v);
        if ($c === 'mb_year')    $v = normalize_year($v);

        if ($v === '' || $v === null) $pairs[] = "{$c} = NULL";
        else $pairs[] = "{$c} = '".sql_escape_string($v)."'";
    }
    return implode(",\n    ", $pairs);
}

/* =========================
 * 3) 파일 수신
 * ========================= */
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_FILES['file'])) {
    echo "업로드 파일이 없습니다.";
    exit;
}
$ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
if ($ext !== 'xlsx') {
    echo "엑셀(.xlsx) 파일만 업로드 가능합니다.";
    exit;
}
$tmpPath = $_FILES['file']['tmp_name'];

/* =========================
 * 4) 엑셀 읽기
 * ========================= */
try {
    $reader = new Xlsx();
    $reader->setReadDataOnly(true);
    $xlsx = $reader->load($tmpPath);
    $sheet = $xlsx->getActiveSheet();
    // A,B,C... 키 배열
    $data = $sheet->toArray(null, true, true, true);
} catch (Throwable $e) {
    echo "엑셀 읽기 오류: " . htmlspecialchars($e->getMessage());
    exit;
}
if (empty($data)) { echo "빈 시트입니다."; exit; }

/* =========================
 * 5) 헤더 추출(1행) & 데이터는 3행부터
 * ========================= */
$headerRow = $data[1] ?? null;           // 1행
if (!$headerRow) { echo "헤더(1행)가 없습니다."; exit; }

$headers = array_values($headerRow);     // ['mb_id','mb_nick',...]
$headerIndex = [];                       // 필드명 -> 열인덱스(0-based)
$keys = array_keys($headerRow);          // ['A','B','C'...]
foreach ($keys as $i => $_) {
    $name = trim((string)($headers[$i] ?? ''));
    if ($name !== '') $headerIndex[$name] = $i; // 0-based
}

// mb_id 컬럼 존재 확인
if (!array_key_exists('mb_id', $headerIndex)) {
    echo "헤더에 'mb_id' 컬럼명이 없습니다. (1행에 정확히 'mb_id'를 넣어주세요)";
    exit;
}
// idx가 있으면 UPDATE 지원
$hasIdx = array_key_exists('idx', $headerIndex);

$ok=0; $fail=0; $errors=[];

/* =========================
 * 6) 3행부터 처리
 * ========================= */
for ($r = 3; $r <= count($data); $r++) {
    if (!isset($data[$r])) continue;
    $rowArr = array_values($data[$r]);   // A,B,C... 값을 0-based로
    // 완전 빈줄 스킵
    $nonEmpty = array_filter($rowArr, fn($v)=>trim((string)$v) !== '');
    if (!$nonEmpty) continue;

    // 행 → 연관배열(필드명 기준)
    $row = [];
    if ($hasIdx) {
        $idxPos = $headerIndex['idx'];
        $row['idx'] = trim((string)($rowArr[$idxPos] ?? ''));
    }
    foreach (ALLOWED_COLUMNS as $col) {
        if (!array_key_exists($col, $headerIndex)) continue; // 헤더에 없는 컬럼은 패스
        $pos = $headerIndex[$col];
        $row[$col] = $rowArr[$pos] ?? null;
    }

    // 필수: mb_id
    $mb_id = isset($row['mb_id']) ? trim((string)$row['mb_id']) : '';
    if ($mb_id === '') {
        $fail++; $errors[] = "행{$r} INSERT 실패: mb_id가 비어있습니다.";
        continue;
    }

    $sql_common = build_sql_common($row);

    if ($hasIdx && !empty($row['idx'])) {
        $idxVal = sql_escape_string($row['idx']);
        $sql = "UPDATE qr_member_student
                   SET {$sql_common}
                 WHERE idx = '{$idxVal}'";
    } else {
        $sql = "INSERT INTO qr_member_student
                   SET {$sql_common}";
    }

    $res = sql_query($sql, false); // 그누보드 스타일
    if ($res) $ok++; else { $fail++; $errors[] = "행{$r} 쿼리 실패"; }
}

/* =========================
 * 7) 결과 출력
 * ========================= */
echo "<h3>처리 결과</h3>";
echo "<div>성공: {$ok}건</div>";
echo "<div>실패: {$fail}건</div>";
if ($errors) {
    echo "<hr><pre>".htmlspecialchars(implode("\n", $errors), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')."</pre>";
}
echo '<p><a href="'.htmlspecialchars($_SERVER['HTTP_REFERER'] ?? './').'">돌아가기</a></p>';

goto_url('./member_student_list.php?' . $qstr . '&amp;w=u&amp;mb_id=' . $mb_id, false);
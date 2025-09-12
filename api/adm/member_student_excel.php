<?php
// /adm/member_student_form_update.php
require_once './_common.php';

/**
 * member_student_form_update.php (PHP 7.0 / Gnuboard 내장 PHPExcel)
 * - 업로드한 Excel의 1행 = 헤더, 2행 = 무시, 3행부터 등록/수정
 * - 필수: mb_id
 * - 선택: bo_table 외 기타 컬럼
 * - 옵션: idx 헤더가 있으면 해당 값으로 UPDATE, 없으면 INSERT
 */

// =========================
// 1) 라이브러리 로드 (PHPExcel)
// =========================
require_once G5_LIB_PATH . '/PHPExcel.php';
require_once G5_LIB_PATH . '/PHPExcel/IOFactory.php';

// =========================
// 2) 유틸
// =========================
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

    // PHPExcel은 날짜셀을 float로 줄 수 있음
    if (is_numeric($v)) {
        // Excel 기준일(1899-12-30) 보정
        $base = new DateTime('1899-12-30 00:00:00', new DateTimeZone('UTC'));
        $days = (int)floor($v);
        $secs = (int)round(($v - $days) * 86400);
        $base->modify('+'.$days.' days');
        $base->modify('+'.$secs.' seconds');
        return $base->format('Y-m-d H:i:s');
    }
    $ts = strtotime((string)$v);
    return $ts !== false ? date('Y-m-d H:i:s', $ts) : null;
}
function normalize_date($v){
    if ($v === '' || $v === null) return null;
    if (is_numeric($v)) {
        $base = new DateTime('1899-12-30 00:00:00', new DateTimeZone('UTC'));
        $days = (int)round($v);
        $base->modify('+'.$days.' days');
        return $base->format('Y-m-d');
    }
    $ts = strtotime((string)$v);
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

// =========================
// 3) 파일 수신
// =========================
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_FILES['file'])) {
    echo '업로드 파일이 없습니다.';
    exit;
}
$tmpPath = $_FILES['file']['tmp_name'];
$ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
if (!in_array($ext, ['xlsx','xls'])) {
    echo '엑셀(.xlsx 또는 .xls) 파일만 업로드 가능합니다.';
    exit;
}

// =========================
/* 4) 엑셀 읽기 (PHPExcel) */
// =========================
try {
    $fileType = PHPExcel_IOFactory::identify($tmpPath);
    $reader   = PHPExcel_IOFactory::createReader($fileType);
    // 값만 읽기(서식/수식 무시)
    if (method_exists($reader, 'setReadDataOnly')) $reader->setReadDataOnly(true);
    $excel = $reader->load($tmpPath);
} catch (Exception $e) {
    echo '엑셀 열기 오류: ' . htmlspecialchars($e->getMessage());
    exit;
}

$sheet = $excel->getSheet(0);
$highestRow    = $sheet->getHighestRow();
$highestColumn = $sheet->getHighestColumn();
$highestColIdx = PHPExcel_Cell::columnIndexFromString($highestColumn) - 1;

$headerIndex = []; // '필드명' => 열 인덱스(0-based)
$hasIdx = false;
$ok = 0; $fail = 0; $errors = [];
$last_mb_id = '';

// ===== 5) 1행 헤더 파싱
$headerRow = 1;
for ($col = 0; $col <= $highestColIdx; $col++) {
    $name = trim((string)$sheet->getCellByColumnAndRow($col, $headerRow)->getValue());
    if ($name !== '') $headerIndex[$name] = $col;
}
if (!array_key_exists('mb_id', $headerIndex)) {
    echo "헤더에 'mb_id' 컬럼명이 없습니다. (1행에 정확히 'mb_id'를 넣어주세요)";
    exit;
}
$hasIdx = array_key_exists('idx', $headerIndex);

// ===== 6) 3행부터 데이터 처리 (2행은 무시)
for ($rowNum = 3; $rowNum <= $highestRow; $rowNum++) {
    // 완전 빈 줄 스킵
    $allEmpty = true;
    for ($c=0; $c<= $highestColIdx; $c++) {
        $v = $sheet->getCellByColumnAndRow($c, $rowNum)->getValue();
        if (trim((string)$v) !== '') { $allEmpty = false; break; }
    }
    if ($allEmpty) continue;

    // 행 -> 연관배열
    $row = [];
    if ($hasIdx) {
        $idxPos = $headerIndex['idx'];
        $row['idx'] = trim((string)$sheet->getCellByColumnAndRow($idxPos, $rowNum)->getValue());
    }
    foreach (ALLOWED_COLUMNS as $colName) {
        if (!array_key_exists($colName, $headerIndex)) continue;
        $pos = $headerIndex[$colName];
        $row[$colName] = $sheet->getCellByColumnAndRow($pos, $rowNum)->getValue();
    }

    // 필수: mb_id
    $mb_id = isset($row['mb_id']) ? trim((string)$row['mb_id']) : '';
    if ($mb_id === '') {
        $fail++; $errors[] = "행{$rowNum} INSERT 실패: mb_id가 비어있습니다.";
        continue;
    }
    $last_mb_id = $mb_id;

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

    $res = sql_query($sql, false);
    if ($res) $ok++; else { $fail++; $errors[] = "행{$rowNum} 쿼리 실패"; }
}

// =========================
// 7) 결과 출력 & 리다이렉트
// =========================
echo "<h3>처리 결과</h3>";
echo "<div>성공: {$ok}건</div>";
echo "<div>실패: {$fail}건</div>";
if ($errors) {
    echo "<hr><pre>".htmlspecialchars(implode("\n", $errors), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')."</pre>";
}

$qstr = isset($qstr) ? $qstr : '';
if (function_exists('goto_url')) {
    goto_url('./member_student_list.php?' . $qstr . '&w=u&mb_id=' . urlencode($last_mb_id), false);
}
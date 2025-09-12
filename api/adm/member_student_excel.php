<?php
require_once "./_common.php";
/**
 * member_student_form_update.php (PHP 7.0 호환 / Spout 2.7)
 * - XLSX 업로드 전용
 * - 1행: 헤더(필드명), 2행: 무시, 3행부터 등록
 * - sql_query()만 사용 (DB 연결/트랜잭션 없음)
 *
 * 필수: mb_id
 * 선택: bo_table 외 기타 컬럼
 * 옵션: idx 헤더가 있으면 해당 값으로 UPDATE 수행
 */

/* =========================
 * 1) 라이브러리 로드 (Spout 2.7)
 * ========================= */
$autoload = __DIR__ . '/vendor/autoload.php';
if (!is_file($autoload)) {
    echo "라이브러리(Spout)가 설치되어 있지 않습니다.<br>".
         "이 파일과 같은 경로에서 아래 명령을 실행하세요.<br>".
         "<pre>composer require box/spout:^2.7</pre>";
    exit;
}
require_once $autoload;

use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;

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

/** 엑셀 날짜/시간 보정 */
function normalize_datetime($v){
    if ($v === '' || $v === null) return null;
    // 숫자 = 엑셀 시리얼로 판단
    if (is_numeric($v)) {
        // 엑셀 기준 시작(윤초/윤년 보정 포함) : 1899-12-30
        $base = new DateTime('1899-12-30 00:00:00', new DateTimeZone('UTC'));
        $days = (int)floor($v);
        $secs = (int)round(($v - $days) * 86400);
        $base->modify('+' . $days . ' days');
        $base->modify('+' . $secs . ' seconds');
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
        $base->modify('+' . $days . ' days');
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
 * 4) 엑셀 읽기 (Spout)
 * ========================= */
try {
    $reader = ReaderFactory::create(Type::XLSX);
    // 대용량 안정화 옵션
    $reader->setShouldFormatDates(false); // 원시값 그대로(날짜는 수치로 들어옴)
    $reader->open($tmpPath);
} catch (Exception $e) {
    echo "엑셀 열기 오류: " . htmlspecialchars($e->getMessage());
    exit;
}

$headerIndex = [];    // '필드명' => 열 인덱스(0-based)
$hasIdx = false;
$rowNumber = 0;
$ok = 0; $fail = 0; $errors = [];
$last_mb_id = '';

try {
    foreach ($reader->getSheetIterator() as $sheet) {
        foreach ($sheet->getRowIterator() as $rowObj) {
            $rowNumber++;
            $cells = $rowObj->toArray(); // 단순 배열

            // 1행: 헤더 처리
            if ($rowNumber === 1) {
                foreach ($cells as $i => $h) {
                    $name = trim((string)$h);
                    if ($name !== '') $headerIndex[$name] = $i;
                }
                // 필수 헤더 검사
                if (!array_key_exists('mb_id', $headerIndex)) {
                    echo "헤더에 'mb_id' 컬럼명이 없습니다. (1행에 정확히 'mb_id'를 넣어주세요)";
                    $reader->close();
                    exit;
                }
                $hasIdx = array_key_exists('idx', $headerIndex);
                continue;
            }

            // 2행: 무시
            if ($rowNumber === 2) {
                continue;
            }

            // 3행부터: 데이터 처리
            // 완전 빈 줄 스킵
            $nonEmpty = array_filter($cells, function($v){ return trim((string)$v) !== ''; });
            if (!$nonEmpty) continue;

            // 행 -> 연관배열 구성
            $row = [];
            if ($hasIdx) {
                $idxPos = $headerIndex['idx'];
                $row['idx'] = isset($cells[$idxPos]) ? trim((string)$cells[$idxPos]) : '';
            }
            foreach (ALLOWED_COLUMNS as $col) {
                if (!array_key_exists($col, $headerIndex)) continue; // 헤더 없는 컬럼은 무시
                $pos = $headerIndex[$col];
                $row[$col] = isset($cells[$pos]) ? $cells[$pos] : null;
            }

            // 필수: mb_id
            $mb_id = isset($row['mb_id']) ? trim((string)$row['mb_id']) : '';
            if ($mb_id === '') {
                $fail++; $errors[] = "행{$rowNumber} INSERT 실패: mb_id가 비어있습니다.";
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
            if ($res) $ok++; else { $fail++; $errors[] = "행{$rowNumber} 쿼리 실패"; }
        }
        // 첫 시트만 처리
        break;
    }
    $reader->close();
} catch (Exception $e) {
    if (method_exists($reader, 'close')) { $reader->close(); }
    echo "처리 중 오류: " . htmlspecialchars($e->getMessage());
    exit;
}

/* =========================
 * 5) 결과 출력 & 리다이렉트
 * ========================= */
echo "<h3>처리 결과</h3>";
echo "<div>성공: {$ok}건</div>";
echo "<div>실패: {$fail}건</div>";
if ($errors) {
    echo "<hr><pre>".htmlspecialchars(implode("\n", $errors), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')."</pre>";
}

// 기존 코드 호환: $qstr / 마지막 mb_id 기준 리다이렉트
$qstr = isset($qstr) ? $qstr : '';
if (function_exists('goto_url')) {
    goto_url('./member_student_list.php?' . $qstr . '&w=u&mb_id=' . urlencode($last_mb_id), false);
}
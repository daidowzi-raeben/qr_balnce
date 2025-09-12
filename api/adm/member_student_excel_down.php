<?php
include_once('./_common.php'); // 필요 시

// ===== 0) Spout 로드 =====
$autoload = __DIR__ . '/vendor/autoload.php';
if (!is_file($autoload)) {
    echo "라이브러리(Spout)가 설치되어 있지 않습니다.<br>".
         "이 파일과 같은 경로에서 아래 명령을 실행하세요.<br>".
         "<pre>composer require box/spout:^2.7</pre>";
    exit;
}
require_once $autoload;

use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;
use Box\Spout\Writer\Style\StyleBuilder;

// ===== 1) 필터 파라미터(선택) =====
// 예: ?bo_like=free%  로 호출하면 'free%'만
$bo_like      = isset($_GET['bo_like']) ? $_GET['bo_like'] : 'free%';
$limit_offset = isset($_GET['offset']) ? max(0, (int)$_GET['offset']) : 0;
$limit_count  = isset($_GET['limit'])  ? max(1, (int)$_GET['limit'])  : 10000; // 필요시 조정

// LIKE 인자 최소한의 이스케이프
$bo_like_esc = addslashes($bo_like);

// ===== 2) 목록 조회 (닉네임 조인 포함) =====
$sql = "
  SELECT a.*, s.mb_nick
  FROM qr_member_student_qa AS a
  LEFT JOIN qr_member_student AS s
    ON s.idx = a.mb_id   -- 사번 기준으로 닉네임 매칭
  WHERE a.bo_table LIKE '{$bo_like_esc}'
  ORDER BY a.id DESC
  LIMIT {$limit_offset}, {$limit_count}
";
$result = sql_query($sql);

// ===== 3) Spout 작성기 준비 =====
$writer   = WriterFactory::create(Type::XLSX);
$filename = 'member_student_qa_' . date('Ymd_His') . '.xlsx';

// 브라우저로 직접 스트리밍
$writer->openToBrowser($filename);

// 시트명
if (method_exists($writer, 'getCurrentSheet')) {
    $writer->getCurrentSheet()->setName('질의응답');
}

// 헤더 스타일(굵게)
$headerStyle = (new StyleBuilder())->setFontBold()->build();

// ===== 4) 헤더 작성 =====
$headers = ['사번','닉네임','질의','답변','응답일'];
if (method_exists($writer, 'addRowWithStyle')) {
    $writer->addRowWithStyle($headers, $headerStyle);
} else {
    // Spout 2.7 환경에서 addRowWithStyle가 없다면 기본 addRow로 대체
    $writer->addRow($headers);
}

// ===== 5) 데이터 채우기 =====
while ($row = sql_fetch_array($result)) {
    // (1) 질문 제목: qr_write_{bo_table}에서 num 번째(0-based) 한 개
    $bo_table = $row['bo_table'];
    $question = '';
    // 안전을 위해 테이블명 화이트리스트(영문/숫자/언더바만 허용)
    if (preg_match('/^[a-zA-Z0-9_]+$/', $bo_table)) {
        $q_table = "qr_write_{$bo_table}";
        $num = (int)$row['num']; // LIMIT offset 용도
        $sql_q = "SELECT wr_subject FROM {$q_table} LIMIT {$num}, 1";
        $qq = sql_fetch($sql_q);
        if (isset($qq['wr_subject'])) $question = $qq['wr_subject'];
    }

    // (2) 답변: content 우선, 비었으면 chk 사용
    $answer = '';
    if (isset($row['content']) && trim((string)$row['content']) !== '') {
        $answer = $row['content'];
    } elseif (isset($row['chk']) && trim((string)$row['chk']) !== '') {
        $answer = $row['chk'];
    }

    // (3) 응답일: c_date → YY-MM-DD 형태
    $resp = '';
    if (!empty($row['c_date'])) {
        $ts = strtotime($row['c_date']);
        if ($ts !== false) $resp = date('y-m-d', $ts);
    }

    // (4) 한 행 쓰기: 사번, 닉네임, 질의, 답변, 응답일
    $writer->addRow([
        isset($row['mb_id'])   ? $row['mb_id']   : '',
        isset($row['mb_nick']) ? $row['mb_nick'] : '',
        $question,
        $answer,
        $resp,
    ]);
}

// ===== 6) 마무리 =====
$writer->close();
exit;
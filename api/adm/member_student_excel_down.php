<?php
include_once('./_common.php'); // 필요 시

// ===== 0) PhpSpreadsheet 로드 =====
$autoload = __DIR__ . '/vendor/autoload.php';
if (!is_file($autoload)) {
    echo "라이브러리(PhpSpreadsheet)가 설치되어 있지 않습니다.<br>".
         "이 파일과 같은 경로에서 아래 명령을 실행하세요.<br>".
         "<pre>composer require phpoffice/phpspreadsheet</pre>";
    exit;
}
require_once $autoload;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// ===== 1) 필터 파라미터(선택) =====
// 예: ?bo_like=free%  로 호출하면 'free%'만
$bo_like = isset($_GET['bo_like']) ? $_GET['bo_like'] : 'free%';
$limit_offset = isset($_GET['offset']) ? max(0, (int)$_GET['offset']) : 0;
$limit_count  = isset($_GET['limit'])  ? max(1, (int)$_GET['limit'])  : 10000; // 필요시 조정

// ===== 2) 목록 조회 (닉네임 조인 포함) =====
$sql = "
  SELECT a.*, s.mb_nick
  FROM qr_member_student_qa AS a
  LEFT JOIN qr_member_student AS s
    ON s.idx = a.mb_id   -- 사번 기준으로 닉네임 매칭
  WHERE a.bo_table LIKE '".addslashes($bo_like)."'
  ORDER BY a.id DESC
  LIMIT {$limit_offset}, {$limit_count}
";
$result = sql_query($sql);

// ===== 3) 스프레드시트 생성 및 헤더 =====
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('질의응답');

$headers = ['사번','닉네임','질의','답변','응답일'];
$col = 1;
foreach ($headers as $h) {
    $sheet->setCellValueByColumnAndRow($col++, 1, $h);
}
// 간단 스타일(볼드)
$sheet->getStyle('A1:E1')->getFont()->setBold(true);

// ===== 4) 데이터 채우기 =====
$rowNum = 2;

while ($row = sql_fetch_array($result)) {
    // (1) 질문 제목: qr_write_{bo_table}에서 num 번째(0-based) 한 개
    $bo_table = $row['bo_table'];
    // 안전을 위해 테이블명 화이트리스트(영문/숫자/언더바만 허용)
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $bo_table)) {
        $question = '';
    } else {
        $q_table = "qr_write_{$bo_table}";
        $num = (int)$row['num']; // LIMIT offset 용도
        $sql_q = "SELECT wr_subject FROM {$q_table} LIMIT {$num}, 1";
        $qq = sql_fetch($sql_q);
        $question = isset($qq['wr_subject']) ? $qq['wr_subject'] : '';
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

    // (4) 엑셀 행 기록: 사번, 닉네임, 질의, 답변, 응답일
    $col = 1;
    $sheet->setCellValueByColumnAndRow($col++, $rowNum, $row['mb_id']);          // 사번
    $sheet->setCellValueByColumnAndRow($col++, $rowNum, $row['mb_nick']);        // 닉네임(LEFT JOIN)
    $sheet->setCellValueByColumnAndRow($col++, $rowNum, $question);              // 질의(wr_subject)
    $sheet->setCellValueByColumnAndRow($col++, $rowNum, $answer);                // 답변
    $sheet->setCellValueByColumnAndRow($col++, $rowNum, $resp);                  // 응답일

    $rowNum++;
}

// ===== 5) 열 너비 자동/가독성 =====
foreach (range('A','E') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// ===== 6) 다운로드 응답 =====
$filename = 'member_student_qa_'.date('Ymd_His').'.xlsx';
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="'.$filename.'"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');

// goto_url('./member_study_list.php?' . $qstr . '&amp;w=u&amp;mb_id=' . $mb_id, false);
exit;
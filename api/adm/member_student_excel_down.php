<?php
// /adm/excel_export_member_student_qa.php
include_once('./_common.php');

// ===== 1) 파라미터 =====
$bo_like      = isset($_GET['bo_like']) ? $_GET['bo_like'] : 'free%';
$limit_offset = isset($_GET['offset']) ? max(0, (int)$_GET['offset']) : 0;
$limit_count  = isset($_GET['limit'])  ? max(1, (int)$_GET['limit'])  : 10000;

$bo_like_esc = addslashes($bo_like);

// ===== 2) 데이터 조회 =====
$sql = "
  SELECT a.*, s.mb_nick
  FROM qr_member_student_qa AS a
  LEFT JOIN qr_member_student AS s
    ON s.idx = a.mb_id
  WHERE a.bo_table LIKE '{$bo_like_esc}'
  ORDER BY a.id DESC
  LIMIT {$limit_offset}, {$limit_count}
";
$result = sql_query($sql);

// ===== 3) 그누보드 내장 writeexcel 로드 =====
require_once G5_LIB_PATH.'/Excel/php_writeexcel/class.writeexcel_workbook.inc.php';
require_once G5_LIB_PATH.'/Excel/php_writeexcel/class.writeexcel_worksheet.inc.php';

// 임시 파일에 작성 후 스트리밍
$tmp_dir = G5_DATA_PATH.'/tmp';
@mkdir($tmp_dir, G5_DIR_PERMISSION, true);
$fname = tempnam($tmp_dir, 'xls');

// 워크북/시트
$workbook  = new writeexcel_workbook($fname);
$worksheet = &$workbook->addworksheet('질의응답');

// 서식(헤더 Bold)
$fmt_header = &$workbook->addformat();
$fmt_header->set_bold();

// ===== 4) 헤더 작성 =====
$headers = array('사번','닉네임','질의','답변','응답일');
$row_idx = 0;
foreach ($headers as $col => $text) {
    $worksheet->write($row_idx, $col, $text, $fmt_header);
}
$row_idx++;

// ===== 5) 데이터 행 추가 =====
while ($row = sql_fetch_array($result)) {
    $bo_table = $row['bo_table'];
    $question = '';

    // 테이블명 화이트리스트(영문/숫자/언더바)
    if ($bo_table && preg_match('/^[a-zA-Z0-9_]+$/', $bo_table)) {
        $q_table = "qr_write_{$bo_table}";
        $num = (int)$row['num'];
        $sql_q = "SELECT wr_subject FROM {$q_table} LIMIT {$num}, 1";
        $qq = sql_fetch($sql_q);
        if (!empty($qq['wr_subject'])) $question = $qq['wr_subject'];
    }

    // 답변: content 우선, 없으면 chk
    $answer = '';
    if (isset($row['content']) && trim((string)$row['content']) !== '') {
        $answer = $row['content'];
    } elseif (isset($row['chk']) && trim((string)$row['chk']) !== '') {
        $answer = $row['chk'];
    }

    // 응답일: c_date → YY-MM-DD
    $resp = '';
    if (!empty($row['c_date'])) {
        $ts = strtotime($row['c_date']);
        if ($ts !== false) $resp = date('y-m-d', $ts);
    }

    // 한 행 쓰기
    $worksheet->write($row_idx, 0, isset($row['mb_id'])   ? $row['mb_id']   : '');
    $worksheet->write($row_idx, 1, isset($row['mb_nick']) ? $row['mb_nick'] : '');
    $worksheet->write($row_idx, 2, $question);
    $worksheet->write($row_idx, 3, $answer);
    $worksheet->write($row_idx, 4, $resp);

    $row_idx++;
}

// ===== 6) 마무리 및 다운로드 =====
$workbook->close();

$filename = 'member_student_qa_'.date('Ymd_His').'.xls';
header("Content-Type: application/vnd.ms-excel; name=\"{$filename}\"");
header("Content-Transfer-Encoding: binary");
header("Content-Disposition: attachment; filename=\"{$filename}\"");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Pragma: public");

$fh = fopen($fname, 'rb');
fpassthru($fh);
fclose($fh);
@unlink($fname);
exit;
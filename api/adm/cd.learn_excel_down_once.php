<?php
// 데이터베이스 연결 설정
$host = "localhost";
$username = "root";
$password = "sejong~5273!!";
$database = "cleandesk2";



$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("데이터베이스 연결 실패: " . $conn->connect_error);
}

$querys = " ";

if(isset($_GET['company']) && $_GET['company'] != '') { 
$querys .= "  and cll.lssn_company =  '{$_GET['company']}'  ";
}

if(isset($_GET['y']) && $_GET['y'] != '') { 
$querys .= "  AND lssn_year = '{$_GET['y']}'    ";
}

if(isset($_GET['t']) && $_GET['t'] != '') { 
$querys .= "  AND cll.lssn_title = '{$_GET['t']}'   ";
}






// 쿼리 실행
$sql = "
    SELECT 
        cm.mb_name AS 이름, 
        cm.mb_id AS 아이디, 
        cm.mb_4 AS 부서명,
        cll.lssn_title AS 학습명, 
        (
            SELECT app_rdate 
            FROM cd_lesson_apply 
            WHERE app_uid = cm.mb_id 
            AND app_lssn_no = cll.lssn_no 
            ORDER BY app_lssn_no ASC LIMIT 1
        ) AS 학습시작일,
        (
            SELECT att_study_last 
            FROM cd_lms_chapter_attend 
            WHERE att_lssn_no = cll.lssn_no 
            AND att_uid = cm.mb_id 
            ORDER BY att_study_last DESC LIMIT 1
        ) AS 학습종료일,
        (
            SELECT COUNT(*) 
            FROM cd_lms_lesson AS a 
            INNER JOIN cd_lms_chapter AS b ON a.lssn_no = b.cpt_lesson 
            WHERE a.lssn_no = cll.lssn_no
        ) AS 총챕터수,
        (
            SELECT COUNT(*) 
            FROM cd_lms_chapter_attend 
            WHERE att_uid = cm.mb_id 
            AND att_lssn_no = cll.lssn_no
        ) AS 완료챕터수
    FROM cd_lms_lesson AS cll
    INNER JOIN cd_member AS cm ON cll.lssn_company = cm.mb_profile
    WHERE  
     cll.lssn_kind = 'LS01' 

{$querys}

    ORDER BY cm.mb_datetime ASC
";


$result = $conn->query($sql);

// 엑셀 파일 다운로드 설정
$filename = "학습현황_" . date("Ymd_His") . ".xls";
header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Cache-Control: max-age=0");

// UTF-8 BOM 추가 (한글 깨짐 방지)
echo "\xEF\xBB\xBF";

// 엑셀 테이블 헤더 출력
echo "<table border='1'>";
echo "<tr>
        <th>이름</th>
        <th>아이디</th>
        <th>부서명</th>
        <th>학습명</th>
        <th>학습시작일</th>
        <th>학습종료일</th>
        <th>학습진도율</th>
        <th>수료여부</th>
      </tr>";

// 데이터 출력
while ($row = $result->fetch_assoc()) {
    $진도율 = ($row['완료챕터수'] > 0) ? floor(($row['완료챕터수'] / $row['총챕터수']) * 100) . '%' : '0%';
    $수료여부 = ($진도율 == '100%') ? '수료' : '미수료';

    echo "<tr>
            <td>{$row['이름']}</td>
            <td>{$row['아이디']}</td>
            <td>{$row['부서명']}</td>
            <td>{$row['학습명']}</td>
            <td>{$row['학습시작일']}</td>
            <td>{$row['학습종료일']}</td>
            <td>{$진도율}</td>
            <td>{$수료여부}</td>
          </tr>";
}
echo "</table>";

// 데이터베이스 연결 종료
$conn->close();
exit;
?>

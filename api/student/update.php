<?php
include_once('./_common.php');


if($mb_nick) {
$sql = " select * from qr_member_student where mb_id = '{$mb_id}' and mb_nick = '{$mb_nick}' limit 1  ";
$result = $sql;
$row = sql_fetch($result);
if(isset($row['idx']) && $row['idx'] > 1) {
    if($row['mb_nick'] == $mb_nick) {
        alert('닉네임이 중복되었습니다.', G5_URL.'/student/?bo_table='.$bo_table);
        exit;
    } else {
        alert('로그인 되었습니다.', G5_URL.'/student/start.php?bo_table='.$bo_table.'&id='.$row['idx']);
    }
    
} else {


$sql = " select * from qr_member_student where mb_nick = '{$mb_nick}' limit 1  ";
$result = $sql;
$row = sql_fetch($result);

if(isset($row['mb_nick']) && $row['mb_nick'] == $mb_nick) {
     alert('닉네임이 중복되었습니다.', G5_URL.'/student/?bo_table='.$bo_table);
    } else {
        
            sql_query("
        INSERT INTO `qr_member_student` (`mb_id`, `mb_nick`, `bo_table`, `c_datetime`, `c_date`)
        VALUES
            ('{$mb_id}', '{$mb_nick}', '{$bo_table}', now(), now());
    ");
    $sql = " select * from qr_member_student where mb_id = '{$mb_id}' and mb_nick = '{$mb_nick}' limit 1  ";
$result = $sql;
$row = sql_fetch($result);
     alert('로그인 되었습니다.', G5_URL.'/student/start.php?num=0&bo_table='.$bo_table.'&id='.$row['idx']);
    }
}

}


if($mode == 'insert') {
 sql_query("
 INSERT INTO `qr_member_student_qa` (`mb_id`, `num`, `chk`, `content`, `bo_table`, `c_datetime`, `c_date`)
VALUES
	('{$id}', '{$num}', '{$chk}', '{$content}', '{$bo_table}', now(), now());

 ");

 $nums = $num + 1;
 alert('저장되었습니다.', '/student/start.php?bo_table='.$bo_table.'&id='.$id.'&num='.$nums);

}

  
?>
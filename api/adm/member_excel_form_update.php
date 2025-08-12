<?php
$sub_menu = "100100";
include_once("./_common.php");
include_once(G5_LIB_PATH."/register.lib.php");
include_once(G5_LIB_PATH.'/thumbnail.lib.php');

if ($w == 'u')
    check_demo();

auth_check($auth[$sub_menu], 'w');

check_admin_token();

//업로드 파일 확인
if (isset($_FILES['mb_excel']) && is_uploaded_file($_FILES['mb_excel']['tmp_name'])) {
	$file = $_FILES['mb_excel']['tmp_name'];
	
	$mb_email = '';
	$mb_nick = '';
	$mb_certify = '';
    $mb_adult = 0;
	$mb_hp = '';
	
	$bl = get_blcode_mb($_POST['bl_year'], $_POST['bl_cate'], $_POST['bl_name']);
	if(!$bl['bl_code'])
		alert('소속 정보를 확인바랍니다.\\n년도: '.$_POST['bl_year'].'\\n분야 : '.$_POST['bl_cate'].'\\n소속 : '.$_POST['bl_name']);
	
	$sql_common = "  
                 mb_nick = '{$mb_nick}',
                 mb_email = '{$mb_email}',
                 mb_homepage = '{$_POST['mb_homepage']}',
                 mb_tel = '{$_POST['mb_tel']}',
                 mb_hp = '{$mb_hp}',
                 mb_certify = '{$mb_certify}',
                 mb_adult = '{$mb_adult}',
                 mb_zip1 = '$mb_zip1',
                 mb_zip2 = '$mb_zip2',
                 mb_addr1 = '{$_POST['mb_addr1']}',
                 mb_addr2 = '{$_POST['mb_addr2']}',
                 mb_addr3 = '{$_POST['mb_addr3']}',
                 mb_addr_jibeon = '{$_POST['mb_addr_jibeon']}',
                 mb_signature = '{$_POST['mb_signature']}',
                 mb_leave_date = '{$_POST['mb_leave_date']}',
                 mb_intercept_date='{$_POST['mb_intercept_date']}',
                 mb_memo = '{$_POST['mb_memo']}',
                 mb_mailling = '{$_POST['mb_mailling']}',
                 mb_sms = '{$_POST['mb_sms']}',
                 mb_open = '{$_POST['mb_open']}',
                 mb_profile = '{$_POST['mb_profile']}',
                 mb_level = '1',
                 mb_1 = '{$_POST['mb_1']}',
                 mb_7 = '{$_POST['mb_7']}',
                 mb_8 = '{$_POST['mb_8']}',
                 mb_9 = '{$_POST['mb_9']}',
                 mb_10 = '{$_POST['mb_10']}' ";

    include_once(G5_LIB_PATH.'/Excel/reader.php');

    $data = new Spreadsheet_Excel_Reader();

    // Set output Encoding.
    $data->setOutputEncoding('UTF-8');
	
	$data->read($file);
	
	error_reporting(E_ALL ^ E_NOTICE);

    $dup_it_id = array();
    $fail_it_id = array();
    $dup_count = 0;
    $total_count = 0;
    $fail_count = 0;
    $succ_count = 0;

    for ($i = 3; $i <= $data->sheets[0]['numRows']; $i++) {
        $total_count++;

        $j = 2;

		$mb_name			= addslashes($data->sheets[0]['cells'][$i][$j++]);
		$mb_id				= addslashes($data->sheets[0]['cells'][$i][$j++]);
		$mb_password		= $mb_id;
		$mb_3				= addslashes($data->sheets[0]['cells'][$i][$j++]);
		$mb_4				= addslashes($data->sheets[0]['cells'][$i][$j++]);
		$mb_5				= addslashes($data->sheets[0]['cells'][$i][$j++]);
		$mb_6				= addslashes($data->sheets[0]['cells'][$i][$j++]);
				
        if(!$mb_id || !$mb_name) {
            $fail_count++;
            continue;
        }

        // mb_id 중복체크
        $sql2 = " select count(*) as cnt from {$g5['member_table']} where mb_id = '$mb_id' ";
        $row2 = sql_fetch($sql2);
        if($row2['cnt']) {
            $fail_mb_id[] = $mb_id;
            $dup_mb_id[] = $mb_id;
            $dup_count++;
            $fail_count++;
            continue;
        }

		/*
		mb_datetime = '".G5_TIME_YMDHIS."',
		*/

        $sql = " INSERT INTO {$g5['member_table']}
                     SET 
						mb_id = '$mb_id',
						mb_name = '$mb_name',
						mb_2 = '$mb_2',
						mb_3 = '$mb_3',
						mb_4 = '$mb_4',
						mb_password = '".get_encrypt_string($mb_password)."',
						mb_datetime = '".G5_TIME_YMDHIS."', 
						mb_ip = '{$_SERVER['REMOTE_ADDR']}', 
						mb_email_certify = '".G5_TIME_YMDHIS."', 
						bl_no = '{$bl['bl_no']}',
                        {$sql_common} ";
        sql_query($sql);

        $succ_count++;
    }
}

alert('회원등록을 완료했습니다.\\n총회원수 : '.number_format($total_count).'\\n등록건수 : '.number_format($succ_count).'\\n실패건수 : '.number_format($fail_count), './member_list.php?'.$qstr.'&amp;');
//goto_url('./member_list.php?'.$qstr.'&amp;', false);
?>
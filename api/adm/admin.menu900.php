<?php
$menu["menu900"] = array (
	
	array('900000', '환경설정', G5_ADMIN_URL.'/config_form.php',   'config'),
    array('900100', '기본환경설정', G5_ADMIN_URL.'/config_form.php',   'cf_basic'),
    array('900200', '관리권한설정', G5_ADMIN_URL.'/auth_list.php',     'cf_auth'),
    array('900280', '테마설정', G5_ADMIN_URL.'/theme.php',     'cf_theme', 1),
    array('900290', '메뉴설정', G5_ADMIN_URL.'/menu_list.php',     'cf_menu', 1),
    array('900300', '메일 테스트', G5_ADMIN_URL.'/sendmail_test.php', 'cf_mailtest'),
    array('900310', '팝업레이어관리', G5_ADMIN_URL.'/newwinlist.php', 'scf_poplayer'),
    array('900800', '세션파일 일괄삭제',G5_ADMIN_URL.'/session_file_delete.php', 'cf_session', 1),
    array('900900', '캐시파일 일괄삭제',G5_ADMIN_URL.'/cache_file_delete.php',   'cf_cache', 1),
    array('900910', '캡챠파일 일괄삭제',G5_ADMIN_URL.'/captcha_file_delete.php',   'cf_captcha', 1),
    array('900920', '썸네일파일 일괄삭제',G5_ADMIN_URL.'/thumbnail_file_delete.php',   'cf_thumbnail', 1),
    array('900500', 'phpinfo()',        G5_ADMIN_URL.'/phpinfo.php',       'cf_phpinfo')
	
	/*
    array('900000', 'SMS 관리', ''.G5_SMS5_ADMIN_URL.'/config.php', 'sms5'),
    array('900100', 'SMS 기본설정', ''.G5_SMS5_ADMIN_URL.'/config.php', 'sms5_config'),
    array('900200', '회원정보업데이트', ''.G5_SMS5_ADMIN_URL.'/member_update.php', 'sms5_mb_update'),
    array('900300', '문자 보내기', ''.G5_SMS5_ADMIN_URL.'/sms_write.php', 'sms_write'),
    array('900400', '전송내역-건별', ''.G5_SMS5_ADMIN_URL.'/history_list.php', 'sms_history' , 1),
    array('900410', '전송내역-번호별', ''.G5_SMS5_ADMIN_URL.'/history_num.php', 'sms_history_num' , 1),
    array('900500', '이모티콘 그룹', ''.G5_SMS5_ADMIN_URL.'/form_group.php' , 'emoticon_group'),
    array('900600', '이모티콘 관리', ''.G5_SMS5_ADMIN_URL.'/form_list.php', 'emoticon_list'),
    array('900700', '휴대폰번호 그룹', ''.G5_SMS5_ADMIN_URL.'/num_group.php' , 'hp_group', 1),
    array('900800', '휴대폰번호 관리', ''.G5_SMS5_ADMIN_URL.'/num_book.php', 'hp_manage', 1),
    array('900900', '휴대폰번호 파일', ''.G5_SMS5_ADMIN_URL.'/num_book_file.php' , 'hp_file', 1)
	*/
);
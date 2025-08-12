<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가;

//로그인 메세지
define('CD_LOGIN_MSG',      '로그인이 필요합니다');
//클린데스크 버전
define('CD_LMS_VER',	    '0.1');
define('CD_CSS_VER',	    '230314');
define('CD_JS_VER',		    '210315');
//클린데스크 테마 js, css, img 파일 경로
define('CD_THEME_JS_URL',	G5_THEME_URL.'/_Js');
define('CD_THEME_CSS_URL',	G5_THEME_URL.'/_Css');
define('CD_THEME_IMG_URL',	G5_THEME_URL.'/_Img');

//게시판 링크 갯수
define('CD_LINK_COUNT', 0);
//클린데스트 전용 이미지 폴더
define('CD_IMG_URL', G5_URL.'/'.G5_IMG_DIR. '_cd');
define('CD_IMG_DIR', G5_PATH.'/'.G5_IMG_DIR. '_cd');


$g5['survey_table'] 		= G5_TABLE_PREFIX.'survey'; 			// 설문지 테이블
$g5['survey_data_table'] 	= G5_TABLE_PREFIX.'survey_data'; 		// 설문 데이터 테이블
$g5['lesson_table'] 		= G5_TABLE_PREFIX.'lms_lesson';		 	// 사이버교육 > 과정 테이블
$g5['less_apply_table'] 	= G5_TABLE_PREFIX.'lesson_apply';		// 사이버 학습 테이블
$g5['chapter_table'] 		= G5_TABLE_PREFIX.'lms_chapter';		// 과정 > 챕터 테이블
$g5['chapter_att_table'] 	= G5_TABLE_PREFIX.'lms_chapter_attend';	// 차시 학습 진도 테이블
$g5['contents_table'] 		= G5_TABLE_PREFIX.'lms_contents';		// 차시 콘텐츠 테이블
$g5['certify_table'] 		= G5_TABLE_PREFIX.'certification';		// 수료증 테이블
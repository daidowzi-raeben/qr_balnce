<?php
$menu['menu100'][1][0] = '100100';
$menu['menu100'][2][0] = '100200';
$menu['menu100'][3][0] = '100290';
add_stylesheet('<link rel="stylesheet" href="'.G5_ADMIN_URL.'/css/admin_lnb.css">', 10);
add_javascript('<script>
$(function(){
	// 인덱스페이지 사이즈 변경
	if ($("#lnb li").length == 0) {
		$("#lnb").hide();
		$("#wrapper").css({"display": "block", "border-left": "0"});
	}

	// 현재탭의 버튼 활성
	if ($("ul.anchor").length > 1) {
		$("ul.anchor").each(function(index){
			$(this).find("a").eq(index).addClass("on");
		});
	} else {
		$("ul.anchor a").each(function() {
			if ( $(this).attr("href").match(location.href.replace(/.*\/([a-zA-Z0-9_-|\.]+)(\?.*)?$/g, "\$1")) )
				$(this).addClass("on");
		});
	}

	// 중요 관리권한항목 삭제
	$("select#au_menu option[value=100100], select#au_menu option[value=100200], select#au_menu option[value=100290]").remove();
});
</script>', 10);

if($sub_menu) {
	$menu_key = substr($sub_menu, 0, 3);
	$i = 0;
	foreach($menu['menu'.$menu_key] as $key=>$value) {
		if($key > 0) {
			if ($is_admin != 'super' && (!array_key_exists($value[0],$auth) || !strstr($auth[$value[0]], 'r'))) {
				continue;
			}

			if ($sub_menu == $value[0]) {
				$on_index = $i;
				break;
			}

			$i++;
		}
	}
	add_javascript('<script>$(function() { $("#lnb li").eq('.$on_index.').addClass("on"); });</script>', 11);
}
?>
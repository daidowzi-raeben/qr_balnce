<?php
$sub_menu = "300110";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

if ($w == '')
{
    $required_mb_id = 'required';
    $required_mb_id_class = 'required alnum_';
    $sound_only = '<strong class="sound_only">필수</strong>';

    $html_title = '추가';
}
else if ($w == 'u')
{
	$lssn = get_lesson($lno);
    if (!$lssn['lssn_no'])
        alert('존재하지 않는 과정정보입니다.');
}

$sql_common = " from {$g5['lesson_table']} ";

$sql_search = " where (1) and lssn_no = {$lno} ";

$sql = " select count(*) as cnt {$sql_common} {$sql_search} {$sql_order} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$startNum = 1 + (($page-1) * 15);


$g5['title'] = '과정목록 > 과정등록';
include_once('./cd.admin.head.php');

$sql = " select * {$sql_common} {$sql_search} {$sql_order} limit {$from_record}, {$rows} ";
$result = sql_query($sql);

$colspan = 16;

include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');
add_javascript('<script type="text/javascript" src="'.CD_THEME_JS_URL.'/jquery.bpopup.min.js"></script>', 0);
?>
<script>
    $(document).ready(function(){
        $('.datepicker').datepicker({
			dateFormat: "yy-mm-dd"
		});
    })
</script>
<div class="tbl_frm02 tbl_wrap">
    <!--<p>
        회원자료 삭제 시 다른 회원이 기존 회원아이디를 사용하지 못하도록 회원아이디, 이름, 닉네임은 삭제하지 않고 영구 보관합니다.
    </p>-->
    <form id="flesson" name="flesson" action="./cd.process_form_update.php" class="local_sch01 local_sch11" method="post" onsubmit="return flesson_submit(this);">
	<input type="hidden" name="w" value="<?php echo $w ?>">
	<input type="hidden" name="lno" value="<?php echo $lno ?>">
        <table>
            <caption><?php echo $g5['title']; ?> 검색</caption>
            <colgroup>
                <col class="grid_4">
                <col>
                <col class="grid_4">
                <col>
            </colgroup>
            <tbody>
            <tr>
                <th scope="row"><label for="lssn_title">과정명<?php echo $sound_only ?></label></th>
                <td colspan="3">
                    <input type="text" class="new__input width_800" name="lssn_title" id="lssn_title" value="<?php echo $lssn['lssn_title'] ?>" <?php echo $required_mb_id ?>  />
                </td>
            </tr>
			
            </tbody>
        </table>
        <div class="sch_div">
            <input type="submit" class="btn btn_03" value="과정등록">
            <a href="./cd.process_list.php" class="btn btn_02">돌아가기</a>
        </div>
		<div id="popup_win" class="pop-layer bPopup">  
			<div class="popup_container" id="popup_container">
				test
			</div>
		</div>
    </form>
</div>


<div class="searchPop" id="searchPop-tst" style="display: none;">
    <div class="searchPop__content">
        <div class="searchPop__row searchPop__row--title">
            <h3 class="searchPop__row-title">과정명</h3>
            <p>
                NS윤리준법 시스템 사이버 교육 설문
            </p>
        </div>
        <div class="searchPop__row searchPop__row--search">
            <h3 class="searchPop__row-title">검색어</h3>
            <div>
                <input type="text" class="new__input"> <button type="button" class="btn btn_03">검색</button>
            </div>
        </div>
        <div class="searchPop__row searchPop__row--table">
            <table>
                <colgroup>
                    <col width="90">
                    <col width="50">
                    <col>
                    <col>
                    <col>
                    <col>
                    <col>
                </colgroup>
                <thead>
                <tr>
                    <th>선택</th>
                    <th>번호</th>
                    <th>시험지분류</th>
                    <th>시험지명</th>
                    <th>문항수</th>
                    <th>관리자</th>
                    <th>등록일시</th>
                    <th>수정일시</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>
                        <button class="btn btn_02">선택</button>
                    </td>
                    <td>4</td>
                    <td></td>
                    <td>NS윤리준법 시스템 사이버 교육 시험</td>
                    <td>10</td>
                    <td>admin</td>
                    <td>2018-06-24</td>
                    <td>2018-06-24</td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="sch_div d-flex justify-content-center mt-2">
            <a href="#//" class="btn btn_01">닫기</a>
        </div>
    </div>
</div>


<div class="searchPop" id="searchPop-rslt" style="display: none;">
    <div class="searchPop__content">
        <div class="searchPop__row searchPop__row--title">
            <h3 class="searchPop__row-title">과정명</h3>
            <p>
                NS윤리준법 시스템 사이버 교육 설문
            </p>
        </div>
        <div class="searchPop__row searchPop__row--search">
            <h3 class="searchPop__row-title">검색어</h3>
            <div>
                <input type="text" class="new__input"> <button type="button" class="btn btn_03">검색</button>
            </div>
        </div>
        <div class="searchPop__row searchPop__row--table">
            <table>
                <colgroup>
                    <col width="90">
                    <col width="50">
                    <col>
                    <col>
                    <col>
                    <col>
                    <col>
                </colgroup>
                <thead>
                <tr>
                    <th>선택</th>
                    <th>번호</th>
                    <th>설문명</th>
                    <th>문항수</th>
                    <th>참여자 수</th>
                    <th>수정일시</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>
                        <button class="btn btn_02">선택</button>
                    </td>
                    <td>4</td>
                    <td>NS윤리준법 시스템 사이버 교육 설문</td>
                    <td>10</td>
                    <td>2</td>
                    <td>2018-06-24</td>
                </tr>
               
                </tbody>
            </table>
        </div>
        <div class="sch_div d-flex justify-content-center mt-2">
            <a href="#//" class="btn btn_01">닫기</a>
        </div>
    </div>
</div>


<script>
   
    function flesson_submit(f)
    {
		if(f.lssn_cond.value == '')
			f.lssn_cond.value = "진도 100%";
		if(f.lssn_point.value == '')
			f.lssn_point.value = 0;
        
		//alert(f.lssn_cond.value);
		//return false;
		return true;
    }


    function view_dir(dir) {
		var datas = "";
		if( dir != undefined ) {
			datas = "dir=" + dir;
			alert(dir);
		}
		$("#popup_win").bPopup();
		//$( "#popup_win" ).show( "blind");
		
		$.ajax({
			type: "POST",
			url: "./cd.ajax.process_dir.php",
			data :  datas,
			success: function(res){
				$("#popup_container").html("");
				var res_info = $.parseJSON( res );
				if( res_info.result == true ){	
					for(i=0;i<=res_info.directory.length-1;i++){
							var new_directory = '<div rel_code="'+res_info.directory[i]+'" style="padding:10px;cursor:pointer;width:100px;display: inline-block;"><span onclick="view_dir(\''+res_info.directory[i]+'\')">'+res_info.directory[i]+'</span><input type="button" value="선택" class="btn btn_03" onclick="select_dir(\''+res_info.directory[i]+'\')"/></div>';
							$("#popup_container").append(new_directory);
					}
					
					
				} else {
					//alert( $("#pannel_dept2 div").length );
				}
			},
			error: function(err){ alert("호출 실패하였습니다.") ;}
		});
	}
	
	function select_dir(url) {
		$("#lssn_img_link").val(url);
		$("#popup_win").bPopup().close();
	}
	
	function preview() {
		var img = $("#lssn_img_link").val();
		
		$("#popup_win").bPopup();
		$("#popup_container").html("");
		var new_img = '<img src="<?php echo CD_IMG_URL ?>/lssn_img/' + img + '">';
		$("#popup_container").append(new_img);
		//alert(img);
	}
	
	function preview2(v_dir) {
		
		alert(v_dir);
	
		var size_w = 1280;
		var size_h = 720;
		
		if( v_dir.length > 0 ) {	
				window.open("<?php echo G5_URL ?>"+v_dir,"content_preview","width="+size_w+"px,height="+size_h+"px");
		} else {
				alert("URL 입력후 미리보기가 가능합니다.")
		}
	}
</script>

<?php
include_once ('./admin.tail.php');
?>

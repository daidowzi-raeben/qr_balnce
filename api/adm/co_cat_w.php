<?php
$sub_menu = "800100";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');
$table = "cd_co_cate";
$idx = '';
if(isset($_GET['idx'])) {
	$idx = $_GET['idx'];
}

if ($w == '')
{
    $required_mb_id = 'required';
    $required_mb_id_class = 'required alnum_';
    $sound_only = '<strong class="sound_only">필수</strong>';

    $html_title = '추가';
}
else if ($w == 'u')
{
	$lssn = sql_fetch("select * from {$table} where idx = {$idx}");
    if (!$lssn['idx'])
        alert('존재하지 않는 정보입니다.');
}

$sql_common = " from {$table} ";

$sql_search = " where (1) and idx = {$idx} ";

$sql = " select count(*) as cnt {$sql_common} {$sql_search} {$sql_order} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$startNum = 1 + (($page-1) * 15);


$g5['title'] = '대분류 관리';
include_once('./cd.admin.head.php');

$sql = " select * {$sql_common} {$sql_search} {$sql_order} limit {$from_record}, {$rows} ";
$result = sql_query($sql);
# echo $sql;
$colspan = 16;

$sql_latest = "select * from {$table} order by idx desc limit 1";
$result_latest = sql_fetch($sql_latest);

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
    <form id="flesson" name="flesson" action="./co_cat_update.php" class="local_sch01 local_sch11" method="post" onsubmit="return flesson_submit(this);">
	<input type="hidden" name="w" value="<?php echo $w ?>">
	<input type="hidden" name="idx" value="<?php echo $idx ?>">
	<input type="hidden" name="mode" value="cd_co_cate">
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
                <th scope="row"><label for="lssn_title">분류명<?php echo $sound_only ?></label></th>
                <td colspan="3">
                    <input type="text" class="new__input width_800" name="cd_name" id="lssn_title" value="<?php echo $lssn['cd_name'] ?>" <?php echo $required_mb_id ?>  />
                </td>
            </tr>
			 <tr>
                <th scope="row"><label for="lssn_year">분류코드<?php echo $sound_only ?></label></th>
                <td colspan="3">
                    <input type="text" class="new__input" name="cd_code" id="lssn_year" value="<?php echo $lssn['cd_code'] ?>" <?php echo $required_mb_id ?>  />
					(최근 등록 코드 : <?php echo $result_latest['cd_code']; ?>)
                </td>
            </tr>
			<tr>
                <th scope="row"><label for="lssn_sdate">등록일</label></th>
                <td colspan="3">
                    <input type="text" class="new__input width_185 datepicker" name="cd_date" value="<?php echo $lssn['cd_date'] ? $lssn['cd_date'] : date("Y-m-d"); ?>" <?php echo $required_mb_id ?> />
                </td>
            </tr>
           
            
            </tbody>
        </table>
        <div class="sch_div">
            <input type="submit" class="btn btn_03" value="등록">
            <a href="./co_cat.php" class="btn btn_02">돌아가기</a>
        </div>
		<div id="popup_win" class="pop-layer bPopup">  
			<div class="popup_container" id="popup_container">
				test
			</div>
		</div>
    </form>
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

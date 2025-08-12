<?php
$sub_menu = "800500";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');
$table = "cd_co_detail";
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


$g5['title'] = '과정 정보 등록 관리';
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
    <form id="flesson" name="flesson" action="./co_cat_update.php"  enctype="multipart/form-data" class="local_sch01 local_sch11" method="post" onsubmit="return flesson_submit(this);">
	<input type="hidden" name="w" value="<?php echo $w ?>">
	<input type="hidden" name="idx" value="<?php echo $idx ?>">
	<input type="hidden" name="mode" value="cd_co_detail">
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
                <th scope="row"><label for="lssn_title">대분류명<?php echo $sound_only ?></label></th>
                <td colspan="3">
                    <select name="cd_cate1" onchange="cate(this)">
					<?php 
					$is_cate = '';
if($w == 'u') {
$sql_latest2 = "SELECT 
(SELECT cd_idx FROM cd_co_cate2 WHERE idx = a.cd_idx) AS cate_idx
FROM cd_co_cate3 AS a WHERE idx = {$idx}";
$result_latest2 = sql_fetch($sql_latest2);
}

					$sql_list = "SELECT * from cd_co_cate ORDER BY idx desc";
					$result_list = sql_query($sql_list);
					for ($k=0; $row2=sql_fetch_array($result_list); $k++) {
						if($k == 0) {
							$is_cate = $row2['idx'];
						}
						if($row2['idx'] == $lssn['cd_cate1']) {
							$is_cate = $row2['idx'];
						}
					?>
						<option value="<?php echo $row2['idx'];?>" <?php if($row2['idx'] == $lssn['cd_cate1']) { echo ' selected '; }?>><?php echo $row2['cd_name'];?></option>
						<?php } ?>
					</select>
                </td>
            </tr>
			<tr>
                <th scope="row"><label for="lssn_title">중분류명<?php echo $sound_only ?></label></th>
                <td colspan="3">
                    <select name="cd_cate2" id="cate2" onchange="cates(this)">
					<?php 
					$is_cate2 = '';
					$sql_list = "SELECT * from cd_co_cate2 where cd_idx = {$is_cate} ORDER BY idx desc";
					$result_list = sql_query($sql_list);
					for ($k=0; $row2=sql_fetch_array($result_list); $k++) {
						if($k == 0) {
							$is_cate2 = $row2['idx'];
						}
						if($row2['idx'] == $lssn['cd_cate2']) {
							$is_cate2 = $row2['idx'];
						}
					?>
						<option value="<?php echo $row2['idx'];?>" <?php if($row2['idx'] == $lssn['cd_cate2']) { echo ' selected '; }?>><?php echo $row2['cd_name'];?></option>
						<?php } ?>
					</select>
                </td>
            </tr>
			<tr>
                <th scope="row"><label for="lssn_title">소분류명<?php echo $sound_only ?></label></th>
                <td colspan="3">
                    <select name="cd_cate3" id="cate3" onchange="cates_l(this)">
					<?php 
					$is_cate3 = '';
					$sql_list = "SELECT * from cd_co_cate3 where cd_idx = {$is_cate2} ORDER BY idx desc";
					$result_list = sql_query($sql_list);
					for ($k=0; $row2=sql_fetch_array($result_list); $k++) {
						if($k == 0) {
							$is_cate3 = $row2['idx'];
						}
						if($row2['idx'] == $lssn['cd_cate3']) {
							$is_cate3 = $row2['idx'];
						}
					?>
						<option value="<?php echo $row2['idx'];?>" <?php if($row2['idx'] == $lssn['cd_cate3']) { echo ' selected '; }?>><?php echo $row2['cd_name'];?></option>
						<?php } ?>
					</select>
                </td>
            </tr>
			<tr>
                <th scope="row"><label for="lssn_title">과정주제<?php echo $sound_only ?></label></th>
                <td colspan="3">
                    <select name="cd_cate4" id="cate4">
					<?php 
					$sql_list = "SELECT * from cd_co_cate4 where cd_idx = {$is_cate3} ORDER BY idx desc";
					$result_list = sql_query($sql_list);
					for ($k=0; $row2=sql_fetch_array($result_list); $k++) {
					?>
						<option value="<?php echo $row2['idx'];?>" <?php if($row2['idx'] == $lssn['cd_cate4']) { echo ' selected '; }?>><?php echo $row2['cd_name'];?></option>
						<?php } ?>
					</select>
                </td>
            </tr>

			 <tr>
                <th scope="row"><label for="lssn_year">과정정보명<?php echo $sound_only ?></label></th>
                <td colspan="3">
                    <input type="text" class="new__input" name="cd_title" id="cd_title" value="<?php echo $lssn['cd_title'] ?>" <?php echo $required_mb_id ?>  />
					<button type="button" class="btn btn_02" onclick="javasctipt:document.getElementById('cd_title').value = document.getElementById('cate4').options[document.getElementById('cate4').selectedIndex].text">과정주제와 동일</button>
                </td>
            </tr>
			 <tr>
                <th scope="row"><label for="lssn_year">인큐베이터 적용<?php echo $sound_only ?></label></th>
                <td colspan="3">
				<label style="margin-right:20px;">
                    <input type="radio" name="cd_use"  value="Y"  <?php if($lssn['cd_use'] == 'Y' || !isset($lssn['cd_use'])) { echo 'checked';} ?> /> ON
					</label>
					<label>
					<input type="radio"  name="cd_use"  value="N"   /> OFF
					</label>
                </td>
            </tr>
			<tr>
                <th scope="row"><label for="lssn_year">썸네일<?php echo $sound_only ?></label></th>
                <td colspan="3">
                    <input type="file" class="new__input" name="cd_thum"  value="<?php echo $lssn['cd_thum'] ?>" <?php echo $required_mb_id ?>  />
					
                </td>
            </tr>
			<tr>
                <th scope="row"><label for="lssn_year">과정 연동(미리보기)<?php echo $sound_only ?></label></th>
                <td colspan="3">
                    <input type="text" class="new__input" name="cd_stu"  value="<?php echo $lssn['cd_stu'] ?>" <?php echo $required_mb_id ?>  />
					
                </td>
            </tr>
			<tr>
                <th scope="row"><label for="lssn_year">과정 정보 파일<?php echo $sound_only ?></label></th>
                <td colspan="3">
                    <input type="text" class="new__input" name="cd_file"  value="<?php echo $lssn['cd_file'] ?>" <?php echo $required_mb_id ?>  />
					저장경로 : (contents/movie)
                </td>
            </tr>
			<tr>
                <th scope="row"><label for="lssn_sdate">개발년월일</label></th>
                <td colspan="3">
                    <input type="text" class="new__input width_185 datepicker" name="cd_ym" value="<?php echo $lssn['cd_ym'] ? $lssn['cd_ym'] : date("Y-m-d"); ?>" <?php echo $required_mb_id ?> />
                </td>
            </tr>
			 <tr>
                <th scope="row"><label for="lssn_year">학습시간(분)<?php echo $sound_only ?></label></th>
                <td colspan="3">
                    <input type="text" class="new__input" name="cd_time" id="lssn_year" value="<?php echo $lssn['cd_time'] ?>" <?php echo $required_mb_id ?>  />
                </td>
            </tr>
			 <tr>
                <th scope="row"><label for="lssn_year">과정소개<?php echo $sound_only ?></label></th>
                <td colspan="3">
                    <textarea class="new__input" name="cd_content"><?php echo $lssn['cd_content'] ?></textarea>
                </td>
            </tr>
			 <tr>
                <th scope="row"><label for="lssn_year">학습목표<?php echo $sound_only ?></label></th>
                <td colspan="3">
                    <textarea class="new__input" name="cd_target"><?php echo $lssn['cd_target'] ?></textarea>
                </td>
            </tr>
			 <tr>
                <th scope="row"><label for="lssn_year">학습내용(목차)<?php echo $sound_only ?></label></th>
                <td colspan="3">
                    <textarea class="new__input" name="cd_con_list"><?php echo $lssn['cd_con_list'] ?></textarea>
                </td>
            </tr>
			 <tr>
                <th scope="row"><label for="lssn_year">학습대상<?php echo $sound_only ?></label></th>
                <td colspan="3">
                    <input type="text" class="new__input" name="cd_age" id="lssn_year" value="<?php echo $lssn['cd_age'] ?>" <?php echo $required_mb_id ?>  />
                </td>
            </tr>
			<tr>
                <th scope="row"><label for="lssn_year">내용전문가<?php echo $sound_only ?></label></th>
                <td colspan="3">
                    <input type="text" class="new__input" name="cd_expert" id="lssn_year" value="<?php echo $lssn['cd_expert'] ?>" <?php echo $required_mb_id ?>  />
                </td>
            </tr>
           
            
            </tbody>
        </table>
        <div class="sch_div">
            <input type="submit" class="btn btn_03" value="등록">
            <a href="./co_cat3.php" class="btn btn_02">돌아가기</a>
        </div>
		<div id="popup_win" class="pop-layer bPopup">  
			<div class="popup_container" id="popup_container">
				test
			</div>
		</div>
    </form>
</div>


<script>
	function cate(v){
		console.log(v.value)

		
		$.ajax({
			type: "GET",
			url: "./co_ajax.php?idx="+v.value+"&table=cd_co_cate2",
			success: function(res){
				const res_info = $.parseJSON( res );
				console.log(res_info)
				$("#cate2 option").remove();
				for(let i=0; i<res_info.length; i++) {
					$("#cate2").append(`<option value='${res_info[i].idx}'>${res_info[i].cd_name}</option>`);
				}

			$.ajax({
			type: "GET",
			url: "./co_ajax.php?idx="+res_info[0].idx+"&table=cd_co_cate3",
			success: function(res){
				const res_info = $.parseJSON( res );
				console.log(res_info)
				$("#cate3 option").remove();
				for(let i=0; i<res_info.length; i++) {
					$("#cate3").append(`<option value='${res_info[i].idx}'>${res_info[i].cd_name}</option>`);
				}
					
		$.ajax({
			type: "GET",
			url: "./co_ajax.php?idx="+res_info[0].idx+"&table=cd_co_cate4",
			success: function(res){
				const res_info = $.parseJSON( res );
				console.log(res_info)
				$("#cate4 option").remove();
				for(let i=0; i<res_info.length; i++) {
					$("#cate4").append(`<option value='${res_info[i].idx}'>${res_info[i].cd_name}</option>`);
				}
					
			},
			error: function(err){ alert("호출 실패하였습니다.") ;}
		});


			},
			error: function(err){ alert("호출 실패하였습니다.") ;}
		});


					
			},
			error: function(err){ alert("호출 실패하였습니다.") ;}
		});

	}


	function cates(v){
		console.log(v.value)

		
		$.ajax({
			type: "GET",
			url: "./co_ajax.php?idx="+v.value+"&table=cd_co_cate3",
			success: function(res){
				const res_info = $.parseJSON( res );
				console.log(res_info)
				$("#cate3 option").remove();
				for(let i=0; i<res_info.length; i++) {
					$("#cate3").append(`<option value='${res_info[i].idx}'>${res_info[i].cd_name}</option>`);
				}	
					$.ajax({
			type: "GET",
			url: "./co_ajax.php?idx="+res_info[0].idx+"&table=cd_co_cate4",
			success: function(res){
				const res_info = $.parseJSON( res );
				console.log(res_info)
				$("#cate4 option").remove();
				for(let i=0; i<res_info.length; i++) {
					$("#cate4").append(`<option value='${res_info[i].idx}'>${res_info[i].cd_name}</option>`);
				}	
			},
			error: function(err){ alert("호출 실패하였습니다.") ;}
		});
			},
			error: function(err){ alert("호출 실패하였습니다.") ;}
		});

	}

	function cates_l(v){
		console.log(v.value)

		
		$.ajax({
			type: "GET",
			url: "./co_ajax.php?idx="+v.value+"&table=cd_co_cate4",
			success: function(res){
				const res_info = $.parseJSON( res );
				console.log(res_info)
				$("#cate4 option").remove();
				for(let i=0; i<res_info.length; i++) {
					$("#cate4").append(`<option value='${res_info[i].idx}'>${res_info[i].cd_name}</option>`);
				}	
			},
			error: function(err){ alert("호출 실패하였습니다.") ;}
		});

	}

	


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

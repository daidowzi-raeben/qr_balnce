<?php
$sub_menu = "300310";
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
    <form id="flesson" name="flesson" action="./cd.campaign_form_update.php" class="local_sch01 local_sch11" method="post" onsubmit="return flesson_submit(this);">
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
			 <tr>
                <th scope="row"><label for="lssn_year">년도<?php echo $sound_only ?></label></th>
                <td colspan="3">
                    <input type="text" class="new__input" name="lssn_year" id="lssn_year" value="<?php echo $lssn['lssn_year'] ?>" <?php echo $required_mb_id ?>  />
                </td>
            </tr>
			<tr>
                <th scope="row"><label for="lssn_cond">수료조건</label></th>
                <td colspan="3">
                    <input type="text" class="new__input width_400" name="lssn_cond" id="lssn_cond" value="<?php echo $lssn['lssn_cond'] ?>" placeholder="진도 100%" />
                </td>
            </tr>
			<tr>
                <th scope="row"><label for="lssn_sdate">학습기간</label></th>
                <td colspan="3">
                    <input type="text" class="new__input width_185 datepicker" name="lssn_sdate" value="<?php echo $lssn['lssn_sdate'] ?>" <?php echo $required_mb_id ?> />
					&nbsp;~&nbsp;
					<input type="text" class="new__input width_185 datepicker" name="lssn_edate" value="<?php echo $lssn['lssn_edate'] ?>" <?php echo $required_mb_id ?> />
                </td>
            </tr>
            <tr>
                <th scope="row">마일리지</th>
                <td>
                    <input type="text" class="new__input" name="lssn_point" id="lssn_point" value="<?php echo $lssn['lssn_point'] ?>" placeholder="0" /> 점
					<br />*0일 경우 '미반영'으로 표시
                </td>
                <th scope="row">등록일</th>
                <td>
                    <input type="text" class="new__input datepicker" name="lssn_regdate" id="lssn_regdate" value="<?php echo substr($lssn['lssn_rdate'],0,10); ?>" />
                </td>
            </tr>
			<tr>
                <th scope="row"><label for="lssn_time">학습시간</label></th>
                <td colspan="3">
                    <input type="text" class="new__input width_400" name="lssn_time" id="lssn_time" value="<?php echo $lssn['lssn_time'] ?>" <?php echo $required_mb_id ?> />
                </td>
            </tr>
            <tr style="display:none;">
                <th scope="row"><label for="lssn_link">과정 연결링크</label></th>
                <td colspan="3">
                    <input type="text" class="new__input width_400" name="lssn_link" id="lssn_link" value="<?php echo $lssn['lssn_url'] ?>" />
					<a href="javascript:preview2('<?php echo $lssn['lssn_url'] ?>');" class="btn btn_01">미리보기</a>
                </td>
            </tr>
			<tr>
                <th scope="row"><label for="lssn_img_link">이미지 연결링크</label></th>
                <td colspan="3">
                    <input type="text" class="new__input width_400" name="lssn_img_link" id="lssn_img_link" value="<?php echo $lssn['lssn_rimg'] ?>" />
					<a href="javascript:view_dir();" class="btn btn_02">찾아연결</a>
					<a href="javascript:preview();" class="btn btn_01">미리보기</a>
					<br />*img_cd > lssn_img 폴더 안에 이미지가 있어야 합니다.
                </td>
            </tr>
				<tr>
                <th scope="row"><label for="lssn_div">폴더명</label></th>
                <td colspan="3">
                    <input type="text" class="new__input width_400" name="lssn_div" id="lssn_div" value="<?php echo $lssn['lssn_div'] ?>" <?php echo $required_mb_id ?> />
                </td>
            </tr>
				<tr>
                <th scope="row"><label for="lssn_total">영상갯수</label></th>
                <td colspan="3">
                    <input type="text" class="new__input width_400" name="lssn_total" id="lssn_total" value="<?php echo $lssn['lssn_total'] ?>" <?php echo $required_mb_id ?> />
                </td>
            </tr>
			<tr>
                <th scope="row"><label for="lssn_view">상태</label></th>
                <td colspan="3">
                    <label for="ck6">
                        <input type="radio" name="lssn_view" id="ck6" value="Y" <?php if($lssn['lssn_view'] != 'N') { echo 'checked';} ?> /> 사용
                    </label>
					&nbsp;&nbsp;&nbsp;
                    <label for="ck5">
                        <input type="radio" name="lssn_view" id="ck5" value="N" <?php if($lssn['lssn_view'] == 'N') { echo 'checked';} ?>/> 사용안함
                    </label>
                </td>
            </tr>
			<tr style="display:none">
			<th scope="row"><label for="lssn_view">수행결과목록</label></th>
			<td colspan="3">
				<select class="" name="lssn_class">
				<option value="">선택</option>
						<?php 
			$sql_list = "SELECT * from cd_lms_adm_list ORDER BY idx asc";
			$result_list = sql_query($sql_list);
			for ($i=0; $row=sql_fetch_array($result_list); $i++) {
				?>
				<option value="<?php echo $row['idx']?>" <?php if($lssn['lssn_class'] == $row['idx']) { echo ' selected ';} ?>><?php echo $row['mb_content']?></option>
			<?php }?>
				</select>
			</td>
			</tr>

			<tr>
			<th scope="row"><label for="lssn_view">회사명</label></th>
			<td colspan="3">
				<select class="" name="lssn_company">
				<option value="">선택</option>
						<?php 
			$sql_list = "SELECT mb_profile from cd_member WHERE mb_profile != '' GROUP BY mb_profile ORDER BY mb_profile asc";
			$result_list = sql_query($sql_list);
			for ($i=0; $row=sql_fetch_array($result_list); $i++) {
				?>
				<option value="<?php echo $row['mb_profile']?>" <?php if($lssn['lssn_company'] == $row['mb_profile']) { echo ' selected ';} ?>><?php echo $row['mb_profile']?></option>
			<?php }?>
				</select>
			</td>
			</tr>

			<tr style="display:none;">
                <th scope="row"><label for="lssn_status">교육형태</label></th>
                <td colspan="3">
				<select name="lssn_status">
				<option value="A" <?php if($lssn['lssn_status'] == 'A') { echo ' selected '; } ?>>A타입</option>
				<option value="B" <?php if($lssn['lssn_status'] == 'B') { echo ' selected '; } ?>>B타입</option>
				<option value="C" <?php if($lssn['lssn_status'] == 'C') { echo ' selected '; } ?>>C타입</option>
				<option value="D" <?php if($lssn['lssn_status'] == 'D') { echo ' selected '; } ?>>D타입</option>

				<option value="E" <?php if($lssn['lssn_status'] == 'E') { echo ' selected '; } ?>>E타입</option>
</select>
                    
                </td>
            </tr>



	


			</tr>
            
            </tbody>
        </table>
        <div class="sch_div">
            <input type="submit" class="btn btn_03" value="과정등록">
            <a href="./cd.campaign_list.php" class="btn btn_02">돌아가기</a>
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

<?php
$sub_menu = "300110";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$sql_common = " from {$g5['chapter_table']} ";

$sql_search = " where (1) and cpt_lesson = '1' ";
if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        case 'mb_point' :
            $sql_search .= " ({$sfl} >= '{$stx}') ";
            break;
        case 'mb_level' :
            $sql_search .= " ({$sfl} = '{$stx}') ";
            break;
        case 'mb_tel' :
        case 'mb_hp' :
            $sql_search .= " ({$sfl} like '%{$stx}') ";
            break;
        default :
            $sql_search .= " ({$sfl} like '{$stx}%') ";
            break;
    }
    $sql_search .= " ) ";
}

if ($is_admin != 'super')
    $sql_search .= " and mb_level <= '{$member['mb_level']}' ";

if (!$sst) {
    $sst = "cpt_no";
    $sod = "asc";
}

$sql_order = " order by {$sst} {$sod} ";

$sql = " select count(*) as cnt {$sql_common} {$sql_search} {$sql_order} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$startNum = 1 + (($page-1) * $rows);


$g5['title'] = '과정목록';
include_once('./cd.admin.head.php');

$sql = " select * {$sql_common} {$sql_search} {$sql_order} limit {$from_record}, {$rows} ";
$result = sql_query($sql);

?>

<div class="tbl_frm02 tbl_wrap">
    <!--<p>
        회원자료 삭제 시 다른 회원이 기존 회원아이디를 사용하지 못하도록 회원아이디, 이름, 닉네임은 삭제하지 않고 영구 보관합니다.
    </p>-->


    <form id="fsearch" name="fsearch" class="local_sch01 local_sch11" method="get">
        <table>
            <colgroup>
                <col class="grid_4">
                <col>
                <col class="grid_4">
                <col>
            </colgroup>
            <tbody>
            <tr>
                <th scope="row"><label for="mb_year">과정명</label></th>
                <td colspan="3">
                    아름다운 동행을 위한 윤리경영 및 청탁금지법 이해와 실천
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="mb_year">노출</label></th>
                <td >
                    노출
                </td>
                <th scope="row"><label for="mb_year">컨트롤바활성</label></th>
                <td >
                    비활성
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="mb_stat">시험</label></th>
                <td >
                    있음
                </td>
                <th scope="row"><label for="mb_stat">설문</label></th>
                <td >
                    없음
                </td>
            </tr>
            </tbody>
        </table>
        <div class="sch_div d-flex justify-content-center mt-2">
            <a href="cd.process_form.php?lno=1" class="btn btn_02">수정</a>
            <input type="button" class="btn btn_02 m-rl-5" value="삭제">
            <a href="cd.process_list.php" class="btn btn_02">목록</a>
        </div>
    </form>
</div>



    <div class="local_ov02">
        <div class="l_div">
            <?php if ($is_admin == 'super') { ?>
                <!--                <a href="./process_ce_form.php" id="member_add" class="btn btn_03">등록</a>-->
                <a href="" id="addCs" class="btn btn_03">차시관리</a>

            <?php } ?>
            <span class="btn_ov01"><span class="ov_txt">Total </span><span class="ov_num"> <?php echo number_format($total_count) ?> 건 </span></span>
        </div>
        <div class="r_div">

        </div>
    </div>

    <div class="tbl_head01 tbl_wrap">
        <table>
            <colgroup>
                <col width="100">
                <col>
                <col>
                <col width="130">
            </colgroup>
            <thead>
            <tr>
                <th scope="col" id="cp_list_no">차시번호</a></th>
                <th scope="col" id="cp_list_id">차시분류</th>
                <th scope="col" id="cp_list_id">제목</th>
                <th scope="col" id="cp_list_id">삭제</th>
            </tr>
            </thead>
            <tbody>
            <?php
            for ($i=1; $row=sql_fetch_array($result); $i++) {
			?>
                <tr class="<?php echo $bg; ?>">
                    <td headers="cp_list_"><?php echo $i?></td>
                    <td headers="cp_list_">contents</td>
                    <td headers="cp_list_" style="text-align:left"><a href=""><?php echo get_chapterName($row['cpt_contents'])?></a></td>
                    <td headers="cp_list_">
                        <form action=""><button type="submit" class="btn btn_03">삭제</button></form>
                    </td>
                </tr>
                <?php
            } ?>
            </tbody>
        </table>
    </div>

</form>

<div class="searchPop" id="searchPop" style="display: none;">
    <div class="searchPop__content">
        <div class="searchPop__row searchPop__row--title">
            <h3 class="searchPop__row-title">과정명</h3>
            <p>
                아름다운 동행을 위한 윤리경영 및 청탁금지법 이해와 실천
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
                    <th>
                        <input type="checkbox" name="select-all" id="select-all" />
                    </th>
                    <th>번호</th>
                    <th>노출</th>
                    <th>분류</th>
                    <th>타입</th>
                    <th>컨텐츠 정보명</th>
                    <th>등록일</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>
                        <input type="checkbox" class="checkbox">
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                </tbody>
            </table>
        </div>
        <div class="sch_div d-flex justify-content-center mt-2">
            <a href="#//" class="btn btn_03" style="margin-right:5px;">선택추가</a>
            <a href="#//" class="btn btn_01">닫기</a>
        </div>
    </div>
</div>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, '?'.$qstr.'&amp;page='); ?>

<script>
	$('.searchPop .sch_div .btn').on('click', function(){
        $(this).parents('.searchPop').hide();
    })
	$('#addCs').on('click', function(e){
        e.preventDefault();
        $('#searchPop').show();
    });

    function fmemberlist_submit(f)
    {
        if (!is_checked("chk[]")) {
            alert(document.pressed+" 하실 항목을 하나 이상 선택하세요.");
            return false;
        }

        if(document.pressed == "선택삭제") {
            if(!confirm("선택한 자료를 정말 삭제하시겠습니까?")) {
                return false;
            }
        }

        return true;
    }


    function view_priview(v_dir) {

        var size_w = 1280;
        var size_h = 720;

        if( v_dir.length > 0 ) {
            window.open("<?php echo G5_URL ?>/process/ethics/"+v_dir+"/01/01.htm","content_preview","width="+size_w+"px,height="+size_h+"px");
        } else {
            alert("URL 입력후 미리보기가 가능합니다.")
        }
    }
</script>

<?php
include_once ('./cd.admin.tail.php');
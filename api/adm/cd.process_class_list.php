<?php
$sub_menu = "300110";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$sql_common = " from  cd_lms_chapter a
INNER JOIN cd_lms_contents b ON a.cpt_contents = b.c_no ";

$sql_search = " where (1) and  cpt_lesson = {$idx} ";
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
    $sst = "cpt_seq";
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
# echo $sql;
$result = sql_query($sql);

?>

<div class="tbl_frm02 tbl_wrap">
    <!--<p>
        회원자료 삭제 시 다른 회원이 기존 회원아이디를 사용하지 못하도록 회원아이디, 이름, 닉네임은 삭제하지 않고 영구 보관합니다.
    </p>-->


    <form id="fsearch" name="fsearch" class="local_sch01 local_sch11" method="get">
        <table>
            <caption><?php echo $g5['title']; ?> 검색</caption>
            <colgroup>
                <col class="grid_4">
                <col>
            </colgroup>
            <tbody>
            <tr>
                <th scope="row"><label for="mb_stat">교육명</label></th>
                <td >
                    <input type="text" class="new__input">
                    <select>
                        <option value="">10개씩 보기</option>
                        <option value="">50개씩 보기</option>
                        <option value="">100개씩 보기</option>
                    </select>
                </td>
            </tr>
            </tbody>
        </table>
        <div class="sch_div">
            <input type="submit" class="btn btn_02" value="검색">
        </div>
    </form>
</div>



    <div class="local_ov02">
        <div class="l_div">
            <span class="btn_ov01"><span class="ov_txt">Total </span><span class="ov_num"> <?php echo number_format($total_count) ?> 건 </span></span>
        </div>
        <div class="r_div">
            <?php if ($is_admin == 'super') { ?>
                <a href="./cd.process_class_form.php?idx=<?php echo $idx?>" id="member_add" class="btn btn_03">신규등록</a>
            <?php } ?>
        </div>
    </div>

    <div class="tbl_head01 tbl_wrap">
        <table>
            <caption><?php echo $g5['title']; ?> 목록</caption>
            <thead>
            <tr>
                <th scope="col" id="cp_list_no">순서</a></th>
                <th scope="col" id="cp_list_id">차시명</th>
                <th scope="col" id="cp_list_id">차시내 동영상</th>
                <th scope="col" id="cp_list_id">정렬</th>
                <th scope="col" id="cp_list_mng">URL</th>
                <th scope="col" id="cp_list_mng">수정</th>
            </tr>
            </thead>
            <tbody>
            <?php 
			for ($i=0; $row=sql_fetch_array($result); $i++) {
			?>
            <tr>
                <td class="cp_list_"><?php echo $startNum?></td>
                <td class="cp_list_ td_left">
					<?php echo $row['c_name']?>
                </td>
                <td class="cp_list_"><?php echo $row['c_page']?></td>
                <td class="cp_list_"><?php echo $row['cpt_seq'] ?></td>
                <td class="cp_list_"><?php echo $row['c_url']?></td>
                <td class="cp_list_"><a href="./cd.process_class_form.php?w=u&idx=<?php echo $idx?>&lno=<?php echo $row['cpt_no']?>&cno=<?php echo $row['c_no']?>" id="lssn_mod" class="btn btn_03">수정</a></td>
            </tr>
            <?php 
				$startNum++;
			} ?>
            </tbody>
        </table>
    </div>

</form>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, '?'.$qstr.'&amp;page='); ?>

<script>
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
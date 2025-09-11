<?php
$sub_menu = "200120";
require_once './_common.php';

auth_check_menu($auth, $sub_menu, 'r');

$sql_common = " from qr_member_student_qa as a ";

$sql_search = " where (1) ";
if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        case 'mb_point':
            $sql_search .= " ({$sfl} >= '{$stx}') ";
            break;
        case 'mb_level':
            $sql_search .= " ({$sfl} = '{$stx}') ";
            break;
        case 'mb_tel':
        case 'mb_hp':
            $sql_search .= " ({$sfl} like '%{$stx}') ";
            break;
        default:
            $sql_search .= " ({$sfl} like '{$stx}%') ";
            break;
    }
    $sql_search .= " ) ";
}

if ($is_admin != 'super') {
    $sql_search .= " and mb_level <= '{$member['mb_level']}' ";
}

if (!$sst) {
    $sst = "id";
    $sod = "desc";
}

$sql_order = " order by {$sst} {$sod} ";

$sql = " select count(*) as cnt {$sql_common} {$sql_search} {$sql_order} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) {
    $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
}
$from_record = ($page - 1) * $rows; // 시작 열을 구함

// 탈퇴회원수
$sql = " select count(*) as cnt {$sql_common} {$sql_search} and mb_leave_date <> '' {$sql_order} ";
$row = sql_fetch($sql);
$leave_count = $row['cnt'];

// 차단회원수
$sql = " select count(*) as cnt {$sql_common} {$sql_search} and mb_intercept_date <> '' {$sql_order} ";
$row = sql_fetch($sql);
$intercept_count = $row['cnt'];

$listall = '<a href="' . $_SERVER['SCRIPT_NAME'] . '" class="ov_listall">전체목록</a>';

$g5['title'] = '학습관리';
require_once './admin.head.php';

$sql = " select *
,(select mb_nick from qr_member_student where idx = a.mb_id) as mb_nick
{$sql_common} {$sql_search} {$sql_order} limit {$from_record}, {$rows} ";
// echo $sql;
$result = sql_query($sql);

$colspan = 16;




$list_bbs = sql_query("select * from qr_board");

?>

<div class="local_ov01 local_ov">
    <!-- <?php echo $listall ?>
    <span class="btn_ov01"><span class="ov_txt">총회원수 </span><span class="ov_num">
            <?php echo number_format($total_count) ?>명 </span></span>
    <a href="?sst=mb_intercept_date&amp;sod=desc&amp;sfl=<?php echo $sfl ?>&amp;stx=<?php echo $stx ?>" class="btn_ov01"
        data-tooltip-text="차단된 순으로 정렬합니다.&#xa;전체 데이터를 출력합니다."> <span class="ov_txt">차단 </span><span
            class="ov_num"><?php echo number_format($intercept_count) ?>명</span></a>
    <a href="?sst=mb_leave_date&amp;sod=desc&amp;sfl=<?php echo $sfl ?>&amp;stx=<?php echo $stx ?>" class="btn_ov01"
        data-tooltip-text="탈퇴된 순으로 정렬합니다.&#xa;전체 데이터를 출력합니다."> <span class="ov_txt">탈퇴 </span><span
            class="ov_num"><?php echo number_format($leave_count) ?>명</span></a> -->
</div>

<form id="fsearch" name="fsearch" class="local_sch01 local_sch" method="get"
    onsubmit="return fmemberlist_submit2(this);">

    <label for="sfl" class="sound_only">검색대상</label>
    <input type="hidden" name="sfl" value="bo_table">
    <select name="stx" id="stx" onchange="fmemberlist_submit2()">
        <option value="">선택</option>
        <?php for ($i = 0; $row = sql_fetch_array($list_bbs); $i++) {?>
        <option value="<?php echo $row['bo_table']?>"><?php echo $row['bo_subject']?></option>
        <?php }?>
    </select>

</form>

<div class="local_desc01 local_desc">
    <p>
        회원자료 삭제 시 다른 회원이 기존 회원아이디를 사용하지 못하도록 회원아이디, 이름, 닉네임은 삭제하지 않고 영구 보관합니다.
    </p>
</div>


<form name="fmemberlist" id="fmemberlist" action="./member_list_update.php" onsubmit="return fmemberlist_submit(this);"
    method="post">
    <input type="hidden" name="sst" value="<?php echo $sst ?>">
    <input type="hidden" name="sod" value="<?php echo $sod ?>">
    <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
    <input type="hidden" name="stx" value="<?php echo $stx ?>">
    <input type="hidden" name="page" value="<?php echo $page ?>">
    <input type="hidden" name="token" value="">

    <div class="tbl_head01 tbl_wrap">
        <table>
            <caption><?php echo $g5['title']; ?> 목록</caption>
            <thead>
                <tr>
                    <!-- <th scope="col" id="mb_list_chk" rowspan="2">
                        <label for="chkall" class="sound_only">회원 전체</label>
                        <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
                    </th> -->
                    <th scope="col" id="mb_list_name">사번</a></th>
                    <th scope="col" id="mb_list_name">닉네임</a></th>
                    <th scope="col" id="mb_list_nick">질의</a></th>
                    <th scope="col" id="mb_list_deny"> 답변</a>
                    </th>
                    <th scope="col" id="mb_list_tel">응답일</th>
                </tr>
            </thead>
            <tbody>
                <?php
                for ($i = 0; $row = sql_fetch_array($result); $i++) {

                    $qq = sql_fetch("select wr_subject from qr_write_{$row['bo_table']} limit {$row['num']},1");
                    // 접근가능한 그룹수
                    $sql2 = " select count(*) as cnt from {$g5['group_member_table']} where mb_id = '{$row['mb_id']}' ";
                    $row2 = sql_fetch($sql2);
                    $group = '';
                    if ($row2['cnt']) {
                        $group = '<a href="./boardgroupmember_form.php?mb_id=' . $row['mb_id'] . '">' . $row2['cnt'] . '</a>';
                    }

                    if ($is_admin == 'group') {
                        $s_mod = '';
                    } else {
                        $s_mod = '<a href="./member_student_form.php?' . $qstr . '&amp;w=u&amp;idx=' . $row['idx'] . '" class="btn btn_03">수정</a>';
                    }
                    $s_grp = '<a href="./boardgroupmember_form.php?mb_id=' . $row['mb_id'] . '" class="btn btn_02">그룹</a>';

                    $leave_date = $row['mb_leave_date'] ? $row['mb_leave_date'] : date('Ymd', G5_SERVER_TIME);
                    $intercept_date = $row['mb_intercept_date'] ? $row['mb_intercept_date'] : date('Ymd', G5_SERVER_TIME);

                    $mb_nick = get_sideview($row['mb_id'], get_text($row['mb_nick']), $row['mb_email'], $row['mb_homepage']);

                    $mb_id = $row['mb_id'];
                    $leave_msg = '';
                    $intercept_msg = '';
                    $intercept_title = '';
                    if ($row['mb_leave_date']) {
                        $mb_id = $mb_id;
                        $leave_msg = '<span class="mb_leave_msg">탈퇴함</span>';
                    } elseif ($row['mb_intercept_date']) {
                        $mb_id = $mb_id;
                        $intercept_msg = '<span class="mb_intercept_msg">차단됨</span>';
                        $intercept_title = '차단해제';
                    }
                    if ($intercept_title == '') {
                        $intercept_title = '차단하기';
                    }

                    $address = $row['mb_zip1'] ? print_address($row['mb_addr1'], $row['mb_addr2'], $row['mb_addr3'], $row['mb_addr_jibeon']) : '';

                    $bg = 'bg' . ($i % 2);

                    switch ($row['mb_certify']) {
                        case 'hp':
                            $mb_certify_case = '휴대폰';
                            $mb_certify_val = 'hp';
                            break;
                        case 'ipin':
                            $mb_certify_case = '아이핀';
                            $mb_certify_val = '';
                            break;
                        case 'simple':
                            $mb_certify_case = '간편인증';
                            $mb_certify_val = '';
                            break;
                        case 'admin':
                            $mb_certify_case = '관리자';
                            $mb_certify_val = 'admin';
                            break;
                        default:
                            $mb_certify_case = '&nbsp;';
                            $mb_certify_val = 'admin';
                            break;
                    }
                ?>

                <tr class="<?php echo $bg; ?>">
                    <!-- <td headers="mb_list_chk" class="td_chk">
                        <input type="hidden" name="mb_id[<?php echo $i ?>]" value="<?php echo $row['mb_id'] ?>"
                            id="mb_id_<?php echo $i ?>">
                        <label for="chk_<?php echo $i; ?>" class="sound_only"><?php echo get_text($row['mb_name']); ?>
                            <?php echo get_text($row['mb_nick']); ?>님</label>
                        <input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>">
                    </td> -->


                    <td headers="mb_list_nick" class="td_name sv_use">
                        <div><?php echo $row['mb_id'] ?></div>
                    </td>
                    <td headers="mb_list_nick" class="td_name sv_use">
                        <div><?php echo $row['mb_nick'] ?></div>
                    </td>
                    <td headers="mb_list_nick" class="td_name sv_use" style="word-break: break-all;">
                        <div><?php echo $qq['wr_subject']?></div>
                    </td>
                    <td headers="mb_list_nick" class="td_name sv_use" style="word-break: break-all;">
                        <div><?php echo $row['content'] ?></div>
                    </td>
                    <td headers="mb_list_join" class="td_date"><?php echo substr($row['c_datetime'], 2, 8); ?></td>

                </tr>

                <?php
                }
                if ($i == 0) {
                    echo "<tr><td colspan=\"" . $colspan . "\" class=\"empty_table\">자료가 없습니다.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <div class="btn_fixed_top">
        <!-- <input type="submit" name="act_button" value="선택수정" onclick="document.pressed=this.value" class="btn btn_02"> -->
        <!-- <input type="submit" name="act_button" value="선택삭제" onclick="document.pressed=this.value" class="btn btn_02"> -->
        <?php if ($is_admin == 'super') { ?>
        <a href="./member_student_excel_down.php?bo_like=<?php echo $stx?>" id="member_add" class="btn btn_01">엑셀다운</a>
        <?php } ?>

    </div>


</form>



<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, '?' . $qstr . '&amp;page='); ?>

<script>
function fmemberlist_submit2(f) {
    document.getElementById('fsearch').submit()
    return true;
}

function fmemberlist_submit(f) {
    if (!is_checked("chk[]")) {
        alert(document.pressed + " 하실 항목을 하나 이상 선택하세요.");
        return false;
    }

    if (document.pressed == "선택삭제") {
        if (!confirm("선택한 자료를 정말 삭제하시겠습니까?")) {
            return false;
        }
    }

    return true;
}
</script>

<?php
require_once './admin.tail.php';
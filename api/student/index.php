<?php
include_once('./_common.php');
include_once('./head.php');
$sql_start = "
select * from qr_write_{$bo_table} where wr_4 = 'F' order by wr_id desc limit 1
";
$result_start = $sql_start;
$row_start = sql_fetch($result_start);

$sql_file_0 = "select * from qr_board_file where wr_id = '{$row_start['wr_id']}' order by bf_no asc limit 1,1 ";
$row_file_0 = sql_fetch($sql_file_0);
$img_0 = G5_DATA_URL . "/file/" . $bo_table . "/". $row_file_0['bf_file'];

?>
<script>
const onclickSubmit = () => {
    const mb_id = document.getElementsByName('mb_id')[0].value
    const mb_nick = document.getElementsByName('mb_nick')[0].value
    if (!mb_id) {
        return alert('사번을 입력하세요')
    }
    if (!mb_nick) {
        return alert('닉네임을 입력하세요')
    }

    document.getElementById('form').submit()
}
</script>
<?php if(!$start) { ?>
<div>
    <a href="/student/?bo_table=<?php echo $bo_table?>&start=1">
        <img src="<?php echo $img_0; ?>" width="100%">
    </a>
</div>
<?php } else {?>
<div class="">
    <div class="title">
        사번 및 닉네임 입력
    </div>
    <div class="content">
        <form id="form" action="update.php" method="post">
            <input type="hidden" name="bo_table" value="<?php echo $bo_table?>">
            <div class="mt-20">
                사번과 닉네임을 입력해 주세요.
            </div>

            <div class="mt-40">
                1. 사번입력
            </div>
            <div class="mt-20">
                <input type="text" class="input" name="mb_id">
            </div>

            <div class="mt-40">
                2. 닉네임 입력
            </div>
            <div class="mt-20">
                <input type="text" class="input" name="mb_nick">
            </div>

            <div class="mt-40">
                <button type="button" class="btn" onclick="onclickSubmit()">확인</button>
            </div>
        </form>
    </div>
</div>
<?php } ?>
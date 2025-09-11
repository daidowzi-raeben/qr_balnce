<?php
include_once('./_common.php');
include_once('./head.php');
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
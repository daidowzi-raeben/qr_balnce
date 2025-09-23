<?php
include_once('./_common.php');
include_once('./head.php');
?>
<script>
const onclickSubmit = (v) => {
    location.href =
        '/student/start.php?bo_table=<?php echo $bo_table?>&id=<?php echo $id?>&num=<?php echo $num?>&chk=' + v;
}
const onclickStart = () => {
    location.href = '/student/start.php?bo_table=<?php echo $bo_table?>&id=<?php echo $id?>&num=0';
}
const onclickStart2 = () => {
    const el = document.getElementById("wait");
    if (!el) return;

    // CSS transition 적용
    el.style.transition = "opacity 1s ease"; // 1초 동안 서서히
    el.style.opacity = "0";

    // 완전히 사라진 뒤 display:none 처리 (선택사항)
    setTimeout(() => {
        el.style.display = "none";
    }, 1000); // transition 시간과 맞추기
};

const onclickSubmit2 = () => {
    const mb_id = document.getElementsByName('content')[0].value
    if (!mb_id) {
        return alert('이유를 입력해 주세요.')
    }

    document.getElementById('form').submit()
}
</script>

<?php if(!$row) { ?>
<div style="text-align:center;background:#EAF2FF;">
    <img src="./image/08.png" height="100%">
</div>
<?php
exit;
}?>

<?php
if(!isset($_GET['num'])) {?>
<div class="">
    <div class="title">
        밸런스 선택
    </div>
    <div class="content" style="background:#EAF2FF; height:100%;">
        <div class="before_box" onclick="onclickStart()" style="background:#EAF2FF;">
            <img src="./image/wait.png" width="80%">
            <div style="margin-top:50px;">
                <div style="background:#2A47FF;text-align:center; width:80%; padding:15px; font-size:20px;
                color:#fff; margin:0 auto; border-radius:100px;
                ">들어가기</div>
            </div>
        </div>
    </div>
</div>
<?php } else {?>
<?php if($num > 0 && !$chk) {?>
<div class="" id="wait" style="position:fixed;width:100%;height:100%;top:0;left:0;z-index:99;">
    <div class="title">
        밸런스 선택
    </div>
    <div class="content">
        <div class="before_box" style="background:#EDF4FF;height:100vh" onclick="onclickStart2()">
            <img src="./image/wait.png" width="80%">
            <div class="before_title">
                밸런스 강의 <br>이어서
            </div>
        </div>
    </div>
</div>
<?php } ?>
<div class="">
    <div class="title">
        밸런스 선택 <?php
        if($row_file_1['bf_content'] ) { 
            $p = $num + 1;
        echo 'STEP '.  $p;
        }?>
    </div>
    <div class="content">
        <div>
            나의 닉네임 : <?php echo $row_member['mb_nick'] ?>
        </div>

        <?php if($chk) { ?>
        <form id="form" action="./update.php?mode=insert" method="post">
            <input type="hidden" name="id" value="<?php echo $id?>">
            <input type="hidden" name="num" value="<?php echo $num?>">
            <input type="hidden" name="chk" value="<?php echo $chk?>">
            <input type="hidden" name="bo_table" value="<?php echo $bo_table?>">
            <?php if($row['wr_1'] != 'D') {?>
            <div class="mt-40">
                "<?php echo $chk == '1' ?  $row_file_1['bf_content'] :  $row_file_2['bf_content']; ?>"
                <br>선택한 이유를 적어주세요.
            </div>
            <?php } else {?>
            <div class="" id="wait" style="position:fixed;width:100%;height:100%;top:0;left:0;z-index:99;">
                <div class="title">
                    밸런스 선택
                </div>
                <div class="content">
                    <div class="before_box" style="background:#EDF4FF;height:100vh" onclick="onclickStart2()">
                        <img src="./image/wait.png" width="80%">
                        <div class="before_title">
                            밸런스 강의 <br>이어서
                        </div>
                    </div>
                </div>
            </div>
            <?php }?>
            <div class="mt-20">
                <textarea name="content" placeholder="200자 이내"
                    style="width:100%;height:300px;border: 1px solid #eee;"></textarea>
            </div>
            <div class="mt-40">
                <button type="button" class="btn" onclick="onclickSubmit2()">확인</button>
            </div>
        </form>
        <?php } else {?>
        <?php if($row['wr_1'] == 'D') {?>
        <script>
        location.href =
            '/student/start.php?bo_table=<?php echo $bo_table?>&id=<?php echo $id?>&num=<?php echo $num?>&chk=1';
        </script>
        <?php }?>

        <?php if($row_file_1['bf_content'] ) { ?>
        <div class="mt-40">
            아래 버튼을 선택해주세요.
        </div>
        <div class="btn-qr" onclick="onclickSubmit(1)">
            <?php echo $row_file_1['bf_content']; ?>
        </div>
        <div class="btn-qr" onclick="onclickSubmit(2)">
            <?php echo $row_file_2['bf_content']; ?>
        </div>
        <?php } else {?>
        <div class="mt-40">
            모든 응답이 끝났습니다.
        </div>
        <?php }?>


        <?php }?>



    </div>
</div>
<?php } ?>
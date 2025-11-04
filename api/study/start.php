<?php
include_once('./_common.php');
include_once('./head.php');

sql_fetch("update qr_board set bo_1 = '{$num}' where bo_table = '{$bo_table}'");

$che = "
select * from qr_board where bo_table = '{$bo_table}' limit 1
";
$chkNum = sql_fetch($che);
// echo $chkNum['bo_1'];

?>


<?php if(!$row) { 
$sql_start = "
select * from qr_write_{$bo_table} where wr_4 = 'F' order by wr_id desc limit 1
";
$result_start = $sql_start;
$row_start = sql_fetch($result_start);
$sql_file_0 = "select * from qr_board_file where wr_id = '{$row_start['wr_id']}' order by bf_no asc limit 3,1 ";
$row_file_0 = sql_fetch($sql_file_0);
$img_0 = G5_DATA_URL . "/file/" . $bo_table . "/". $row_file_0['bf_file'];

?>
<img src="<?php echo $img_0;?>" width="1920">
<?php
sql_fetch("update qr_board set bo_1 = '' where bo_table = '{$bo_table}'");
exit;
}?>

<?php if($start > 4) { ?>
<?php if(!$row_img['bf_file']) {?>
<script>
window.location.href =
    "/study/start.php?bo_table=<?php echo $bo_table ?>&num=<?php echo $num + 1 ?>&start=0"; // 다음 페이지 URL
</script>
<?php }?>
<img src="<?php echo '/data/file/'.$bo_table.'/'.$row_img['bf_file']?>" width="100%">

<?php }?>

<?php if($start == 2) { ?>


<!-- 
<div class="bottom">
    <span class="btn"
        onclick="location.href='/study/start.php?bo_table=<?php echo $bo_table ?>&num=<?php echo $num + 1 ?>&start=0';">다음으로</span>
    <span class="btn" data-modal-open="pdfModal" data-pdf="<?php echo $pdf_1?>">해설보기</span>
</div>
 -->


<div class="content">
    <div class="con-title">
        <?php echo $row['wr_subject']?>
    </div>

    <div class="flex" style="margin-top:30px;">

        <div class="vs" <?php if ($row['wr_1'] == 'D') { ?> style="display:none;" <?php }?>>VS</div>


        <div class="div mr">
            <div class="description">
                <?php if ($row['wr_1'] == 'D') { ?> 의견 주신 분<?php } else {?>
                <?php echo $row_file_1['bf_content']; ?>
                <?php }?>
            </div>
            <div class="box ox" <?php if ($row['wr_1'] == 'D') { ?> style="width: 50%;margin: 20px auto;" <?php }?>
                data-target="<?php echo $chk1['cnt']?>">0</div> <!-- ← 목표 숫자 -->
        </div>

        <div class="div ml" <?php if ($row['wr_1'] == 'D') { ?> style="display:none;" <?php }?>>
            <div class="description">
                <?php echo $row_file_2['bf_content']; ?>
            </div>
            <div class="box ox" data-target="<?php echo $chk2['cnt']?>">0</div> <!-- ← 목표 숫자 -->
        </div>
    </div>
</div>
</div>
<div class="bottom">
    <span class="btn"
        onclick="location.href='/study/start.php?bo_table=<?php echo $bo_table ?>&num=<?php echo $num + 1 ?>&start=0';">다음으로</span>
    <span class="btn" data-modal-open="pdfModal" data-pdf="<?php echo $pdf_1?>">해설보기</span>
</div>

<script>
(() => {
    const easeOutCubic = t => 1 - Math.pow(1 - t, 3);

    // 전역 배속 (1=보통, 2=2배 빠름, 0.5=2배 느림)
    let SPEED = 1;

    // 박스별 속도 설정 (ms) ― 필요 없으면 null
    const durations = {
        left: 3000,
        right: 3000
    };

    function animateCount(el, key, defaultDuration = 1600) {
        return new Promise((resolve) => {
            const target = parseInt(el.dataset.target, 10) || 0;
            const duration = (durations[key] || defaultDuration) / SPEED;

            const startTime = performance.now();

            function frame(now) {
                const p = Math.min(1, (now - startTime) / duration);
                const eased = easeOutCubic(p);
                const value = Math.round(target * eased);

                el.textContent = value.toLocaleString();
                el.classList.add('pop');
                setTimeout(() => el.classList.remove('pop'), 70);

                if (p < 1) requestAnimationFrame(frame);
                else {
                    el.textContent = target.toLocaleString();
                    resolve();
                }
            }
            requestAnimationFrame(frame);
        });
    }

    const left = document.querySelector('.div.mr .box.ox');
    const right = document.querySelector('.div.ml .box.ox');
    const vsEl = document.querySelector('.vs');

    async function runVersus() {
        [left, right].forEach(el => {
            el.classList.remove('winner', 'loser', 'finish');
            el.textContent = '0';
        });

        vsEl.classList.add('pulse');

        await Promise.all([
            animateCount(left, 'left'),
            animateCount(right, 'right')
        ]);

        vsEl.classList.remove('pulse');

        const l = parseInt(left.dataset.target, 10) || 0;
        const r = parseInt(right.dataset.target, 10) || 0;

        if (l > r) {
            left.classList.add('winner');
            right.classList.add('loser');
        } else if (r > l) {
            right.classList.add('winner');
            left.classList.add('loser');
        } else {
            vsEl.classList.add('draw');
            setTimeout(() => vsEl.classList.remove('draw'), 1200);
        }
    }

    const io = new IntersectionObserver((en) => {
        en.forEach(e => {
            if (e.isIntersecting) {
                runVersus();
                io.disconnect();
            }
        });
    }, {
        threshold: .5
    });
    io.observe(left.closest('.flex'));

    [left, right, vsEl].forEach(el => el.addEventListener('click', runVersus));

    // 🔹 외부에서 JS로 속도 제어 가능
    window.VSControl = {
        setSpeed: (s) => SPEED = s,
        setDuration: (side, d) => durations[side] = d,
        restart: runVersus
    };
})();
</script>
<?php }?>


<?php if($start == 3) { ?>


<div class="content">
    <div class="table-wrapper">
        <table class="custom-table" id="myTable">
            <thead>
                <tr>
                    <th style="min-width:100px;">닉네임</th>
                    <th>
                        <?php echo $row_a['wr_7']; ?>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php for ($i=0; $row2=sql_fetch_array($sql2); $i++) { ?>
                <tr>
                    <td>
                        <?php echo $row2['mb_nick']?>
                    </td>
                    <td>
                        <?php echo $row2['content']?>
                    </td>
                </tr>
                <!-- 나머지 행들 -->

                <?php }?>
            </tbody>
        </table>
    </div>
</div>
<div class="bottom">
    <span class="btn"
        onclick="location.href='/study/start.php?bo_table=<?php echo $bo_table ?>&num=<?php echo $num + 1 ?>&start=0';">다음으로</span>
    <span class="btn" data-modal-open="pdfModal" data-pdf="<?php echo $pdf_1?>">해설보기</span>
</div>







<div class="modal" id="leftType" role="dialog" aria-modal="true" aria-hidden="true" aria-labelledby="leftType">
    <div class="modal__panel" tabindex="-1">
        <div class="modal__header">
            <div class="modal__title" id="pdfTitle">
                <span id="nickName"></span>
            </div>
            <button class="modal__close" aria-label="닫기">&times;</button>
            <!-- <div class="modal__actions">
                <a class="btn" id="downloadBtn" href="#" download>다운로드</a>
                <button class="btn" data-modal-close>닫기</button>
            </div> -->
        </div>
        <div class="modal__body loading" id="pdfContainer">
            <div class="content">
                <div class="table-wrapper">
                    <table class="custom-table" id="myTable">
                        <thead>
                            <tr>
                                <th style="min-width:100px;">닉네임</th>
                                <th> <?php echo $row_a['wr_7']; ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="height:500px;">
                                    <span id="nickName2"></span>
                                </td>
                                <td style="word-break: break-all;">
                                    <span id="thought"></span>
                                </td>
                            </tr>
                            <!-- 나머지 행들 -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>



<?php }?>




<?php if($start == 4) { ?>




<div class="content">
    <div class="table-wrapper">
        <table class="custom-table" id="myTable">
            <thead>
                <tr>
                    <th style="min-width:100px;">닉네임</th>
                    <th> <?php echo $row_a['wr_7']; ?></th>
                </tr>
            </thead>
            <tbody>
                <?php for ($i=0; $row3=sql_fetch_array($sql3); $i++) { ?>
                <tr>
                    <td>
                        <?php echo $row3['mb_nick']?>
                    </td>
                    <td>
                        <?php echo $row3['content']?>
                    </td>
                </tr>
                <!-- 나머지 행들 -->

                <?php }?>
            </tbody>
        </table>
    </div>
</div>
<div class="bottom">
    <span class="btn"
        onclick="location.href='/study/start.php?bo_table=<?php echo $bo_table ?>&num=<?php echo $num + 1 ?>&start=0';">다음으로</span>
    <span class="btn" data-modal-open="pdfModal" data-pdf="<?php echo $pdf_1?>">해설보기</span>
</div>







<div class="modal" id="leftType" role="dialog" aria-modal="true" aria-hidden="true" aria-labelledby="leftType">
    <div class="modal__panel" tabindex="-1">
        <div class="modal__header">
            <div class="modal__title" id="pdfTitle">
                <span id="nickName"></span>
            </div>
            <button class="modal__close" aria-label="닫기">&times;</button>
            <!-- <div class="modal__actions">
                <a class="btn" id="downloadBtn" href="#" download>다운로드</a>
                <button class="btn" data-modal-close>닫기</button>
            </div> -->
        </div>
        <div class="modal__body loading" id="pdfContainer">
            <div class="content">
                <div class="table-wrapper">
                    <table class="custom-table" id="myTable">
                        <thead>
                            <tr>
                                <th style="min-width:100px;">닉네임</th>
                                <th> <?php echo $row_a['wr_7']; ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="height:500px;">
                                    <span id="nickName2"></span>
                                </td>
                                <td style="word-break: break-all;">
                                    <span id="thought"></span>
                                </td>
                            </tr>
                            <!-- 나머지 행들 -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>



<?php }?>





<?php if($start == 1) { ?>
<div class="content ">
    <div style="text-align:center;">
        <button id="startBtn" class="bebas" style="margin-top:30px;">START</button>
    </div>

    <div class="position-center">
        <div class="board" aria-live="polite">
            <div class="block" id="mm" style="display:none;">00</div>
            <div class="block bebas wrap" id="ss">00</div>
            <div class="dots">
                <span class="dot"></span>
                <span class="dot"></span>
            </div>
            <div class="block bebas wrap" id="ms">00</div>
        </div>
    </div>

    <div class="ctrls" style="display:none;">
        <button id="pauseBtn">일시정지</button>
        <button id="resetBtn">리셋</button>
    </div>
</div>
<div class="bottom">
    <span class="btn"
        onclick="location.href='/study/start.php?bo_table=<?php echo $bo_table ?>&num=<?php echo $num ?>&start=0';">이전으로</span>

    <?php if ($row['wr_1'] == 'D') { ?>
    <span class="btn"
        onclick="location.href='/study/start.php?bo_table=<?php echo $bo_table ?>&num=<?php echo $num ?>&start=2';">의견보기</span>
    <?php } else {?>
    <span class="btn"
        onclick="location.href='/study/start.php?bo_table=<?php echo $bo_table ?>&num=<?php echo $num ?>&start=3';">결과보기</span>
    <?php }?>

    <!-- <span class="btn"
        onclick="location.href='/study/start.php?bo_table=<?php echo $bo_table ?>&num=<?php echo $num ?>&start=2';">결과보기</span> -->
    <span class="btn" data-modal-open="pdfModal" data-pdf="<?php echo $pdf_1?>">해설보기</span>
</div>



<div class="" style="display:none;">

</div>
<?php
$sql_start = "
select * from qr_write_{$bo_table} where wr_4 = 'F' order by wr_id desc limit 1
";
$result_start = $sql_start;
$row_start = sql_fetch($result_start);
if($row_start['wr_2'] == 0) {
    ?>
<script>
window.location.href =
    "/study/start.php?bo_table=<?php echo $bo_table ?>&num=<?php echo $num ?>&start=<?php echo $start + 1 ?>"; // 다음 페이지 URL
</script>
<?php }
?>

<script>
(() => {
    const DURATION_MS = <?php echo $row_start['wr_2'] ? $row_start['wr_2'] * 1000 : 10000; ?>; // 1분
    const mmEl = document.getElementById('mm');
    const ssEl = document.getElementById('ss');
    const msEl = document.getElementById('ms');
    const wraps = document.querySelectorAll('.wrap');;
    const startBtn = document.getElementById('startBtn');
    const pauseBtn = document.getElementById('pauseBtn');
    const resetBtn = document.getElementById('resetBtn');

    let rafId = null;
    let endTime = null;
    let remaining = DURATION_MS;
    let running = false;
    let pausedAt = null;

    function pad(n, len = 2) {
        return String(n).padStart(len, '0');
    }

    function render(ms) {
        const total = Math.max(0, ms | 0);
        const m = Math.floor(total / 60000);
        const s = Math.floor((total % 60000) / 1000);
        const ms3 = Math.floor((total % 1000) / 10);
        mmEl.textContent = pad(m);
        ssEl.textContent = pad(s);
        msEl.textContent = pad(ms3, 2);
        // 10초 남으면 긴박감
        if (total <= 10000) wraps.forEach(el => el.classList.add('panic'));
        else wraps.forEach(el => el.classList.remove('panic'));
        if (total === 0) wraps.forEach(el => el.classList.remove('panic'));
    }

    function tick(now) {
        remaining = endTime - now;
        render(remaining);
        if (remaining <= 0) {
            cancelAnimationFrame(rafId);
            running = false;
            endTime = null;
            render(0);
            startBtn.textContent = 'RESTART';
            return;
        }
        rafId = requestAnimationFrame(tick);
    }

    function start() {
        if (running) return;
        const now = performance.now();
        endTime = now + (pausedAt ? remaining : DURATION_MS);
        pausedAt = null;
        running = true;
        startBtn.textContent = 'RUNNING…';
        rafId = requestAnimationFrame(tick);
    }

    function pause() {
        if (!running) return;
        running = false;
        cancelAnimationFrame(rafId);
        rafId = null;
        pausedAt = performance.now();
        // endTime 기준 남은 시간 계산
        remaining = Math.max(0, endTime - pausedAt);
        startBtn.textContent = 'RESUME';
    }

    function reset() {
        running = false;
        cancelAnimationFrame(rafId);
        rafId = null;
        endTime = null;
        pausedAt = null;
        remaining = DURATION_MS;
        render(DURATION_MS);
        startBtn.textContent = 'START';
        wraps.forEach(el => el.classList.remove('panic'));
    }

    startBtn.addEventListener('click', start);
    pauseBtn.addEventListener('click', pause);
    resetBtn.addEventListener('click', reset);

    // 키보드: Space 시작/일시정지, R 리셋
    window.addEventListener('keydown', (e) => {
        if (e.code === 'Space') {
            e.preventDefault();
            running ? pause() : start();
        }
        if (e.key.toLowerCase() === 'r') reset();
    });

    // 최초 화면
    render(DURATION_MS);
    setTimeout(() => {
        start();
    }, );
})();
</script>
<?php } ?>

<?php if($start == 0) { ?>
<div class="content">
    <div class="step">
        <?php echo $row['wr_7'] ?>
    </div>
    <div class="con-title">
        <?php echo $row['wr_subject']?>
    </div>

    <?php if($row['wr_1'] == 'A') { ?>
    <div class="flex" style="margin-top:30px;">
        <div class="vs">VS</div>
        <div class="div mr">
            <div class="description">
                <?php echo $row_file_1['bf_content']; ?>
            </div>
            <div class="box">
                <img src="<?php echo $img_1; ?>" class="q_img">
            </div>
        </div>
        <div class="div ml">
            <div class="description">
                <?php echo $row_file_2['bf_content']; ?>
            </div>
            <div class="box">
                <img src="<?php echo $img_2; ?>" class="q_img ">
            </div>
        </div>
    </div>
    <?php } ?>

    <?php if($row['wr_1'] == 'B') { ?>
    <div class="flex" style="margin-top:30px;">
        <div class="vs">VS</div>
        <div class="div mr">
            <div class="description none">
            </div>
            <div class="box ox">
                <?php echo $row_file_1['bf_content']; ?>

            </div>
        </div>
        <div class="div ml">
            <div class="description none">
            </div>
            <div class="box ox">
                <?php echo $row_file_2['bf_content']; ?>

            </div>
        </div>
    </div>
    <?php } ?>


    <?php if($row['wr_1'] == 'C') { ?>
    <div class="flex" style="margin-top:30px;">
        <div class="vs">VS</div>
        <div class="div mr">
            <div class="description">
                A
            </div>
            <div class="box oxc">
                <?php echo $row_file_1['bf_content']; ?>
            </div>
        </div>
        <div class="div ml">
            <div class="description">
                B
            </div>
            <div class="box oxc">
                <?php echo $row_file_2['bf_content']; ?>
            </div>
        </div>
    </div>
    <?php } ?>

    <?php if($row['wr_1'] == 'D') { ?>
    <div class="type_d">
        <img src="./image/title/type_d.png">
    </div>
    <?php } ?>



</div>
<div class="bottom">
    <span class="btn"
        onclick="location.href='/study/start.php?bo_table=<?php echo $bo_table ?>&num=<?php echo $num ?>&start=1';">시작하기</span>
    <span class="btn" data-modal-open="pdfModal" data-pdf="<?php echo $pdf_1?>">해설보기</span>
</div>

<?php }?>


<!-- 모달 마크업 -->
<div class="modal" id="demoModal" role="dialog" aria-modal="true" aria-hidden="true" aria-labelledby="demoTitle">
    <div class="modal__panel" tabindex="-1">
        <button class="modal__close" aria-label="닫기">&times;</button>
        <div class="modal__header" id="demoTitle">해설 보기</div>
        <div class="modal__body">
            <?php echo $row['wr_content']; ?>
        </div>
        <div class="modal__footer" style="display:none">
            <button class="btn" data-modal-close>취소</button>
            <button class="btn primary" data-modal-close>확인</button>
        </div>
    </div>
</div>

<!-- <button class="btn primary" data-modal-open="pdfModal" data-pdf="<?php echo $pdf_1?>">
    PDF 보기
</button> -->




<!-- 모달 -->
<div class="modal" id="pdfModal" role="dialog" aria-modal="true" aria-hidden="true" aria-labelledby="pdfTitle">
    <div class="modal__panel" tabindex="-1">
        <div class="modal__header">
            <div class="modal__title" id="pdfTitle">문서 미리보기</div>
            <button class="modal__close" aria-label="닫기">&times;</button>
            <!-- <div class="modal__actions">
                <a class="btn" id="downloadBtn" href="#" download>다운로드</a>
                <button class="btn" data-modal-close>닫기</button>
            </div> -->
        </div>
        <div class="modal__body loading" id="pdfContainer">
            <!-- 기본 내장 PDF 뷰어 -->
            <iframe id="pdfFrame" class="pdf-frame" title="PDF 미리보기" allow="fullscreen"></iframe>
            <!-- iOS 사파리에서 PDF 뷰가 약한 경우를 위한 예비(필요 시 주석 해제하여 사용)
      <object id="pdfObject" class="pdf-frame" type="application/pdf" data=""></object>
      -->
        </div>
    </div>
</div>
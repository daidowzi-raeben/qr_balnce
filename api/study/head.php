<style>
:root {
    --overlay: rgba(0, 0, 0, .5);
    --radius: 16px;
    --gap: 16px;
}

* {
    box-sizing: border-box
}

body.modal-open {
    overflow: hidden
}

/* 모달 공통 */
.modal {
    position: fixed;
    inset: 0;
    display: none;
    align-items: center;
    justify-content: center;
    background: var(--overlay);
    padding: 24px;
    z-index: 9999;
}

.modal[aria-hidden="false"] {
    display: flex;
}

.modal__panel {
    background: #fff;
    color: #111;
    width: min(96%, 96vw);
    height: min(90vh, 92vw);
    border-radius: var(--radius);
    box-shadow: 0 20px 60px rgba(0, 0, 0, .2);
    transform: translateY(8px) scale(.98);
    opacity: 0;
    transition: transform .18s ease, opacity .18s ease;
    outline: 0;
}

.modal[aria-hidden="false"] .modal__panel {
    transform: translateY(0) scale(1);
    opacity: 1;
}

.modal__header {
    padding: 16px var(--gap);
    font-weight: 800;
    background: #0b7eeb;
    color: #fff;
    border-radius: var(--radius) var(--radius) 0 0;
    border-bottom: 1px solid #eee;
}

.modal__body {
    /* padding: 40px; */
    line-height: 1.6;
    font-size: 28px;
}

.modal__footer {
    padding: 16px var(--gap);
    display: flex;
    gap: 8px;
    justify-content: flex-end;
    border-top: 1px solid #eee;
}

.btn {
    padding: 10px 14px;
    border-radius: 10px;
    border: 1px solid #ddd;
    background: #fff;
    cursor: pointer;
}

.btn.primary {
    background: #111;
    color: #fff;
    border-color: #111;
}

.modal__close {
    position: absolute;
    top: 10px;
    right: 10px;
    border: 0;
    background: transparent;
    font-size: 22px;
    cursor: pointer;
    color: #fff;
}
</style>

<style>
@font-face {
    font-family: 'KOHIBaeumOTF';
    src: url('https://fastly.jsdelivr.net/gh/projectnoonnu/noonfonts_2201-2@1.0/KOHIBaeumOTF.woff') format('woff');
    font-weight: normal;
    font-style: normal;
}

.bebas {
    font-family: 'KOHIBaeumOTF', sans-serif !important;
    /* font-size: 40px; */
    /* color: #ff5200; */
}

.wrap {
    /* width: min(1200px, 92vw); */
    /* padding: 28px 28px 40px; */
    /* border: 10px solid #4889d6; */
    /* border-radius: 14px; */
    /* background: #4889d6; */
    /* text-align: center; */
    position: relative;
    transition: border-color .2s ease;
}

.wrap.c {
    animation: borderPulse .6s linear infinite
}

@keyframes borderPulse {

    0%,
    100% {
        border-color: #fff
    }

    50% {
        border-color: #1020c0
    }
}

#startBtn {
    appearance: none;
    border: 0;
    background: transparent;
    cursor: pointer;
    color: #ffa90c;
    font-weight: 800;
    letter-spacing: .08em;
    font-size: 38px;
    margin: 6px 0 24px;
    user-select: none;
}

.board {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 24px;
}

.block {
    width: min(288px, 22vw);
    height: min(433px, 30vw);
    border-radius: 18px;
    background: #D1E5FD;
    display: grid;
    place-items: center;
    font-variation-settings: 'wght'900;
    font-size: clamp(80px, 12vw, 180px);
    color: #000;
    box-shadow: 0 4px 0 rgba(0, 0, 0, .08) inset;
    font-weight: bold;
    /* background: #D1E5FD; */
    border: 2px solid #000;
}

.block.small {
    width: min(200px, 18vw);
    height: min(240px, 23vw);
    font-size: clamp(48px, 9vw, 120px);
}

.dots {
    display: grid;
    place-items: center;
    gap: 28px;
    height: min(320px, 30vw);
    width: min(60px, 7vw);
    align-content: space-evenly;

}

.dot {
    width: min(160px, 2vw);
    height: min(160px, 2vw);
    /* background: var(--navy); */
    border-radius: 50%;
    background: #000;
}

.panic {
    animation: blink 1s linear infinite
}

@keyframes blink {
    from {
        background-color: #000;
        color: #fff;
    }

    to {
        background-color: #ef1515;
    }
}

/* 조작 버튼(선택) */
.ctrls {
    margin-top: 18px;
    display: flex;
    gap: 10px;
    justify-content: center
}

.ctrls button {
    padding: 10px 14px;
    border-radius: 10px;
    /* border: 2px solid var(--navy); */
    border: 2px solid #4889d6;
    background: #fff;
    color: #4889d6;
    font-weight: 700;
    cursor: pointer
}
</style>



<style>
@font-face {
    font-family: 'GmarketSansLight';
    src: url('https://cdn.jsdelivr.net/gh/projectnoonnu/noonfonts_2001@1.1/GmarketSansLight.woff') format('woff');
    font-weight: 300;
    font-style: normal;
    font-display: swap;
}

@font-face {
    font-family: 'GmarketSansMedium';
    src: url('https://cdn.jsdelivr.net/gh/projectnoonnu/noonfonts_2001@1.1/GmarketSansMedium.woff') format('woff');
    font-weight: 500;
    font-style: normal;
    font-display: swap;
}

@font-face {
    font-family: 'GmarketSansBold';
    src: url('https://cdn.jsdelivr.net/gh/projectnoonnu/noonfonts_2001@1.1/GmarketSansBold.woff') format('woff');
    font-weight: 700;
    font-style: normal;
    font-display: swap;
}

@import url('https://fastly.jsdelivr.net/gh/wanteddev/wanted-sans@v1.0.1/packages/wanted-sans/fonts/webfonts/variable/split/WantedSansVariable.min.css');

:root,
body {
    font-family: 'GmarketSansMedium', 'GmarketSans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    background: #000066;
}

* {
    font-family: 'GmarketSansMedium', 'GmarketSans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.vs {
    font-family: 'Wanted Sans Variable', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    position: absolute;
    z-index: 9;
    font-size: 100px;
    font-weight: bold;
    color: #FF5500;
    left: 50%;
    /* margin-right: -60px; */
    top: 50%;
    transform: translate(-50%, -50%);
}


.img_qr {
    position: absolute;
    z-index: 9;
    /* left: 50%; */
    /* top: 0; */
    top: -564px;
    /* transform: translate(-50%, -50%); */
    right: 202px;
    /* transform: translate(-50%, -50%); */
}

.position-center {
    left: 50%;
    /* margin-right: -60px; */
    top: 50%;
    transform: translate(-50%, -50%);
    position: absolute;
}

.bold {
    font-family: 'GmarketSansBold', sans-serif;
}

.light {
    font-family: 'GmarketSansLight', sans-serif;
}

/* 박스 사이징 리셋 */
html {
    box-sizing: border-box;
}

*,
*::before,
*::after {
    box-sizing: inherit;
}

/* .content * {
    font-family: 'GmarketSansLight', sans-serif;
} */

.content {
    max-width: 1900px;
    background: #fff;
    width: 95%;
    margin: 20px auto 0;
    height: 80vh;
    border-radius: 10px;
    padding: 20px;
    position: relative;
}

.flex {
    display: flex;
    justify-content: space-between;
    position: relative;
}

.q_img {
    width: 100%;
}

.bottom {
    margin-top: 20px;
    text-align: center;
    display: none;
}

.bottom .btn {
    display: inline-block;
    padding: 15px 70px;
    background: #fff;
    border-radius: 100px;
    margin: 10px 15px;
    cursor: pointer;
}

.bottom .btn:hover {
    background: #BEFFFF;
    color: #3867F9;
}

.box {
    border: 3px solid #000066;
    max-height: 415px;
    overflow: hidden;
    border-radius: 10px;
    margin-top: 20px;

}

.ml {
    margin-left: 30px;
}

.mr {
    margin-right: 30px;
}

.flex>.div {
    flex: 1;
    position: relative;
}

.description {
    position: absolute;
    border: 3px solid #000066;
    width: 300px;
    padding: 10px 0;
    text-align: center;
    background: #fff;
    border-radius: 100px;
    font-size: 20px;
    left: 50%;
    transform: translate(-50%, 0%);
    margin-top: -5px;
    z-index: 8;
}

.ox {
    background: #eee;
    font-size: 200px;
    font-weight: bold;
    text-align: center;
    min-height: 350px;
    line-height: 350px;
    max-height: 450px;
}


.oxc {
    background: #eee;
    font-size: 50px;
    font-weight: bold;
    text-align: center;
    min-height: 350px;
    max-height: 450px;
    display: flex;
    justify-content: center;
    /* 가로 가운데 */
    align-items: center;
    /* 세로 가운데 */
}

.none {
    display: none;
}

.con-title {
    background: #fff;
    background: #000066;
    text-align: center;
    font-size: 35px;
    font-weight: bold;
    padding: 20px;
    border-radius: 10px;
    color: #fff;
}
</style>



<style>
:root {
    --overlay: rgba(0, 0, 0, .55);
    --radius: 16px;
}

* {
    box-sizing: border-box
}

body.modal-open {
    overflow: hidden
}

.modal {
    position: fixed;
    inset: 0;
    display: none;
    align-items: center;
    justify-content: center;
    background: var(--overlay);
    padding: 24px;
    z-index: 9999
}

.modal[aria-hidden="false"] {
    display: flex
}

.modal__panel {
    background: #fff;
    width: min(1100px, 96vw);
    height: min(85vh, 900px);
    border-radius: var(--radius);
    box-shadow: 0 24px 80px rgba(0, 0, 0, .25);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    position: relative;
}

.modal__header {
    padding: 12px 16px;
    border-bottom: 1px solid #eee;
    display: flex;
    gap: 10px;
    align-items: center
}

.modal__title {
    font-weight: 800
}

.modal__actions {
    margin-left: auto;
    display: flex;
    gap: 8px
}

.btn {
    padding: 8px 12px;
    border-radius: 10px;
    border: 1px solid #ddd;
    background: #fff;
    cursor: pointer
}

.btn.primary {
    background: #111;
    color: #fff;
    border-color: #111
}

.modal__body {
    flex: 1;
    background: #f8f9fb;
}

.pdf-frame {
    width: 100%;
    height: 100%;
    border: 0;
    background: #fff
}

/* 로딩 상태 */
.loading::after {
    /* content: '불러오는 중…'; */
    position: absolute;
    inset: 0;
    display: grid;
    place-items: center;
    color: #666;
    font-weight: 700;
    background: linear-gradient(#fff8, #fff8);
}

/* 작은 화면에서 헤더 줄바꿈 */
@media (max-width:480px) {
    .modal__header {
        flex-wrap: wrap
    }

    .modal__actions {
        width: 100%;
        justify-content: flex-end
    }
}

.table-wrapper {
    max-height: 650px;
    /* 스크롤 높이 제한 */
    overflow-y: auto;
    border: 2px solid #e0e0e0;
    border-radius: 6px;
}

.custom-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 16px;
    min-width: 600px;
}

.custom-table thead th {
    background-color: #1E50FF;
    /* 파란색 헤더 */
    color: white;
    font-weight: bold;
    padding: 12px;
    text-align: left;
    position: sticky;
    /* 스크롤해도 헤더 고정 */
    top: 0;
    z-index: 1;
}

.custom-table tbody td {
    padding: 12px;
    border-bottom: 1px solid #ddd;
    vertical-align: top;
}

.custom-table tbody tr:hover {
    background-color: #f5f5f5;
}

/* 스크롤바 커스터마이징 (웹킷 브라우저) */
.table-wrapper::-webkit-scrollbar {
    width: 8px;
}

.table-wrapper::-webkit-scrollbar-thumb {
    background-color: rgba(0, 0, 0, 0.2);
    border-radius: 4px;
}

.table-wrapper::-webkit-scrollbar-track {
    background-color: transparent;
}


/* VS 펄스 */
.vs {
    /* font: 900 72px/1 'Wanted Sans Variable', system-ui, sans-serif; */
    /* color: #ff6a00; */
    transition: transform .2s ease;
}

.vs.pulse {
    animation: vsPulse .8s ease-in-out infinite;
    transform-origin: center;
}

@keyframes vsPulse {
    0% {
        transform: translate(-50%, -50%) scale(1)
    }

    50% {
        transform: translate(-50%, -50%) scale(1.25)
    }

    100% {
        transform: translate(-50%, -50%) scale(1);
    }
}

/* 승리/패배 스타일 */
.box.ox.winner {
    border-color: #ffb400;
    box-shadow: 0 0 0 3px #ffe08a inset, 0 0 24px rgba(255, 180, 0, .65);
    position: relative;
    animation: winPop 1s ease-out infinite;
}

.box.ox.winner::after {
    /* content: '👑';
    position: absolute;
    top: -18px;
    right: -12px;
    font-size: 36px;
    transform: rotate(12deg); */
}

.type_d {
    position: absolute;
    bottom: 0;
    width: 100%;
    text-align: center;
}

.step {
    font-size: 30px;
    text-align: center;
    padding: 20px;
    font-weight: bold;
}

@keyframes winPop {
    0% {
        transform: scale(1)
    }

    60% {
        transform: scale(1.12)
    }

    100% {
        transform: scale(1)
    }
}

.box.ox.loser {
    opacity: .55;
    filter: saturate(.85);
    animation: loseShake .28s ease-in-out 2;
}

@keyframes loseShake {

    0%,
    100% {
        transform: translateX(0)
    }

    25% {
        transform: translateX(-5px)
    }

    75% {
        transform: translateX(5px)
    }
}

/* 무승부 표시 (선택) */
.vs.draw {
    color: #0c1670;
    animation: none
}

#myTable tr.selected {
    background-color: #1976d2;
    color: white;
}
</style>


<?php

//   $sql = " select bo_table
//                 from `{$g5['board_table']}` a left join `{$g5['group_table']}` b on (a.gr_id=b.gr_id)
//                 where a.bo_device <> 'mobile' ";
//     $result = sql_query($sql);
//     for ($i=0; $row=sql_fetch_array($result); $i++) { 
//     }


$list = sql_query("select *
,(select mb_nick from qr_member_student where idx = a.mb_id) as mb_nick
from qr_member_student_qa as a where bo_table= '{$bo_table}' and num = '{$num}'");

$sql = " select * 
from qr_write_".$bo_table." where wr_4 = 'A'  limit $num, 1  ";

$result = $sql;
$row = sql_fetch($result);




$sql_a = " select * 
from qr_write_".$bo_table." where wr_4 = 'B'  limit $num, 1  ";

$result_a = $sql_a;
$row_a = sql_fetch($result_a);

$sql_bb = " select * from qr_write_".$bo_table." where wr_4 = 'B' limit $num, 1  ";
$result_bb = $sql_bb;
$row_bb = sql_fetch($result_bb);


$imgs = $start - 5;
$sql_img = "select * from qr_board_file where 
bo_table = '{$bo_table}' and wr_id = '{$row_bb['wr_id']}' and bf_content = '' order by bf_no asc
limit $imgs, 1
";

$result_img = $sql_img;
$row_img = sql_fetch($result_img);


$sql_img_cnt = "select count(*) as cnt from qr_board_file where 
bo_table = '{$bo_table}' and wr_id = '{$row_bb['wr_id']}' and bf_content = '' order by bf_no asc
";
$result_img_cnt = $sql_img_cnt;
$row_img_cnt = sql_fetch($result_img_cnt);




$sql2 = sql_query("select *
,(select mb_nick from qr_member_student where idx = a.mb_id) as mb_nick
from qr_member_student_qa as a where bo_table= '{$bo_table}' and num = '{$num}' and chk = '1'");

// $result2 = $sql2;
// $row2 = sql_fetch($result2);


$sql3 =  sql_query("select *
,(select mb_nick from qr_member_student where idx = a.mb_id) as mb_nick
from qr_member_student_qa as a where bo_table= '{$bo_table}' and num = '{$num}' and chk = '2'");

// $result3 = $sql3;
// $row3 = sql_fetch($result3);





// $result2 = $sql2;
$chk1 = sql_fetch("select count(*) as cnt
from qr_member_student_qa as a where bo_table= '{$bo_table}' and num = '{$num}' and chk = '1'");


$chk2 = sql_fetch("select count(*) as cnt
from qr_member_student_qa as a where bo_table= '{$bo_table}' and num = '{$num}' and chk = '2'");



$sql_file_1 = "select * from qr_board_file where wr_id = '{$row['wr_id']}' order by bf_no asc limit 0,1 ";
$row_file_1 = sql_fetch($sql_file_1);
$img_1 = G5_DATA_URL . "/file/" . $bo_table . "/". $row_file_1['bf_file'];

$sql_file_2 = "select * from qr_board_file where wr_id = '{$row['wr_id']}' order by bf_no asc limit 1,1 ";
$row_file_2 = sql_fetch($sql_file_2);
$img_2 = G5_DATA_URL . "/file/" . $bo_table . "/". $row_file_2['bf_file'];

$sql_file_3 = "select * from qr_board_file where wr_id = '{$row['wr_id']}' and bf_content = 'PDF' order by bf_no asc limit 1 ";
$row_file_3 = sql_fetch($sql_file_3);
$pdf_1 = G5_DATA_URL . "/file/" . $bo_table . "/". $row_file_3['bf_file'];

?>



<script>
// 현재 URL에서 쿼리스트링 가져오기
const params = new URLSearchParams(window.location.search);

// 개별 값 읽기
const bo_table = params.get('bo_table'); // "free"
const intro = params.get('intro'); // "1"
const start = params.get('start'); // "1"
const num = params.get('num'); // "1"
const total_img = <?php echo $row_img_cnt['cnt']?>; // "1"
</script>


<?php if($intro > 0) { ?>
<script>
document.addEventListener('keydown', function(e) {
    // 왼쪽 화살표 키
    if (e.key === "PageUp" && intro > 1) {
        window.location.href =
            "/study/?bo_table=<?php echo $bo_table ?>&intro=<?php echo $intro - 1 ?>"; // 이전 페이지 URL
    }
    // 오른쪽 화살표 키
    else if (e.key === "PageDown") {
        if (intro === '5') {
            window.location.href =
                "/study/start.php?bo_table=<?php echo $bo_table ?>&num=0&start=0"; // 다음 페이지 URL
        } else {
            window.location.href =
                "/study/?bo_table=<?php echo $bo_table ?>&intro=<?php echo $intro + 1 ?>"; // 다음 페이지 URL
        }
    }

    // console.log("e.key:", e.key); // 눌린 키 이름 (예: "ArrowUp", "a", "PageDown")
    // console.log("e.code:", e.code); // 키보드 물리적 위치 코드 (예: "ArrowUp", "KeyA", "PageDown")
    // console.log("keyCode:", e.keyCode); // 예전 방식 (예: 38=위, 40=아래, 33=PageUp, 34=PageDown)
});
</script>
<?php }?>

<?php if($num > -1) { ?>
<script>
document.addEventListener('keydown', function(e) {
    // 왼쪽 화살표 키
    if (e.key === "PageUp" && start > 0) {
        // history.back(-1)
        window.location.href =
            "/study/start.php?bo_table=<?php echo $bo_table ?>&num=<?php echo $num ?>&start=<?php echo $start - 1 ?>"; // 이전 페이지 URL
    }
    // 오른쪽 화살표 키
    else if (e.key === "PageDown") {
        if (total_img + 3 < start) {
            console.log(total_img)
            window.location.href =
                "/study/start.php?bo_table=<?php echo $bo_table ?>&num=<?php echo $num + 1 ?>&start=0"; // 다음 페이지 URL
            return;

        } else {


            <?php if ($row['wr_1'] == 'D' && $start == 3) { ?>
            window.location.href =
                "/study/start.php?bo_table=<?php echo $bo_table ?>&num=<?php echo $num ?>&start=<?php echo $start + 2 ?>"; // 다음 페이지 URL
            <?php } else {?>
            window.location.href =
                "/study/start.php?bo_table=<?php echo $bo_table ?>&num=<?php echo $num ?>&start=<?php echo $start + 1 ?>"; // 다음 페이지 URL
            <?php }?>
        }

    }


    if (start === '2') {

        if (e.key === ".") {
            onclickModal('leftType')
        } else if (e.key === "Escape") {
            onclickModal('rightType')
        }

    }


    // if (e.key === "PageUp") {
    //     console.log("PageUp 눌림");
    // } else if (e.key === "PageDown") {
    //     console.log("PageDown 눌림");
    // }
    console.log("e.key:", e.key); // 눌린 키 이름 (예: "ArrowUp", "a", "PageDown")
    // console.log("e.code:", e.code); // 키보드 물리적 위치 코드 (예: "ArrowUp", "KeyA", "PageDown")
    // console.log("keyCode:", e.keyCode); // 예전 방식 (예: 38=위, 40=아래, 33=PageUp, 34=PageDown)
});
</script>
<?php }?>




<style>
#myTable tr.selected {
    background-color: #1976d2;
    color: white;
}

.row-selected {
    background: #1976d2;
    color: #fff;
}
</style>

<script>
(function initTableNav(selector = '#myTable') {
    // DOM이 준비된 뒤 실행
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => initTableNav(selector));
        return;
    }

    const tableEl = document.querySelector(selector);
    if (!tableEl) {
        console.error('테이블을 찾지 못했습니다. selector:', selector);
        return;
    }

    const tbody = tableEl.tBodies[0] || tableEl; // tbody 없으면 table에 대해 동작
    let selectedIndex = -1;

    const getRows = () => tbody.querySelectorAll('tr');

    function selectRow(idx) {
        const rows = getRows();
        if (!rows.length) return;

        rows.forEach(r => r.classList.remove('row-selected'));
        // 범위 보정
        idx = Math.max(0, Math.min(idx, rows.length - 1));

        rows[idx].classList.add('row-selected');
        rows[idx].scrollIntoView({
            block: 'nearest'
        });
        selectedIndex = idx;
    }

    // 마우스로 클릭해도 선택되게
    tableEl.addEventListener('click', (e) => {
        const tr = e.target.closest('tr');
        if (!tr) return;
        const rows = Array.from(getRows());
        const idx = rows.indexOf(tr);
        if (idx !== -1) selectRow(idx);
    });

    // 키보드 네비게이션 + Enter 데이터 가져오기
    document.addEventListener('keydown', (e) => {
        const rows = getRows();
        if (!rows.length) return;

        if (e.key === 'ArrowDown') {
            e.preventDefault();
            selectRow(selectedIndex + 1);
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            selectRow(selectedIndex - 1);
        } else if (e.key === 'F5' || e.key === 'Escape') {
            e.preventDefault();
            const row = rows[selectedIndex];
            if (!row) return;

            const cells = row.querySelectorAll('th,td');
            const nickname = cells[0]?.innerText?.trim() ?? '';
            const thought = cells[1]?.innerText?.trim() ?? '';

            document.getElementById('nickName').innerText = nickname
            document.getElementById('nickName2').innerText = nickname
            document.getElementById('thought').innerText = thought

            // 원하는 동작으로 교체 (예: alert, 폼 채우기, 서버 전송 등)
            console.log({
                nickname,
                thought
            });
            onclickModal('leftType')
            // alert(`닉네임: ${nickname}\n나의 생각: ${thought}`);
        }
    });

    // 초기 선택 (행이 있을 때만)
    if (getRows().length) selectRow(0);
})();
</script>



<script>
// ===== Modal core =====
// (() => {
const state = {
    active: null,
    lastFocus: null
};

function openModal(modal) {
    if (!modal) return;
    state.lastFocus = document.activeElement;
    modal.setAttribute('aria-hidden', 'false');
    document.body.classList.add('modal-open');
    state.active = modal;

    // 첫 포커스: 패널로 이동
    const panel = modal.querySelector('.modal__panel');
    panel && panel.focus();

    // ESC 닫기
    document.addEventListener('keydown', onKeydown);
    // 배경 클릭 닫기
    modal.addEventListener('mousedown', onBackdrop);
    // 포커스 트랩
    modal.addEventListener('keydown', trapFocus);
}


function closeModal(modal) {
    if (!modal) return;
    modal.setAttribute('aria-hidden', 'true');
    document.body.classList.remove('modal-open');

    document.removeEventListener('keydown', onKeydown);
    modal.removeEventListener('mousedown', onBackdrop);
    modal.removeEventListener('keydown', trapFocus);

    // 원래 포커스로 복귀
    state.lastFocus && state.lastFocus.focus?.();
    state.active = null;
}

function onKeydown(e) {
    if (e.key === '.' && state.active) closeModal(state.active);
}

function onBackdrop(e) {
    // 패널 바깥(overlay)을 클릭했을 때만 닫기
    if (e.target === state.active) closeModal(state.active);
}

function trapFocus(e) {
    if (e.key !== 'Tab') return;
    const focusables = state.active.querySelectorAll(
        'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
    );
    if (!focusables.length) return;
    const first = focusables[0];
    const last = focusables[focusables.length - 1];
    if (e.shiftKey && document.activeElement === first) { // Shift+Tab at first
        e.preventDefault();
        last.focus();
    } else if (!e.shiftKey && document.activeElement === last) { // Tab at last
        e.preventDefault();
        first.focus();
    }
}

// 공개 API: onclickModal('modalId')
window.onclickModal = function(id) {
    const modal = document.getElementById(id);
    openModal(modal);
};

// 닫기 버튼들
document.addEventListener('click', (e) => {
    if (e.target.matches('[data-modal-close], .modal__close')) {
        const modal = e.target.closest('.modal');
        closeModal(modal);
    }
});
// })();
</script>
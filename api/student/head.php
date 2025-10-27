<?php
    echo '<meta name="viewport" id="meta_viewport" content="width=device-width,initial-scale=1.0,minimum-scale=0,maximum-scale=10">'.PHP_EOL;
    echo '<meta name="HandheldFriendly" content="true">'.PHP_EOL;
    echo '<meta name="format-detection" content="telephone=no">'.PHP_EOL;

    ?>

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
    margin: 0;
    padding: 0;
    height: 100%;
}

* {
    font-family: 'GmarketSansMedium', 'GmarketSans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    box-sizing: border-box
}


.title {
    text-align: center;
    height: 100px;
    line-height: 90px;
    background: #4376f8;
    color: #fff;
    font-size: 2rem;
}

.content {
    /* min-height: calc(100% - 100px); */
    background: #fff;
    border-radius: 10px 10px 0 0;
    position: absolute;
    margin-top: -10px;
    width: 100%;
    padding: 1rem;
}

.btn {
    background: #4376f8;
    color: #fff;
    height: 40px;
    line-height: 40px;
    border: none;
    width: 100%;
    border-radius: 100px;
}

.mt-20 {
    margin-top: 20px;
}

.mt-40 {
    margin-top: 40px;
}

.input {
    border: 1px solid #ccc;
    width: 100%;
    height: 40px;
    padding: 10px;
    /* line-height: 60px; */
}

.before_box {
    background: #EAEEFF;
    height: 500px;
    border-radius: 10px;
    text-align: center;
}

.before_title {
    text-align: center;
    margin-top: 20px;
    color: #4376f8;
    font-size: 40px;
    animation: blinkFade 1.5s infinite;
}

@keyframes blinkFade {

    0%,
    100% {
        opacity: 1;
    }

    50% {
        opacity: 0;
    }
}

.btn-qr {
    padding: 20px;
    text-align: center;
    color: #4376f8;
    font-size: 20px;
    border: 1px solid #4376f8;
    border-radius: 10px;
    margin-top: 20px;
}
</style>


<?php
// if(!$num) $num =0;
$sql = " select * from qr_write_".$bo_table." where wr_4 = 'A' limit $num, 1  ";
$result = $sql;
$row = sql_fetch($result);


$sql_bb = " select * from qr_write_".$bo_table." where wr_4 = 'B' limit $num, 1  ";
$result_bb = $sql_bb;
$row_bb = sql_fetch($result_bb);



$sql_file_1 = "select * from qr_board_file where wr_id = '{$row['wr_id']}' order by bf_no asc limit 0,1 ";
$row_file_1 = sql_fetch($sql_file_1);
$img_1 = G5_DATA_URL . "/file/" . $bo_table . "/". $row_file_1['bf_file'];

$sql_file_2 = "select * from qr_board_file where wr_id = '{$row['wr_id']}' order by bf_no asc limit 1,1 ";
$row_file_2 = sql_fetch($sql_file_2);
$img_2 = G5_DATA_URL . "/file/" . $bo_table . "/". $row_file_2['bf_file'];

$sql_file_3 = "select * from qr_board_file where wr_id = '{$row['wr_id']}' and bf_content = 'PDF' order by bf_no asc limit 1 ";
$row_file_3 = sql_fetch($sql_file_3);
$pdf_1 = G5_DATA_URL . "/file/" . $bo_table . "/". $row_file_3['bf_file'];

$sql2 = " select * from qr_member_student where idx = '{$id}' limit 1  ";
$result2 = $sql2;
$row_member = sql_fetch($result2);




?>
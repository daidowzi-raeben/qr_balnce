<?php
include_once('./_common.php');
include_once('./head.php');




$sql_start = "
select * from qr_write_{$bo_table} where wr_4 = 'F' order by wr_id desc limit 1
";
$result_start = $sql_start;
$row_start = sql_fetch($result_start);

$sql_file_0 = "select * from qr_board_file where wr_id = '{$row_start['wr_id']}' order by bf_no asc limit 0,1 ";
$row_file_0 = sql_fetch($sql_file_0);
$img_0 = G5_DATA_URL . "/file/" . $bo_table . "/". $row_file_0['bf_file'];


$sql_file_00 = "select * from qr_board_file where wr_id = '{$row_start['wr_id']}' order by bf_no asc limit 2,1 ";
$row_file_00 = sql_fetch($sql_file_00);
$img_00 = G5_DATA_URL . "/file/" . $bo_table . "/". $row_file_00['bf_file'];


$sql_qr = "
select * from qr_write_{$bo_table} where wr_4 = 'E' order by wr_id desc limit 1
";
$result_qr = $sql_qr;
$row_qr = sql_fetch($result_qr);

$sql_file_qr = "select * from qr_board_file where wr_id = '{$row_qr['wr_id']}' order by bf_no asc limit 0,1 ";
$row_file_qr = sql_fetch($sql_file_qr);
$img_qr = G5_DATA_URL . "/file/" . $bo_table . "/". $row_file_qr['bf_file'];
?>


<style>
@font-face {
    font-family: 'Isamanru';
    src: url('https://cdn.jsdelivr.net/gh/projectnoonnu/noonfonts_20-10@1.0/GongGothicLight.woff') format('woff');
    font-weight: 300;
    font-display: swap;
}

@font-face {
    font-family: 'Isamanru';
    src: url('https://cdn.jsdelivr.net/gh/projectnoonnu/noonfonts_20-10@1.0/GongGothicMedium.woff') format('woff');
    font-weight: normal;
    font-display: swap;
}

@font-face {
    font-family: 'Isamanru';
    src: url('https://cdn.jsdelivr.net/gh/projectnoonnu/noonfonts_20-10@1.0/GongGothicBold.woff') format('woff');
    font-weight: 700;
    font-display: swap;
}

.Isamanru {
    font-family: 'Isamanru';
}

body,
html {
    overflow: hidden
}
</style>

<?php if($intro == 1) {?>
<div style="text-align:center;">
    <a href="./?bo_table=<?php echo $bo_table?>&intro=2">
        <img src="<?php echo $img_0;?>" width="1920">
    </a>
</div>
<?php }?>

<?php if($intro == 2) {?>
<div style="text-align:center;">
    <a href="./?bo_table=<?php echo $bo_table?>&intro=3" style="position:relative">
        <img src="<?php echo $img_qr;?>" width="1920">
    </a>
</div>
<?php }?>

<?php if($intro == 3) {?>
<div style="text-align:center;">
    <div class="Isamanru" style="color: #2c57fb;
    position: absolute;
    z-index: 99;
    font-size: 57px;
     top: 50%; 
    left: 50%;
    transform: translate(-50%,-50%);
        margin-top: -73px;
    margin-left: -31px;
    ">
        <?php echo $row_start['wr_2']; ?>
    </div>
    <a href="./?bo_table=<?php echo $bo_table?>&intro=4">
        <img src="./image/title/3.png" width="1920" style="position: absolute;
    top: 50%; 
    left: 50%;
    transform: translate(-50%,-50%); width:1920px; height:1080px;">
    </a>
</div>
<?php }?>

<?php if($intro == 4) {?>
<div style="text-align:center;">
    <a href="./?bo_table=<?php echo $bo_table?>&intro=5">
        <img src="<?php echo $img_00;?>" width="1920">
    </a>
</div>
<?php }?>

<?php if($intro == 5) {?>
<div style="text-align:center;">
    <a href="./start.php?bo_table=<?php echo $bo_table?>&num=0&start=0">
        <img src="./image/title/5.png" width="1820">
    </a>
</div>
<?php }?>
<?php
include_once('./_common.php');
include_once('./head.php');
?>

<?php if($intro == 1) {?>
<div style="text-align:center;">
    <a href="./?bo_table=<?php echo $bo_table?>&intro=2">
        <img src="./image/title/1.png" width="1820" style="max-width:1820px">
    </a>
</div>
<?php }?>

<?php if($intro == 2) {?>
<div style="text-align:center;">
    <a href="./?bo_table=<?php echo $bo_table?>&intro=3" style="position:relative">
        <img src="./image/title/2.png" width="1820" style="max-width:1820px">
        <div class="img_qr">
            <img width="330"
                src="https://api.qrserver.com/v1/create-qr-code/?size=330x330&data=<?php echo G5_URL.'/student/?bo_table='.$bo_table?>">
        </div>
    </a>
</div>
<?php }?>

<?php if($intro == 3) {?>
<div style="text-align:center;">
    <a href="./?bo_table=<?php echo $bo_table?>&intro=4">
        <img src="./image/title/3.png" width="1820" style="max-width:1820px">
    </a>
</div>
<?php }?>

<?php if($intro == 4) {?>
<div style="text-align:center;">
    <a href="./?bo_table=<?php echo $bo_table?>&intro=5">
        <img src="./image/title/4.png" width="1820" style="max-width:1820px">
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
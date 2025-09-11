<?php
include_once('./_common.php');

define('_INDEX_', true);
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if(defined('G5_THEME_PATH')) {
    require_once(G5_THEME_PATH.'/index.php');
    return;
}

if (G5_IS_MOBILE) {
    include_once(G5_MOBILE_PATH.'/index.php');
    return;
}

include_once(G5_PATH.'/head.php');
?>

<div class="latest_wr">
    <!-- 최신글 시작 { -->
    <?php
    
    //  최신글
    $sql = " select * from qr_board ";
    $result = sql_query($sql);
    for ($i=0; $row=sql_fetch_array($result); $i++) {
    ?>
    <div class="lt_wr" style="margin-bottom:10px;">
        <a href="/study/?bo_table=<?php echo $row['bo_table']?>&intro=1" target="_blank"><?php echo $row['bo_subject']?>
            바로가기</a>
    </div>
    <?php
    }
    ?>
    <!-- } 최신글 끝 -->
</div>
<?php
include_once(G5_PATH.'/tail.php');
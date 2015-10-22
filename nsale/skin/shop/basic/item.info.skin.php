<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.G5_SHOP_CSS_URL.'/style.css">', 0);
?>

<script src="<?php echo G5_JS_URL; ?>/viewimageresize.js"></script>

<!-- 상품 정보 시작 { -->
<section id="sit_inf">
    <h2>상품 정보</h2>
    <?php echo pg_anchor('inf'); ?>

    <?php if ($it['it_explan']) { // 상품 상세설명 ?>
    <h3>상품 상세설명</h3>
    <div id="sit_inf_explan">
        <?php echo conv_content($it['it_explan'], 1); ?>
    </div>
    <?php } ?>

</section>
<!-- } 상품 정보 끝 -->



<!-- 상품문의 시작 { -->
<section id="sit_qa">
    <h2>상품문의</h2>
    <?php echo pg_anchor('qa'); ?>

    <div id="itemqa"><?php include_once('./itemqa.php'); ?></div>
</section>
<!-- } 상품문의 끝 -->

<script>
$(window).on("load", function() {
    $("#sit_inf_explan").viewimageresize2();
});
</script>
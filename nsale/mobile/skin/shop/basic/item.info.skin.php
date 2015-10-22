<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.G5_SHOP_CSS_URL.'/style.css">', 0);
?>

<script src="<?php echo G5_JS_URL; ?>/viewimageresize.js"></script>

<?php //if ($default['de_baesong_content']) { // 필독정보 내용이 있다면 ?>
<!-- 필독정보 시작 { 
<section id="sit_dvr">
    <h2>필독 정보</h2>
    <?php //echo pg_anchor('dvr'); ?>

    <?php //echo conv_content($default['de_baesong_content'], 1); ?>
</section>
 } 필독정보 끝 -->
<?php //} ?>

<!-- 상품 정보 시작 { -->
<section id="sit_inf">
    <h2>상품 정보</h2>
    <?php echo pg_anchor('inf'); ?>

    <?php if ($it['it_basic']) { // 상품 기본설명 ?>
    <h3>상품 기본설명</h3>
    <div id="sit_inf_basic">
         <?php echo $it['it_basic']; ?>
    </div>
    <?php } ?>

    <?php if ($it['it_explan']) { // 상품 상세설명 ?>
    
    <div id="sit_inf_explan">
        <?php echo conv_content($it['it_explan'], 1); ?>
    </div>
    <?php } ?>

</section>
<!-- } 상품 정보 끝 -->

<!-- 사용후기 시작 { 
<section id="sit_use">
    <h2>사용후기</h2>
    <?php// echo pg_anchor('use'); ?>

    <div id="itemuse"><?php //include_once('./itemuse.php'); ?></div>
</section>
} 사용후기 끝 -->

<!-- 상품문의 시작 { -->
<section id="sit_qa">
    <h2>이 상품의 자주묻는 질문</h2>
    <?php echo pg_anchor('qa'); ?>
    <div id="itemqa"><?php include_once('./itemqa.php'); ?></div>
</section>
<!-- } 상품문의 끝 -->




<?php if ($default['de_change_content']) { // 교환/반품 내용이 있다면 ?>
<!-- 교환/반품 시작 { -->
<section id="sit_ex">
    <h2>교환/반품</h2>
    <?php echo pg_anchor('ex'); ?>

    <?php echo conv_content($default['de_change_content'], 1); ?>
</section>
<!-- } 교환/반품 끝 -->
<?php } ?>

<?php if ($default['de_rel_list_use']) { ?>
<!-- 관련상품 시작 { -->
<section id="sit_rel">
    <h2>관련상품</h2>
    <?php echo pg_anchor('rel'); ?>

    <div class="sct_wrap">
        <?php
        $rel_skin_file = $skin_dir.'/'.$default['de_rel_list_skin'];
        if(!is_file($rel_skin_file))
            $rel_skin_file = G5_SHOP_SKIN_PATH.'/'.$default['de_rel_list_skin'];

        $sql = " select b.* from {$g5['g5_shop_item_relation_table']} a left join {$g5['g5_shop_item_table']} b on (a.it_id2=b.it_id) where a.it_id = '{$it['it_id']}' and b.it_use='1' ";
        $list = new item_list($rel_skin_file, $default['de_rel_list_mod'], 0, $default['de_rel_img_width'], $default['de_rel_img_height']);
        $list->set_query($sql);
        echo $list->run();
        ?>
    </div>
</section>
<!-- } 관련상품 끝 -->
<?php } ?>




<script>
$(window).on("load", function() {
    $("#sit_inf_explan").viewimageresize2();
});
</script>


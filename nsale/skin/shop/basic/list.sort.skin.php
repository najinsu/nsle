<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

$sct_sort_href = $_SERVER['SCRIPT_NAME'].'?';
if($ca_id)
    $sct_sort_href .= 'ca_id='.$ca_id;
else if($ev_id)
    $sct_sort_href .= 'ev_id='.$ev_id;
if($skin)
    $sct_sort_href .= '&amp;skin='.$skin;
if($it_id)
    $sct_sort_href .= 'it_id='.$it_id;
$sct_sort_href .= '&amp;sort=';

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.G5_SHOP_CSS_URL.'/style.css">', 0);
?>
<!-- 150821 나진수 셀렉트 박스로 변경 영카트 youngcart5.0.42 기준 -->
<!-- 상품 정렬 선택 시작 { -->
<section id="sct_sort">
    <select class="ssch_sort"  name='select' onchange="window.location=this.value">
    	<option value="<?php echo $sct_sort_href; ?>it_sum_qty&amp;sortodr=desc" <?php if($sort=='it_sum_qty'){?> selected="selected" <?php } ?>>판매많은순</option><!--인기순-->
    	<option value="<?php echo $sct_sort_href; ?>it_price&amp;sortodr=asc" <?php if($sort=='it_price' && $sortodr=='asc' ){?> selected="selected" <?php } ?>>낮은가격순</option><!--낮은 가격순-->
    	<option value="<?php echo $sct_sort_href; ?>it_price&amp;sortodr=desc" <?php if($sort=='it_price' && $sortodr=='desc' ){?> selected="selected" <?php } ?>>높은가격순</option><!--높은 가격순-->
    </select >
    
</section>

	
<!-- } 상품 정렬 선택 끝 -->
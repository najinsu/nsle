<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

$str = '';
$exists = false;
// 150821 나진수 category 변경
$depth2_ca_id = substr($ca_id, 0, 2);

$sql = " select ca_id, ca_name from {$g5['g5_shop_category_table']} where ca_id like '${depth2_ca_id}%' and length(ca_id) = 4 and ca_use = '1' order by ca_order, ca_id ";
$result = sql_query($sql);
while ($row=sql_fetch_array($result)) {

    $row2 = sql_fetch(" select count(*) as cnt from {$g5['g5_shop_item_table']} where (ca_id like '{$row['ca_id']}%' or ca_id2 like '{$row['ca_id']}%' or ca_id3 like '{$row['ca_id']}%') and it_use = '1'  ");
    // 150821 나진수 제품이 없으면 상품 노출 안되게 수정
    // 150831 나진수 카테부분 / 부분 깨져서 변경
    if($row2['cnt']>0){
    $bar++;
    $str .= '<li>';
    if($bar>1){
    $str .= '&nbsp&nbsp&nbsp/&nbsp&nbsp&nbsp';
    }
    $str .='<a href="./list.php?ca_id='.$row['ca_id'].'">'.$row['ca_name'].' ('.$row2['cnt'].')</a></li>';
    }
    $exists = true;
}

if ($exists) {

    // add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
    add_stylesheet('<link rel="stylesheet" href="'.G5_SHOP_CSS_URL.'/style.css">', 0);
?>

<!-- 상품분류 1 시작 { -->
<aside id="sct_ct_1" class="sct_ct">
    <h2>현재 상품 분류와 관련된 분류</h2>
    <ul>
        <?php echo $str; ?>
    </ul>
</aside>
<!-- } 상품분류 1 끝 -->

<?php } ?>
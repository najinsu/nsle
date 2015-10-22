<?php

include_once('./_common.php');

$it_id = $_GET['it_id'];
$info = $_GET['info'];

// 분류사용, 상품사용하는 상품의 정보를 얻음
$sql = " select a.*,
                b.ca_name,
                b.ca_use
           from {$g5['g5_shop_item_table']} a,
                {$g5['g5_shop_category_table']} b
          where a.it_id = '$it_id'
            and a.ca_id = b.ca_id ";
$it = sql_fetch($sql);
if (!$it['it_id'])
    alert('자료가 없습니다.');
if (!($it['ca_use'] && $it['it_use'])) {
    if (!$is_admin)
        alert('판매가능한 상품이 아닙니다.');
}

// 분류 테이블에서 분류 상단, 하단 코드를 얻음
$sql = " select ca_mobile_skin_dir, ca_include_head, ca_include_tail, ca_cert_use, ca_adult_use
           from {$g5['g5_shop_category_table']}
          where ca_id = '{$it['ca_id']}' ";
$ca = sql_fetch($sql);


$g5['title'] = $it['it_name'].' &gt; '.$it['ca_name'];
include_once(G5_PATH.'/head.sub.php');

// 관리자가 확인한 사용후기의 개수를 얻음
$sql = " select count(*) as cnt from `{$g5['g5_shop_item_use_table']}` where it_id = '{$it_id}' and is_confirm = '1' ";
$row = sql_fetch($sql);
$item_use_count = $row['cnt'];

// 상품문의의 개수를 얻음
$sql = " select count(*) as cnt from `{$g5['g5_shop_item_qa_table']}` where it_id = '{$it_id}' ";
$row = sql_fetch($sql);
$item_qa_count = $row['cnt'];

function pg_anchor($info) {
    global $default;
    global $it_id, $item_use_count, $item_qa_count, $item_relation_count;

?>
    
<?php
}
?>


<div id="info_content" class="new_win">
<?php
switch($info) {
    case 'use':
        include_once(G5_MSHOP_SKIN_PATH.'/iteminfo.itemuse.skin.php');
        break;
    case 'qa':
        include_once(G5_MSHOP_SKIN_PATH.'/iteminfo.itemqa.skin.php');
        break;
    case 'dvr':
        include_once(G5_MSHOP_SKIN_PATH.'/iteminfo.delivery.skin.php');
        break;
    case 'ex':
        include_once(G5_MSHOP_SKIN_PATH.'/iteminfo.change.skin.php');
        break;
    default:
        include_once(G5_MSHOP_SKIN_PATH.'/iteminfo.info.skin.php');
        break;
}
?>
</div>
<div class="close_btn"><button type="button" id="iteminfo_close" onclick="self.close();">창닫기</button></div>

<?php
include_once(G5_PATH.'/tail.sub.php');
?>
<?php
include_once('./_common.php');

if (G5_IS_MOBILE) {
    include_once(G5_MSHOP_PATH.'/orderinquiry.php');
    return;
}

define("_ORDERINQUIRY_", true);
// 150811 나진수 주문상세 세션 오류로 인한 세션 추가
$ss_od_name = get_session(ss_od_name);
$ss_od_hp = get_session(ss_od_hp);



// 회원인 경우
if ($is_member)
{
    $sql_common = " from {$g5['g5_shop_order_table']} where mb_id = '{$member['mb_id']}' ";
}
/*
150811 나진수 멤버와 비회원 리스트 중복으로 인한 mb_id='' and 추가
 * else if ($od_name && $od_hp) // 비회원인 경우 주문자이름과 휴대폰번호 와 비밀번호가 넘어왔다면
{
    $sql_common = " from {$g5['g5_shop_order_table']} where od_name = '$od_name' and od_hp='$od_hp' ";
}
 *  */
else if ($ss_od_name && $ss_od_hp) // 비회원인 경우 주문서번호와 비밀번호가 넘어왔다면
{
    $sql_common = " from {$g5['g5_shop_order_table']} where mb_id='' and od_name = '$ss_od_name' and od_hp='$ss_od_hp' ";
}
else // 그렇지 않다면 로그인으로 가기
{
    goto_url(G5_BBS_URL.'/login.php?url='.urlencode(G5_SHOP_URL.'/orderinquiry.php'));
}

// 테이블의 전체 레코드수만 얻음
$sql = " select count(*) as cnt " . $sql_common;
$row = sql_fetch($sql);
$total_count = $row['cnt'];

// 비회원 주문확인시 비회원의 모든 주문이 다 출력되는 오류 수정
// 조건에 맞는 주문서가 없다면


$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함




$g5['title'] = '주문내역조회';
include_once('./_head.php');
?>

<!-- 주문 내역 시작 { -->
<div id="sod_v">
    <p id="sod_v_info">주문서번호 링크를 누르시면 주문상세내역을 조회하실 수 있습니다.</p>

    <?php
    $limit = " limit $from_record, $rows ";
    include "./orderinquiry.sub.php";
    ?>

    <?php echo get_paging($config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr&amp;page="); ?>
</div>
<!-- } 주문 내역 끝 -->

<?php
include_once('./_tail.php');
?>

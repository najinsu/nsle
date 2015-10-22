<?php
include_once('./_common.php');
//150903 나진수 가재고 수량으로 인한 주문하기 폼을 제외한 나머지 부분에서 구매하기 ct_select를 0으로 변경함 시작 로그아웃시 세션 만료로 select 가 지워지지 않아 세션 지우기 전에 실행 shop.head.php 부분 과동일시 사용
$ct_select_id=get_session('ss_cart_direct');
if ( !preg_match("/(orderformupdate.php)/", $_SERVER['REQUEST_URI'])&&!preg_match("/(orderform.php)/", $_SERVER['REQUEST_URI'])&& !preg_match("/(cartupdate.php)/", $_SERVER['REQUEST_URI']) && !preg_match("/(orderform.php)/", $_SERVER['REQUEST_URI'])){
$sql = " update {$g5['g5_shop_cart_table']} set ct_select = '0' where ct_select='1' and  od_id='$ct_select_id'";
sql_query($sql);
}
//150903 나진수 가재고 수량으로 인한 주문하기 폼을 제외한 나머지 부분에서 구매하기 ct_select를 0으로 변경함 끝 로그아웃시 세션 만료로 select 가 지워지지 않아 세션 지우기 전에 실행 shop.head.php 부분 과동일시 사용
//
// 이호경님 제안 코드
session_unset(); // 모든 세션변수를 언레지스터 시켜줌
session_destroy(); // 세션해제함

// 자동로그인 해제 --------------------------------
set_cookie('ck_mb_id', '', 0);
set_cookie('ck_auto', '', 0);
// 자동로그인 해제 end --------------------------------

if ($url) {
    $p = parse_url($url);
    if ($p['scheme'] || $p['host']) {
        alert('url에 도메인을 지정할 수 없습니다.');
    }

    if($url == 'shop')
        $link = G5_SHOP_URL;
    else
        $link = $url;
} else if ($bo_table) {
    $link = G5_BBS_URL.'/board.php?bo_table='.$bo_table;
} else {
    $link = G5_URL;
}

goto_url($link);
?>

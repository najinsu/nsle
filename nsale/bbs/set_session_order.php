<?php
include_once('./_common.php');
set_session('ss_od_name', $od_name);
set_session('ss_od_hp', $od_hp);
if(G5_IS_MOBILE){
    goto_url(G5_MSHOP_URL."/orderinquiry.php");
}else{
goto_url(G5_SHOP_URL."/orderinquiry.php");
}
// 150811 나진수 set_session_order.php 파일 추가 (비회원 문의하기 부분추가)
?>

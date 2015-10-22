<?php
include_once('/var/www/html/nsale/common.php');
    include_once('/var/www/html/nsale/lib/common.lib.php');    // 공통 라이브러리
// 150903 나진수 모바일에서 홈버튼 시 또는 뒤로가기 페이지가 살아있는 상태에서 db가 지워지지 않기 때문에 크론으로 인한 삭제 
// 또한 orderform.php부분에서 새로고침 시간이 같이 연동되어서 사용됨    
 $sql_cron="update {$g5['g5_shop_cart_table']} set ct_select = '0' where ct_select='1' and  ct_select_time < DATE_SUB(now(), INTERVAL 25 MINUTE) and ct_select_chk ='0' and ct_status='쇼핑' ";
        sql_query($sql_cron);
        
?>
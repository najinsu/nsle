<?php
if (!defined("_GNUBOARD_"))
    exit; // 개별 페이지 접근 불가

if (!defined("_ORDERINQUIRY_"))
    exit; // 개별 페이지 접근 불가
?>

<?php if (!$limit) { ?>총 <?php echo $cnt; ?> 건<?php } ?>


<div id="sod_inquiry">
    <ul>
        <?php
        if ($is_member) {
            $sql = " select *,
                    (od_cart_coupon + od_coupon + od_send_coupon) as couponprice
                   from {$g5['g5_shop_order_table']}
                  where mb_id = '{$member['mb_id']}'
                  order by od_id desc
                  $limit ";
        } else {
            // 150811 나진수 멤버와 비회원 정보 값 구분자없어서 오류 mb_id='' and od_name = '$ss_od_name' and od_hp='$ss_od_hp' 이부분 추가
            $sql = " select *,
                    (od_cart_coupon + od_coupon + od_send_coupon) as couponprice
                   from {$g5['g5_shop_order_table']}
                  where mb_id='' and od_name = '$ss_od_name' and od_hp='$ss_od_hp'
                  order by od_id desc
                  $limit ";
        }

        $result = sql_query($sql);
        for ($i = 0; $row = sql_fetch_array($result); $i++) {
            // 주문상품
            $sql = " select it_name, ct_option, ct_qty
                        from {$g5['g5_shop_cart_table']}
                        where od_id = '{$row['od_id']}'
                        order by io_type, ct_id
                        limit 1 ";
            $ct = sql_fetch($sql);
            
            $sql_ct = " select count(*) as cnt
                        from {$g5['g5_shop_cart_table']}
                        where od_id = '{$row['od_id']}' ";
            $ct2 = sql_fetch($sql_ct);
            if ($ct2['cnt'] == 1) {
                $ct_op = '<div id="od_op">' .get_text($ct['ct_option']).'<span>수량 : '.$ct['ct_qty'].'개</span></div>';
            }else{
                $ct_op="";
                $sql_op = " select *
                        from {$g5['g5_shop_cart_table']}
                        where od_id = '{$row['od_id']}' ";
                $result_op = sql_query($sql_op);

                for ($z = 0; $row_op = sql_fetch_array($result_op); $z++) {
                    $ct_op .= '<div id="od_op">' . $row_op['ct_option'] . '<span>수량 : ' . $row_op['ct_qty'] . ' 개</span></div>';
                }
            }$ct_name = preg_replace("<br/>", "/\'/", $ct['it_name']).'<br />'.$ct_op;
            
            

            
            switch ($row['od_status']) {
                case '주문':
                    $od_status = '입금확인중';
                    break;
                case '입금':
                    $od_status = '입금완료';
                    break;
                case '준비':
                    $od_status = '상품준비중';
                    break;
                case '배송':
                    $od_status = '배송중';	// 151006 나진수 상품배송이라는 단어가 단어 혼돈으로 인한 배송중으로 변경 요청으로 변경 /상품배송/->배송중
                    break;
                case '완료':
                    $od_status = '배송완료';
                    break;
                default:
                    $od_status = '주문취소';
                    break;
            }

            $od_invoice = '';
            if ($row['od_delivery_company'] && $row['od_invoice'])
                $od_invoice = get_text($row['od_delivery_company']) . ' ' . get_text($row['od_invoice']);

            $uid = md5($row['od_id'] . $row['od_name'] . $row['od_hp']);
            ?>

            <li>
                <div class="inquiry_idtime">
                    주문서번호 : <a href="<?php echo G5_SHOP_URL; ?>/orderinquiryview.php?od_id=<?php echo $row['od_id']; ?>&amp;uid=<?php echo $uid; ?>" class="idtime_link"><?php echo $row['od_id']; ?></a>
                    <span class="idtime_time">주문일자 <?php echo substr($row['od_time'], 2, 8); ?></span>
                </div>
                <div class="inquiry_name">
                    <?php echo $ct_name; ?>
                </div>
                <div class="inquiry_price">
                    <?php echo display_price($row['od_cart_price'] + $row['od_send_cost'] + $row['od_send_cost2']); ?><span>/ <?php echo $row['od_settle_case']; ?></span>
                </div>
                <div class="inquiry_inv">
                    <span class="inv_status" <?php if($od_status == '주문취소'){ ?>style="color:#ff455b;"<?php } ?>><?php echo $od_status; ?></span>
                </div>
            </li>

            <?php
        }

        if ($i == 0)
            echo '<li class="empty_list">주문 내역이 없습니다.</li>';
        ?>
    </ul>
</div>












<?php
$sub_menu = '500110';
include_once('./_common.php');
/*
 * 20150817 나진수 매출 현황 프로그램 변형 영카트 5.0.42 기준에서 변형됨
 */
auth_check($auth[$sub_menu], "r");

$g5['title'] = $fr_year . ' ~ ' . $to_year . ' 연간 매출현황';
include_once (G5_ADMIN_PATH . '/admin.head.php');

function print_line($save) {
    $save_item_price = $save['receiptvbank'] + $save['receiptcard'] + $save['receipthp'];
    $hp_commission = $save['receipthp'] * 0.032;
    $card_commission = $save['receiptcard'] * 0.029;
    $vbank_commission = $save['vbankcount'] * 225;
    $save_commission = $vbank_commission + $card_commission + $hp_commission;
    $save_real_price = $save_item_price - $save['ordercancel'] - $save['od_send_cost'] - $save_commission;
    ?>
    <tr>
        <td class="td_alignc"><a href="./sale1month.php?fr_month=<?php echo $save['od_date']; ?>01&amp;to_month=<?php echo $save['od_date']; ?>12"><?php echo $save['od_date']; ?></a></td>     
        <td class="td_num"><?php echo number_format($save['ordercount']); ?></td>
        <td class="td_numsum"><?php echo number_format($save['orderprice']); ?></td>
        <!--<td class="td_numcoupon"><?php //echo number_format($save['ordercoupon']);    ?></td>
        <td class="td_numincome"><?php //echo number_format($save['receiptbank']);    ?></td>-->
        <td class="td_numrdy"><?php echo number_format($save['misu']); ?></td>
        <td class="td_numincome"><?php echo number_format($save['receiptvbank']); ?></td>
        <!--<td class="td_numincome"><?php //echo number_format($save['receiptiche']);    ?></td>-->
        <td class="td_numincome"><?php echo number_format($save['receiptcard']); ?></td>
        <td class="td_numincome"><?php echo number_format($save['receipthp']); ?></td>
        <td class="td_num_prcie"><?php echo number_format($save_item_price); ?></td>
        <!--<td class="td_numincome"><?php //echo number_format($save['receiptpoint']);    ?></td>-->
        <td class="td_numcancel1"><?php echo number_format($save['ordercancel']); ?></td>
        <td class="td_numcancel1"><?php echo number_format($save['od_send_cost']); ?></td>
        <td class="td_numcancel1"><?php echo number_format($save_commission); ?></td>
        <td class="td_num_prcie"><?php echo number_format($save_real_price); ?></td>
    </tr>
    <?php
}

$sql = " select od_id,
                SUBSTRING(od_time,1,4) as od_date,
                od_status,
                od_send_cost,
                od_settle_case,
                od_receipt_price,
                od_receipt_point,
                od_cart_price,
                od_cancel_price,
                od_misu,
                (od_cart_price + od_send_cost + od_send_cost2) as orderprice,
                (od_cart_coupon + od_coupon + od_send_coupon) as couponprice
           from {$g5['g5_shop_order_table']}
          where SUBSTRING(od_time,1,4) between '$fr_year' and '$to_year'
          order by od_time desc ";
$result = sql_query($sql);
?>

<div class="tbl_head01 tbl_wrap">
    <table>
        <caption><?php echo $g5['title']; ?></caption>
        <thead>
            <tr>
                <th scope="col">주문년도</th>
                <th scope="col">주문수</th>
                <th scope="col">주문합계</th>
                <!--<th scope="col">쿠폰</th>
                <th scope="col">무통장</th>-->
                <th scope="col">미수금</th>
                <th scope="col">가상계좌</th>
                <!--<th scope="col">계좌이체</th>-->
                <th scope="col">카드입금</th>
                <th scope="col">휴대폰</th>
                <th scope="col">합산결제금액</th>
                <!--<th scope="col">포인트입금</th>-->
                <th scope="col">주문취소</th>
                <th scope="col">배송금액</th>
                <th scope="col">PG수수료</th>
                <th scope="col">순매출액</th>
            </tr>
        </thead>
        <tbody>
            <?php
            unset($save);
            unset($tot);
            for ($i = 0; $row = sql_fetch_array($result); $i++) {
                if ($i == 0)
                    $save['od_date'] = $row['od_date'];

                if ($save['od_date'] != $row['od_date']) {
                    print_line($save);
                    unset($save);
                    $save['od_date'] = $row['od_date'];
                }
                if ($row['od_status'] != '주문' && $row['od_settle_case'] == '가상계좌') {
                    $save['vbankcount'] ++;
                    $tot['vbankcount'] ++;
                }
                $save['ordercount'] ++;
                $save['orderprice'] += $row['orderprice'];
                $save['ordercancel'] += $row['od_cancel_price'];
                //$save['ordercoupon'] += $row['couponprice'];
                //if ($row['od_settle_case'] == '무통장')
                //    $save['receiptbank'] += $row['od_receipt_price'];
                if ($row['od_settle_case'] == '가상계좌')
                    $save['receiptvbank'] += $row['od_receipt_price'];
                //if ($row['od_settle_case'] == '계좌이체')
                //    $save['receiptiche'] += $row['od_receipt_price'];
                if ($row['od_settle_case'] == '휴대폰')
                    $save['receipthp'] += $row['od_receipt_price'];
                if ($row['od_settle_case'] == '신용카드')
                    $save['receiptcard'] += $row['od_receipt_price'];
                //$save['receiptpoint'] += $row['od_receipt_point'];
                $save['misu'] += $row['od_misu'];
                
                //150901 나진수 배송시 배송비 카운트
                if($row['od_status']=='배송'){
                    $save['od_send_cost'] += $row['od_send_cost'];
                }
                
                $tot['ordercount'] ++;
                $tot['orderprice'] += $row['orderprice'];
                $tot['ordercancel'] += $row['od_cancel_price'];
                //$tot['ordercoupon'] += $row['couponprice'];
                //if ($row['od_settle_case'] == '무통장')
                //    $tot['receiptbank'] += $row['od_receipt_price'];
                if ($row['od_settle_case'] == '가상계좌')
                    $tot['receiptvbank'] += $row['od_receipt_price'];
                //if ($row['od_settle_case'] == '계좌이체')
                //    $tot['receiptiche'] += $row['od_receipt_price'];
                if ($row['od_settle_case'] == '휴대폰')
                    $tot['receipthp'] += $row['od_receipt_price'];
                if ($row['od_settle_case'] == '신용카드')
                    $tot['receiptcard'] += $row['od_receipt_price'];
                //$tot['receiptpoint'] += $row['od_receipt_point'];
                $tot['misu'] += $row['od_misu'];
                //150901 나진수 배송시 배송비 카운트
                if($row['od_status']=='배송'){
                    $tot['od_send_cost'] += $row['od_send_cost'];
                }
            }

            if ($i == 0) {
                echo '<tr><td colspan="12" class="empty_table">자료가 없습니다.</td></tr>';
            } else {
                print_line($save);
            }
            $tot_hp_commission = $tot['receipthp'] * 0.032;
            $tot_card_commission = $tot['receiptcard'] * 0.029;
            $tot_vbank_commission = $tot['vbankcount'] * 225;
            $tot_item_price = $tot['receiptvbank'] + $tot['receipthp'] + $tot['receiptcard'];
            $tot_commission = $tot_vbank_commission + $tot_card_commission + $tot_hp_commission;

            $tot_real_price = $tot_item_price - $tot['ordercancel'] - $tot['od_send_cost'] - $tot_commission;
            ?>
        </tbody>
        <tfoot>
            <tr>
                <td>합 계</td>
                <td><?php echo number_format($tot['ordercount']); ?></td>
                <td><?php echo number_format($tot['orderprice']); ?></td>
               <!-- <td><?php // echo number_format($tot['ordercoupon']);   ?></td>
                <td><?php //echo number_format($tot['receiptbank']);   ?></td>-->
                <td><?php echo number_format($tot['misu']); ?></td>
                <td><?php echo number_format($tot['receiptvbank']); ?></td>
                <!--<td><?php //echo number_format($tot['receiptiche']);   ?></td>-->
                <td><?php echo number_format($tot['receiptcard']); ?></td>
                <td><?php echo number_format($tot['receipthp']); ?></td>
                <td><?php echo number_format($tot_item_price); ?></td>
                <!--<td><?php //echo number_format($tot['receiptpoint']);   ?></td>-->
                <td><?php echo number_format($tot['ordercancel']); ?></td>
                <td><?php echo number_format($tot['od_send_cost']); ?></td>
                <td><?php echo number_format($tot_commission); ?></td>
                <td><?php echo number_format($tot_real_price); ?></td>
            </tr>
        </tfoot>
    </table>
</div>

<?php
include_once (G5_ADMIN_PATH . '/admin.tail.php');
?>

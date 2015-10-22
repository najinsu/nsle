<?php
if (!defined("_GNUBOARD_"))
    exit; // 개별 페이지 접근 불가

if (!defined("_ORDERINQUIRY_"))
    exit; // 개별 페이지 접근 불가
?>

<!-- 주문 내역 목록 시작 { -->
<?php if (!$limit) { ?>총 <?php echo $cnt; ?> 건<?php } ?>

<div class="tbl_head01 tbl_wrap">
    <table>
        <thead>
            <tr>
                <th scope="col">주문서번호</th>
                <th scope="col">주문일시</th>
                <th scope="col">주문상품수</th>
                <th scope="col">결제방식</th>
                <th scope="col">결제금액</th>
                <th scope="col">미입금액</th>
                <th scope="col">상태</th>
            </tr>
        </thead>
        <tbody>
            <?php
            
            if ($is_member) {
                $sql = " select *,
                    (od_cart_coupon + od_coupon + od_send_coupon) as couponprice
                   from {$g5['g5_shop_order_table']}
                  where mb_id = '{$member['mb_id']}'
                  order by od_id desc
                  $limit ";
            } else {

                $sql = " select *,
                    (od_cart_coupon + od_coupon + od_send_coupon) as couponprice
                   from {$g5['g5_shop_order_table']}
                  where mb_id='' and od_name = '$ss_od_name' and od_hp='$ss_od_hp'
                  order by od_id desc
                  $limit ";
            }
            // 150811 나진수 get세션 값 수정 / 멤버와 비회원 중복 값으로 인한 mb_id 부분 추가  , 데이터 리스트 기분이 od_name였는데 od_id로 변경
            // where od_name = '{$_POST['od_name']}' and od_hp='{$_POST['od_hp']}' order by od_name desc
            $result = sql_query($sql);
            for ($i = 0; $row = sql_fetch_array($result); $i++) {
                
                 $sql = " select SUM(ct_qty) as qty
                        from {$g5['g5_shop_cart_table']}
                        where  od_id = '{$row['od_id']}' ";
    $sum = sql_fetch($sql);
                $uid = md5($row['od_id'] . $row['od_time'] . $row['od_ip']);
				
				//151006 나진수 배송단계
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
                ?>

                <tr>
                    <td>
                        <input type="hidden" name="ct_id[<?php echo $i; ?>]" value="<?php echo $row['ct_id']; ?>">
                        <a href="<?php echo G5_SHOP_URL; ?>/orderinquiryview.php?od_id=<?php echo $row['od_id']; ?>&amp;uid=<?php echo $uid; ?>" class="od_id_num"><?php echo $row['od_id']; ?></a>
                    </td>
                    <td><?php echo substr($row['od_time'], 2, 14); ?> (<?php echo get_yoil($row['od_time']); ?>)</td>
                    <td class="td_num"><?php echo $sum['qty']; ?></td>
                    <td class="td_numbig"><?php echo $row['od_settle_case']; ?></td>
                    <td class="td_numbig"><?php echo display_price($row['od_cart_price'] + $row['od_send_cost'] + $row['od_send_cost2']); ?></td>
                    <td class="td_numbig"><?php echo display_price($row['od_misu']); ?></td>
                    <td  <?php if($od_status == '주문취소'){ ?>style="color:#ff455b;"<?php } ?>><?php echo $od_status; ?></td>
                </tr>

                <?php
            }

            if ($i == 0)
                echo '<tr><td colspan="7" class="empty_table">주문 내역이 없습니다.</td></tr>';
            ?>
        </tbody>
    </table>
</div>
<!-- } 주문 내역 목록 끝 -->
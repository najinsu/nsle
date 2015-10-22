<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가
?>

<form name="sm_form" method="POST" action="" accept-charset="euc-kr">
<input type="hidden" name="P_OID"        value="<?php echo $od_id; ?>">
<input type="hidden" name="P_GOODS"      value="<?php echo $goods; ?>">
<input type="hidden" name="P_AMT"        value="<?php echo $tot_price; ?>">
<input type="hidden" name="P_UNAME"      value="">
<input type="hidden" name="P_MOBILE"     value="">
<input type="hidden" name="P_EMAIL"      value="">
<!-- 150831 나진수 입금 기한 날짜 추가 시작-->
<?php  $m_deposit_after= date("Ymd ", G5_SERVER_TIME+(60*60*24*7)); ?>
<input type="hidden" name="P_VBANK_DT"   value="<?php echo $m_deposit_after;?>">
<!-- 150831 나진수 입금 기한 날짜 추가 끝-->
<input type="hidden" name="P_MID"        value="<?php echo $default['de_inicis_mid']; ?>">
<input type="hidden" name="P_NEXT_URL"   value="<?php echo $next_url; ?>">
<input type="hidden" name="P_NOTI_URL"   value="<?php echo $noti_url; ?>">
<input type="hidden" name="P_RETURN_URL" value="">
<input type="hidden" name="P_HPP_METHOD" value="2">
<!-- 150828 나진수 현금영수증 퍼블릿 부분 추가 활성화 vbank_receipt=Y 추가 -->
<input type="hidden" name="P_RESERVED"   value="bank_receipt=N&vbank_receipt=Y&twotrs_isp=Y&block_isp=Y<?php echo $useescrow; ?>">
<input type="hidden" name="P_NOTI"       value="<?php echo $od_id; ?>">
<input type="hidden" name="P_QUOTABASE"  value="01:02:03:04:05:06:07:08:09:10:11:12"> <!-- 150815 나진수 영카트 5 패치 5.0.43  버전 업데이트 부분 추가 소스 (할부기간 설정 01은 일시불) -->

<input type="hidden" name="good_mny"     value="<?php echo $tot_price; ?>" >

<?php if($default['de_tax_flag_use']) { ?>
<input type="hidden" name="P_TAX"        value="">
<input type="hidden" name="P_TAXFREE"    value="">
<?php } ?>
</form>
<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

include_once(G5_MSHOP_PATH.'/settle_inicis.inc.php');

// 세션비교
$hash = md5(get_session('P_TID').$default['de_inicis_mid'].get_session('P_AMT'));
if($hash != $_POST['P_HASH'])
    alert('결제 정보가 일치하지 않습니다. 올바른 방법으로 이용해 주십시오.');

//최종결제요청 결과 성공 DB처리
$tno             = get_session('P_TID');
$amount          = get_session('P_AMT');
$app_time        = $_POST['P_AUTH_DT'];
$pay_method      = $_POST['P_TYPE'];
$pay_type        = $PAY_METHOD[$pay_method];
$depositor       = $_POST['P_UNAME'];
$commid          = $_POST['P_HPP_CORP'];
$mobile_no       = $_POST['P_APPL_NUM'];
$app_no          = $_POST['P_AUTH_NO'];
$card_name       = $_POST['P_CARD_ISSUER'];
if ($default['de_escrow_use'] == 1)
    $escw_yn         = 'Y';
switch($pay_type) {
    case '계좌이체':
        $bank_name = $_POST['P_VACT_BANK'];
        break;
    case '가상계좌':
        $bankname  = $_POST['P_VACT_BANK'];
        $account   = $_POST['P_VACT_NUM'].' '.$_POST['P_VACT_NAME'];
        $app_no    = $_POST['P_VACT_NUM'];
        break;
    default:
        break;
}


//20151013 나진수 임의 로그 생성 settle_common.php 소스 부분 변형함 시작

$PageCall_time = date("H:i:s");

    $value = array(
                "PageCall time" => $PageCall_time,
                "P_TID"		=> $tno,
                "P_MID"         => $default['de_inicis_mid'],
                "P_AUTH_DT"     => $app_time,
                "P_TYPE"        => $pay_method,
                "P_OID"         => get_session('ss_order_id'),
                "P_AMT"         => $amount,
                "P_UNAME"       => iconv_euckr($P_UNAME),
                "P_RMESG1"      => $P_RMESG1,
                "P_RMESG2"      => $P_RMESG2,
                "P_NOTI"        => $P_NOTI,
                "P_AUTH_NO"     => $P_AUTH_NO,
                "P_SRC_CODE"    => $P_SRC_CODE
            );
    
    

  
writeLog($value);


function writeLog($msg)
{
    $file = G5_SHOP_PATH."/inicis/log/noti_input_".date("Ymd").".log";

    if(!($fp = fopen($path.$file, "a+"))) return 0;

    ob_start();
    print_r($msg);
    $ob_msg = ob_get_contents();
    ob_clean();

    if(fwrite($fp, " ".$ob_msg."\n") === FALSE)
    {
        fclose($fp);
        return 0;
    }
    fclose($fp);
    return 1;
}
//20151013 나진수 임의 로그 생성 settle_common.php 소스 부분 변형함 끝

// 세션 초기화
set_session('P_TID',  '');
set_session('P_AMT',  '');
set_session('P_HASH', '');
?>
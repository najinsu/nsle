<?php

include_once('./_common.php');
include_once(G5_CAPTCHA_PATH . '/captcha.lib.php');
include_once(G5_LIB_PATH . '/register.lib.php');

if ($is_member) {
    alert_close('이미 로그인중입니다.');
}


$mb_id = trim($_POST['mb_id']);
$mb_name = trim($_POST['mb_name']);
$mb_hp = trim($_POST['mb_hp']);

if (!$mb_id)
    alert_close('아이디 오류입니다.');
if (!$mb_name)
    alert_close('사용자이름 오류입니다.');
if (!$mb_hp)
    alert_close('핸드폰 번호 오류입니다.');


$sql = " select count(*) as cnt from {$g5['member_table']} where mb_hp = '$mb_hp' ";
$row = sql_fetch($sql);
if ($row['cnt'] > 1)
    alert('동일한 핸드폰 번호가 2개 이상 존재합니다.\\n\\n070-4693-5216 문의전화 주시기 바랍니다.');

$sql = " select mb_no, mb_id, mb_name, mb_hp, mb_datetime from {$g5['member_table']} where mb_id ='$mb_id' and mb_name='$mb_name' and mb_hp = '$mb_hp' ";
$mb = sql_fetch($sql);
if (!$mb['mb_id'])
    alert('존재하지 않는 회원입니다.');
else if (is_admin($mb['mb_id']))
    alert('관리자 아이디는 접근 불가합니다.');

// 임시비밀번호 발급
$change_password = rand(100000, 999999);
$mb_lost_certify = get_encrypt_string($change_password);


// 임시비밀번호와 난수를 mb_lost_certify 필드에 저장
$sql = " update {$g5['member_table']} set mb_password = '$mb_lost_certify' where mb_id = '{$mb['mb_id']}' ";
sql_query($sql);

//----------------------------------------------------------
// SMS 문자전송 시작
//----------------------------------------------------------

$sms_contents = $default['de_sms_cont6'];
$sms_contents = str_replace("{이름}", $mb_name, $sms_contents);
$sms_contents = str_replace("{비밀번호}", $change_password, $sms_contents);
$sms_contents = str_replace("{회사명}", $default['de_admin_company_name'], $sms_contents);

// 핸드폰번호에서 숫자만 취한다
$receive_number = preg_replace("/[^0-9]/", "", $mb_hp);  // 수신자번호 (회원님의 핸드폰번호)
$send_number = preg_replace("/[^0-9]/", "", $default['de_admin_company_tel']); // 발신자번호

if ($w == "" && $default['de_sms_use6'] && $receive_number) {
    if ($config['cf_sms_use'] == 'icode') {
        include_once(G5_LIB_PATH . '/icode.sms.lib.php');

        $SMS = new SMS; // SMS 연결
        $SMS->SMS_con($config['cf_icode_server_ip'], $config['cf_icode_id'], $config['cf_icode_pw'], $config['cf_icode_server_port']);
        $SMS->Add($receive_number, $send_number, $config['cf_icode_id'], iconv("utf-8", "euc-kr", stripslashes($sms_contents)), "");
        $SMS->Send();
    }
}
//----------------------------------------------------------
// SMS 문자전송 끝
//----------------------------------------------------------

alert_close($mb_hp . '번호로 임시비밀번호가 발송 되었습니다.\\n\\n핸드폰을 확인하여 주십시오.');
?>
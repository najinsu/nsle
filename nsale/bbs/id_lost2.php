<?php
include_once('./_common.php');
include_once(G5_CAPTCHA_PATH.'/captcha.lib.php');

if ($is_member) {
    alert('이미 로그인중입니다.');
}


$mb_hp = trim($_POST['mb_hp']);
$mb_name = trim($_POST['mb_name']);
if (!$mb_hp)
    alert_close('핸드폰 번호 오류입니다.');

if (!$mb_name)
    alert_close('이름 오류입니다.');

$sql = " select count(*) as cnt from {$g5['member_table']} where mb_hp = '$mb_hp' ";
$row = sql_fetch($sql);
if ($row['cnt'] > 1)
    alert('동일한 핸드폰번호가 2개 이상 존재합니다.\\n\\n070-4693-5216 문의전화 주시기 바랍니다.');

$sql = " select mb_no, mb_id, mb_name, mb_email, mb_hp, mb_datetime from {$g5['member_table']} where mb_name ='$mb_name' and mb_hp = '$mb_hp' ";
$mb = sql_fetch($sql);

if (!$mb['mb_name'])
    alert('존재하지 않는 회원입니다.');
else if (is_admin($mb['mb_name']))
    alert('관리자는 접근 불가합니다.');

$is_mb_id = substr($mb['mb_id'], 0, -2);
if(($mb['mb_name']==$mb_name)&&($mb['mb_hp']==$mb_hp))
    alert_close('회원님의 아이디는 '.$is_mb_id.'** 입니다.\\n\\n');
?>

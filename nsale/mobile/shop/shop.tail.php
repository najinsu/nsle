<?php
if (!defined("_GNUBOARD_"))
    exit; // 개별 페이지 접근 불가

$admin = get_admin("super");

// 사용자 화면 우측과 하단을 담당하는 페이지입니다.
// 우측, 하단 화면을 꾸미려면 이 파일을 수정합니다.
?>

</div><!-- container End -->

<div id="ft">
    <?php
    // 150813 나진수 pc버전 보기 부분 관리자 권한있는사람볼수있게 추가 수정
    $is_auth = false;
                    $sql = " select count(*) as cnt from {$g5['auth_table']} where mb_id = '{$member['mb_id']}' ";
                    $row = sql_fetch($sql);
                    if ($row['cnt'])
                        $is_auth = true;
    if ($is_admin || $is_auth) {
        if (G5_DEVICE_BUTTON_DISPLAY && G5_IS_MOBILE) {
            ?>
            <a href="<?php echo get_device_change_url(); ?>" id="device_change">PC 버전으로 보기</a>
            <?php
        }
    }
    ?>
    <ul>
            <!--<li><a href="<?php echo G5_BBS_URL; ?>/content.php?co_id=company">회사소개</a></li>-->
        <li><a href="<?php echo G5_BBS_URL; ?>/content.php?co_id=provision" >서비스이용약관</a></li>
        <li><a href="<?php echo G5_BBS_URL; ?>/content.php?co_id=privacy">개인정보 취급방침</a></li>
    </ul>
    <p>
        <span><b>회사명</b> <?php echo $default['de_admin_company_name']; ?></span>
        <span><b>대표</b> <?php echo $default['de_admin_company_owner']; ?></span><br>

        <span><b>주소</b> <?php echo $default['de_admin_company_addr']; ?></span><br>
        <span><b>사업자 등록번호</b> <?php echo $default['de_admin_company_saupja_no']; ?></span><br>
        <span><b>전화</b> <?php echo $default['de_admin_company_tel']; ?></span>&nbsp;&nbsp;
        <span><b>팩스</b> <?php echo $default['de_admin_company_fax']; ?></span><br>
        <!-- <span><b>운영자</b> <?php echo $admin['mb_name']; ?></span><br> -->
        <span><b>통신판매업신고번호</b> <?php echo $default['de_admin_tongsin_no']; ?></span>&nbsp;&nbsp;
        <span><b>개인정보관리책임자</b> <?php echo $default['de_admin_info_name']; ?></span><br>
<?php if ($default['de_admin_buga_no']) echo '<span><b>부가통신사업신고번호</b> ' . $default['de_admin_buga_no'] . '</span>'; ?>
        Copyright &copy; 2015 <?php echo $default['de_admin_company_name']; ?>. All Rights Reserved.
    </p>

</div>

<?php
$sec = get_microtime() - $begin_time;
$file = $_SERVER['SCRIPT_NAME'];
?>
<?php
$is_auth = false;
$sql = " select count(*) as cnt from {$g5['auth_table']} where mb_id = '{$member['mb_id']}' ";
$row = sql_fetch($sql);
if ($row['cnt'])
    $is_auth = true;
//if ($is_admin || $is_auth) {
//}
if ($config['cf_analytics']) {
    echo $config['cf_analytics'];
}
?>

<script src="<?php echo G5_JS_URL; ?>/sns.js"></script>

<?php
include_once(G5_PATH . '/tail.sub.php');
?>

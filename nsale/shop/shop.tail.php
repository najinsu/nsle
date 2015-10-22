<?php
if (!defined("_GNUBOARD_"))
    exit; // 개별 페이지 접근 불가

    
// 하단 파일 지정 : 이 코드는 가능한 삭제하지 마십시오.
if ($default['de_include_tail'] && is_file(G5_SHOP_PATH . '/' . $default['de_include_tail'])) {
    include_once(G5_SHOP_PATH . '/' . $default['de_include_tail']);
    return; // 이 코드의 아래는 실행을 하지 않습니다.
}

$admin = get_admin("super");

// 사용자 화면 우측과 하단을 담당하는 페이지입니다.
// 우측, 하단 화면을 꾸미려면 이 파일을 수정합니다.
?>
</div>
</div>
<!-- } 콘텐츠 끝 -->

<!-- 하단 시작 { -->
</div>
<div id="ft" >
    <div class="ft_ul_bg"></div>
    <?php
    if ($is_admin || $is_auth) {
        if (G5_DEVICE_BUTTON_DISPLAY && !G5_IS_MOBILE) {
            ?>
            <a href="<?php echo get_device_change_url(); ?>" id="device_change">모바일 버전으로 보기</a>
            <?php
        }
    }
    ?>
    <div class="ft_con">
        <ul>
            <!--<li><a href="<?php echo G5_BBS_URL; ?>/content.php?co_id=company">회사소개</a></li>-->
            <li><a href="<?php echo G5_BBS_URL; ?>/content.php?co_id=provision">서비스이용약관</a></li>
            <li><a href="<?php echo G5_BBS_URL; ?>/content.php?co_id=privacy">개인정보 취급방침</a></li>
        </ul>
        <div id="ft_con_left">
            <b>회사명</b> <?php echo $default['de_admin_company_name']; ?><br/>
            <span><b>대표</b> <?php echo $default['de_admin_company_owner']; ?></span><br>
            <span><b>주소</b> <?php echo $default['de_admin_company_addr']; ?></span><br>
            <span><b>사업자 등록번호</b> <?php echo $default['de_admin_company_saupja_no']; ?></span>
        </div>
        <div id="ft_con_right">
            <span><b>전화</b> <?php echo $default['de_admin_company_tel']; ?></span><br/>
            <span><b>팩스</b> <?php echo $default['de_admin_company_fax']; ?></span><br>
            <!-- <span><b>운영자</b> <?php echo $admin['mb_name']; ?></span><br> -->
            <span><b>통신판매업신고번호</b> <?php echo $default['de_admin_tongsin_no']; ?></span><br/>
            <span><b>개인정보관리책임자</b> <?php echo $default['de_admin_info_name']; ?></span>
        </div>
<?php if ($default['de_admin_buga_no']) echo '<span><b>부가통신사업신고번호</b> ' . $default['de_admin_buga_no'] . '</span>'; ?><br>
        <div class="company_po">Copyright &copy; 2015 <?php echo $default['de_admin_company_name']; ?>. All Rights Reserved.</div>

    </div>
</div>

<?php
$sec = get_microtime() - $begin_time;
$file = $_SERVER['SCRIPT_NAME'];
?>
<?php
//if($is_admin||$is_auth){
//}
if ($config['cf_analytics']) {
    echo $config['cf_analytics'];
}
?>

<script src="<?php echo G5_JS_URL; ?>/sns.js"></script>
<!-- } 하단 끝 -->

<?php
include_once(G5_PATH . '/tail.sub.php');
?>

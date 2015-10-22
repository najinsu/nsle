<?php
if (!defined("_GNUBOARD_"))
    exit; // 개별 페이지 접근 불가

//150902 나진수 가재고 수량으로 인한 주문하기 폼을 제외한 나머지 부분에서 구매하기 ct_select를 0으로 변경함 시작
$ct_select_id=get_session('ss_cart_direct');
if ( !preg_match("/(orderformupdate.php)/", $_SERVER['REQUEST_URI'])&&!preg_match("/(orderform.php)/", $_SERVER['REQUEST_URI'])&& !preg_match("/(cartupdate.php)/", $_SERVER['REQUEST_URI']) && !preg_match("/(orderform.php)/", $_SERVER['REQUEST_URI'])){
$sql = " update {$g5['g5_shop_cart_table']} set ct_select = '0' where ct_select='1' and od_id='$ct_select_id'";
sql_query($sql);
}
//150902 나진수 가재고 수량으로 인한 주문하기 폼을 제외한 나머지 부분에서 구매하기 ct_select를 0으로 변경함 끝
// 상단 파일 지정 : 이 코드는 가능한 삭제하지 마십시오.
if ($default['de_include_head'] && is_file(G5_SHOP_PATH . '/' . $default['de_include_head'])) {
    include_once(G5_SHOP_PATH . '/' . $default['de_include_head']);
    return; // 이 코드의 아래는 실행을 하지 않습니다.
}

include_once(G5_PATH . '/head.sub.php');
include_once(G5_LIB_PATH . '/outlogin.lib.php');
include_once(G5_LIB_PATH . '/poll.lib.php');
include_once(G5_LIB_PATH . '/visit.lib.php');
include_once(G5_LIB_PATH . '/connect.lib.php');
include_once(G5_LIB_PATH . '/popular.lib.php');
include_once(G5_LIB_PATH . '/latest.lib.php');


if ($g5['title'] != $qaconfig['qa_title']) {
    unset($_SESSION['ss_qa_name']);
    unset($_SESSION['ss_qa_hp']);
}
// 150811 주문내역 세션 오류로 인한 추가
if ($g5['title'] != "주문내역조회") {
    unset($_SESSION['ss_od_name']);
    unset($_SESSION['ss_od_hp']);
}
?>
<!-- 150828 나진수 placeholders ie9, ie8 안되는부분 js 추가-->
<script src="<?php echo G5_JS_URL; ?>/placeholders.min.js"></script>
<!-- 상단 시작 { -->
<div id="hd">
    <h1 id="hd_h1"><?php echo $g5['title'] ?></h1>

    <div id="skip_to_container"><a href="#container">본문 바로가기</a></div>

    <?php
    if (defined('_INDEX_')) { // index에서만 실행
        include G5_BBS_PATH . '/newwin.inc.php'; // 팝업레이어
    }
    ?>



    <div id="hd_wrapper">
        <div id="logo"><a href="<?php echo $default['de_root_index_use'] ? G5_URL : G5_SHOP_URL; ?>/"><img src="<?php echo G5_DATA_URL; ?>/common/logo_img" alt="<?php echo $config['cf_title']; ?>"></a></div>

        <div id="hd_sch">
            <h3>쇼핑몰 검색</h3>
            <form name="frmsearch1" action="<?php echo G5_SHOP_URL; ?>/search.php" onsubmit="return search_submit(this);">

                <label for="sch_str" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
                <input type="text" name="q" value="<?php echo stripslashes(get_text(get_search_string($q))); ?>" id="sch_str" required>
                <input type="image" value="검색" src="<?php echo G5_IMG_URL; ?>/search.png" id="sch_submit">

            </form>
            <script>
                function search_submit(f) {
                    if (f.q.value.length < 2) {
                        alert("검색어는 두글자 이상 입력하십시오.");
                        f.q.select();
                        f.q.focus();
                        return false;
                    }

                    return true;
                }
            </script>
        </div>

        <div id="tnb">
            <h3>회원메뉴</h3>
            <ul>
                <?php
                if ($is_member) {
                    $is_auth = false;
                    $sql = " select count(*) as cnt from {$g5['auth_table']} where mb_id = '{$member['mb_id']}' ";
                    $row = sql_fetch($sql);
                    if ($row['cnt'])
                        $is_auth = true;
                    ?>
                        <?php if ($is_admin == 'super' || $is_auth) { ?>
                        <li><a href="<?php echo G5_ADMIN_URL; ?>/shop_admin/" target="_blank"><b>관리자</b></a></li>
                        <?php } ?>
                        <li><a href="<?php echo G5_BBS_URL; ?>/logout.php?url=shop">로그아웃</a></li>
                        <li><a href="<?php echo G5_BBS_URL; ?>/member_confirm.php?url=register_form.php">정보수정</a></li>
                <?php } else { ?>
                    <li><a href="<?php echo G5_BBS_URL; ?>/login.php?url=<?php echo $urlencode; ?>"><b>로그인</b></a></li>
                    <li><a href="<?php echo G5_BBS_URL; ?>/register.php">회원가입</a></li>

                <?php } ?>
                <!--150817 나진수 장바구니 기능 삭제됨-->

                <li><a href="<?php echo G5_BBS_URL; ?>/board.php?bo_table=ask_faq">자주묻는 질문</a></li>

                <?php
                if (!$default['de_root_index_use']) {
                    $com_href = G5_URL;
                    $com_name = '커뮤니티';

                    if ($default['de_shop_layout_use']) {
                        if (!preg_match('#' . G5_SHOP_DIR . '/#', $_SERVER['SCRIPT_NAME'])) {
                            $com_href = G5_SHOP_URL;
                            $com_name = '쇼핑몰';
                        }
                    }
                    ?>
                    <li><a href="<?php echo $com_href; ?>/"><?php echo $com_name; ?></a></li>
                    <?php
                    unset($com_href);
                    unset($com_name);
                }
                ?>
            </ul>
        </div>
    </div>
    <!-- 20150821 나진수 메뉴바 추가-->
    <div id="hd_menu">
        <ul>
            <li  ><a <?php if (defined('_INDEX_')) { ?> style="background:#ffffff;color:#ff455b;" <?php } ?>  href="<?php echo G5_SHOP_URL; ?>">홈</a></li>
            <li  ><a <?php if ( preg_match("/ca_id=10/", $_SERVER['REQUEST_URI'])) { ?> style="background:#ffffff;color:#ff455b;" <?php } ?> href="<?php echo G5_SHOP_URL; ?>/list.php?ca_id=10">뷰티 특가상품</a></li>
            <li  ><a <?php if ( preg_match("/ca_id=20/", $_SERVER['REQUEST_URI'])) { ?> style="background:#ffffff;color:#ff455b;" <?php } ?> href="<?php echo G5_SHOP_URL; ?>/list.php?ca_id=20">일반 특가상품</a></li>
            <li><a href="<?php echo G5_SHOP_URL; ?>/orderinquiry.php" <?php if (preg_match("/orderinquiry.php/", $_SERVER['REQUEST_URI'])) { ?>
                       style="background:#ffffff;color:#ff455b;" <?php } ?>>주문조회</a></li>
            <li><a <?php if (($g5['title'] == $qaconfig['qa_title']) || preg_match("/qalist.php$/", $url)) { ?>  style="background:#ffffff;color:#ff455b;" <?php } ?> href="<?php echo G5_BBS_URL; ?>/qalist.php">1:1문의</a></li>
        </ul>
    </div>

</div>

<div id="wrapper_bg">
    <div id="wrapper">

<?php include(G5_SHOP_SKIN_PATH . '/boxtodayview.skin.php'); // 오늘 본 상품   ?>


        <!-- } 상단 끝 -->

        <!-- 콘텐츠 시작 { -->
        <div id="container">
<?php if ((!$bo_table || $w == 's' ) && !defined('_INDEX_')) { ?><div id="wrapper_title"><?php echo $g5['title'] ?></div><?php } ?>
            <!-- 글자크기 조정 display:none 되어 있음 시작 { -->
            <div id="text_size">
                <button class="no_text_resize" onclick="font_resize('container', 'decrease');">작게</button>
                <button class="no_text_resize" onclick="font_default('container');">기본</button>
                <button class="no_text_resize" onclick="font_resize('container', 'increase');">크게</button>
            </div>
            <!-- } 글자크기 조정 display:none 되어 있음 끝 -->
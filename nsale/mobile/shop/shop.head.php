<?php
if (!defined("_GNUBOARD_"))
    exit; // 개별 페이지 접근 불가
include_once(G5_PATH . '/head.sub.php');
include_once(G5_LIB_PATH . '/outlogin.lib.php');
include_once(G5_LIB_PATH . '/visit.lib.php');
include_once(G5_LIB_PATH . '/connect.lib.php');
include_once(G5_LIB_PATH . '/popular.lib.php');
include_once(G5_LIB_PATH . '/latest.lib.php');

//150902 나진수 가재고 수량으로 인한 주문하기 폼을 제외한 나머지 부분에서 구매하기 ct_select를 0으로 변경함 시작
$ct_select_id=get_session('ss_cart_direct');
if ( !preg_match("/(orderformupdate.php)/", $_SERVER['REQUEST_URI'])&&!preg_match("/(orderform.php)/", $_SERVER['REQUEST_URI'])&& !preg_match("/(cartupdate.php)/", $_SERVER['REQUEST_URI']) && !preg_match("/(orderform.php)/", $_SERVER['REQUEST_URI'])){
$sql = " update {$g5['g5_shop_cart_table']} set ct_select = '0' where ct_select='1' and  od_id='$ct_select_id'";
sql_query($sql);
}
//150902 나진수 가재고 수량으로 인한 주문하기 폼을 제외한 나머지 부분에서 구매하기 ct_select를 0으로 변경함 끝

if ($g5['title'] != $qaconfig['qa_title']) {
    unset($_SESSION['ss_qa_name']);
    unset($_SESSION['ss_qa_hp']);
}
if ($g5['title'] != "주문내역조회") {
    unset($_SESSION['ss_od_name']);
    unset($_SESSION['ss_od_hp']);
}
?>
<meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, width=device-width" />

<header id="hd">

  <?php
    if (defined('_INDEX_')) { // index에서만 실행
        include G5_BBS_PATH . '/newwin.inc.php'; // 팝업레이어
    }
    ?>
    <?php if ((!$bo_table || $w == 's' ) && defined('_INDEX_')) { ?><h1><?php echo $config['cf_title'] ?></h1><?php } ?>




    <ul id="hd_tnb">
        <?php
        if ($is_member) {
            // 150812 나진수 shop.tail.php부분 관리자 권한에 있는 인원에 한해서 pc버전 보기 활성화 부분 추가
            $is_auth = false;
            $sql = " select count(*) as cnt from {$g5['auth_table']} where mb_id = '{$member['mb_id']}' ";
            $row = sql_fetch($sql);
            if ($row['cnt'])
                $is_auth = true;
            ?>
            <li><a href="<?php echo G5_BBS_URL; ?>/logout.php?url=shop">로그아웃</a></li>
            <li><a href="<?php echo G5_BBS_URL; ?>/member_confirm.php?url=register_form.php">정보수정</a></li>
<?php } else { ?>
            <li><a href="<?php echo G5_BBS_URL; ?>/login.php?url=<?php echo $urlencode; ?>">로그인</a></li>
            <li><a href="<?php echo G5_BBS_URL ?>/register.php" id="snb_join">회원가입</a></li>
<?php } ?>
        <li><a href="<?php echo G5_SHOP_URL; ?>/search.php">상품검색</a></li>
        <li><a href="<?php echo G5_BBS_URL; ?>/board.php?bo_table=ask_faq" >자주묻는질문</a></li>
        <!--  //150821 나진수 장바구니 제거 / 자주묻는질문으로 변경      <li><a href="<?php //echo G5_SHOP_URL; ?>/cart.php" >장바구니</a></li>-->

    </ul>
    <div id="logo"><a href="<?php echo $default['de_root_index_use'] ? G5_URL : G5_SHOP_URL; ?>/"><img src="<?php echo G5_DATA_URL; ?>/common/mobile_logo_img" alt="<?php echo $config['cf_title']; ?> 메인"></a></div>
    
<?php // include_once(G5_MSHOP_PATH.'/category.php'); // 분류  ?>



    <ul id="hd_mb">

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
        <li><a <?php if ( preg_match("/ca_id=10/", $_SERVER['REQUEST_URI'])) { ?> style="background:#ffffff;color:#ff455b;" <?php } ?> href="<?php echo G5_SHOP_URL; ?>/list.php?ca_id=10">뷰티상품</a></li>
        <li><a <?php if ( preg_match("/ca_id=20/", $_SERVER['REQUEST_URI'])) { ?> style="background:#ffffff;color:#ff455b;" <?php } ?> href="<?php echo G5_SHOP_URL; ?>/list.php?ca_id=20">일반상품</a></li>

<?php if ($default['de_root_index_use']) { ?>
            <li><a href="<?php echo G5_SHOP_URL; ?>/orderinquiry.php" <?php if (preg_match("/orderinquiry.php/", $_SERVER['REQUEST_URI'])) { ?>
                       style="background:#ffffff;color:#ff455b;" <?php } ?>>주문조회</a></li>
            <li><a <?php if (($g5['title'] == $qaconfig['qa_title']) || preg_match("/qalist.php$/", $url)) { ?>  style="background:#ffffff;color:#ff455b;" <?php } ?> href="<?php echo G5_BBS_URL; ?>/qalist.php">1:1문의</a></li>
            <!--<li><a <?php //if ($bo_table == "ask_faq") { ?>
                        style="background:#ffffff;color:#ff455b;" <?php //} ?> href="<?php //echo G5_BBS_URL; ?>/board.php?bo_table=ask_faq">FAQ</a></li>-->
<?php } ?>
    </ul>
</header>

<?php if (MobileCheck() == 'Computer') { ?>
    <style>
        #hd_mb li a:hover{background:#ffffff; color:#ff455b;border:1px solid #ff455b;border-right:0px; }

    </style>
<?php } ?>


<div id="container">

    <?php if ((!$bo_table || $w == 's' ) && !defined('_INDEX_')) { ?><h1 id="container_title"><?php echo $g5['title'] ?></h1><?php } ?>

    <div id="go_up"><a href="#"><img src="<?php echo G5_IMG_URL; ?>/mobile/top.png"></a></div> 


    <script type="text/javascript">

        $(window).scroll(function () {
            var scrollTop = $(window).scrollTop();
            if (scrollTop >= 500) {
                $('#go_up').css({
                    'position': 'fixed',
                    'bottom': 20 + 'px',
                    'right': 20 + 'px',
                    'display': 'block',
                    'z-index': '9999999'
                });

            }
            else {
                $('#go_up').css({
                    'display': 'none'
                });
            }
        });

    </script>

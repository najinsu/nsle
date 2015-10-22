<?php
include_once('./_common.php');

if (G5_IS_MOBILE) {
    include_once(G5_MSHOP_PATH.'/item.php');
    return;
}

$it_id = trim($_GET['it_id']);

include_once(G5_LIB_PATH.'/iteminfo.lib.php');

// 분류사용, 상품사용하는 상품의 정보를 얻음
$sql = " select a.*, b.ca_name, b.ca_use from {$g5['g5_shop_item_table']} a, {$g5['g5_shop_category_table']} b where a.it_id = '$it_id' and a.ca_id = b.ca_id ";
$it = sql_fetch($sql);
if (!$it['it_id'])
    alert('자료가 없습니다.');
if (!($it['ca_use'] && $it['it_use'])) {
    if (!$is_admin)
        alert('현재 판매가능한 상품이 아닙니다.');
}

// 분류 테이블에서 분류 상단, 하단 코드를 얻음
$sql = " select ca_skin_dir, ca_include_head, ca_include_tail, ca_cert_use, ca_adult_use from {$g5['g5_shop_category_table']} where ca_id = '{$it['ca_id']}' ";
$ca = sql_fetch($sql);
$sns_title = get_text($it['it_name']).' | '.get_text($config['cf_title']);
$sns_url  = G5_SHOP_URL.'/item.php?it_id='.$it['it_id'];
?>
<meta property="og:title" content="<?php echo preg_replace("[<br/>]", "", $sns_title); ?> " />
<meta property="og:image" content="http://54.64.214.194/data/item/<?php echo $it['it_img1'];?>" />
<meta property="og:description" content="<?php $it['it_basic'] ? $it['it_basic']: '&nbsp;' ?>" />
<meta property="og:type" content="website" />
<?php
// 본인인증, 성인인증체크
if(!$is_admin) {
    $msg = shop_member_cert_check($it_id, 'item');
    if($msg)
        alert($msg, G5_SHOP_URL);
}

// 오늘 본 상품 저장 시작
// tv 는 today view 약자
$saved = false;
$tv_idx = (int)get_session("ss_tv_idx");
if ($tv_idx > 0) {
    for ($i=1; $i<=$tv_idx; $i++) {
        if (get_session("ss_tv[$i]") == $it_id) {
            $saved = true;
            break;
        }
    }
}

if (!$saved) {
    $tv_idx++;
    set_session("ss_tv_idx", $tv_idx);
    set_session("ss_tv[$tv_idx]", $it_id);
}
// 오늘 본 상품 저장 끝

// 조회수 증가
if (get_cookie('ck_it_id') != $it_id) {
    sql_query(" update {$g5['g5_shop_item_table']} set it_hit = it_hit + 1 where it_id = '$it_id' "); // 1증가
    set_cookie("ck_it_id", $it_id, time() + 3600); // 1시간동안 저장
}

// 스킨경로
$skin_dir = G5_SHOP_SKIN_PATH;
$ca_dir_check = true;

if($it['it_skin']) {
    $skin_dir = G5_PATH.'/'.G5_SKIN_DIR.'/shop/'.$it['it_skin'];

    if(is_dir($skin_dir)) {
        $form_skin_file = $skin_dir.'/item.form.skin.php';

        if(is_file($form_skin_file))
            $ca_dir_check = false;
    }
}

if($ca_dir_check) {
    if($ca['ca_skin_dir']) {
        $skin_dir = G5_PATH.'/'.G5_SKIN_DIR.'/shop/'.$ca['ca_skin_dir'];

        if(is_dir($skin_dir)) {
            $form_skin_file = $skin_dir.'/item.form.skin.php';

            if(!is_file($form_skin_file))
                $skin_dir = G5_SHOP_SKIN_PATH;
        } else {
            $skin_dir = G5_SHOP_SKIN_PATH;
        }
    }
}

define('G5_SHOP_CSS_URL', str_replace(G5_PATH, G5_URL, $skin_dir));

$g5['title'] = $it['it_name'].' &gt; '.$it['ca_name'];

// 분류 상단 코드가 있으면 출력하고 없으면 기본 상단 코드 출력
if ($ca['ca_include_head'])
    @include_once($ca['ca_include_head']);
else
    include_once('./_head.php');


if ($is_admin) {
    echo '<div class="sit_admin"><a href="'.G5_ADMIN_URL.'/shop_admin/itemform.php?w=u&amp;it_id='.$it_id.'" class="btn_admin">상품 관리</a></div>';
}
?>

<!-- 상품 상세보기 시작 { -->
<?php
// 상단 HTML
echo '<div id="sit_hhtml">'.conv_content($it['it_head_html'], 1).'</div>';

// 보안서버경로
if (G5_HTTPS_DOMAIN)
    $action_url = G5_HTTPS_DOMAIN.'/'.G5_SHOP_DIR.'/cartupdate.php';
else
    $action_url = './cartupdate.php';


// 고객선호도 별점수
$star_score = get_star_image($it['it_id']);

// 관리자가 확인한 사용후기의 개수를 얻음
$sql = " select count(*) as cnt from `{$g5['g5_shop_item_use_table']}` where it_id = '{$it_id}' and is_confirm = '1' ";
$row = sql_fetch($sql);
$item_use_count = $row['cnt'];

// 상품문의의 개수를 얻음
$sql = " select count(*) as cnt from `{$g5['g5_shop_item_qa_table']}` where it_id = '{$it_id}' ";
$row = sql_fetch($sql);
$item_qa_count = $row['cnt'];

// 관련상품의 개수를 얻음
if($default['de_rel_list_use']) {
    $sql = " select count(*) as cnt from {$g5['g5_shop_item_relation_table']} a left join {$g5['g5_shop_item_table']} b on (a.it_id2=b.it_id and b.it_use='1') where a.it_id = '{$it['it_id']}' ";
    $row = sql_fetch($sql);
    $item_relation_count = $row['cnt'];
}

// 소셜 관련

$sns_share_links .= get_sns_share_link('facebook', $sns_url, $sns_title, G5_SHOP_SKIN_URL.'/img/sns_fb_s.png').' ';
$sns_share_links .= get_sns_share_link('twitter', $sns_url, $sns_title, G5_SHOP_SKIN_URL.'/img/sns_twt_s.png').' ';
$sns_share_links .= get_sns_share_link('googleplus', $sns_url, $sns_title, G5_SHOP_SKIN_URL.'/img/sns_goo_s.png');

// 상품품절체크
if(G5_SOLDOUT_CHECK)
    $is_soldout = is_soldout($it['it_id']);

// 주문가능체크
$is_orderable = true;
if(!$it['it_use'] || $it['it_tel_inq'] || $is_soldout)
    $is_orderable = false;

if($is_orderable) {
    // 선택 옵션
    $option_item = get_item_options($it['it_id'], $it['it_option_subject']);

    // 추가 옵션
    $supply_item = get_item_supply($it['it_id'], $it['it_supply_subject']);

    // 상품 선택옵션 수
    $option_count = 0;
    if($it['it_option_subject']) {
        $temp = explode(',', $it['it_option_subject']);
        $option_count = count($temp);
    }

    // 상품 추가옵션 수
    $supply_count = 0;
    if($it['it_supply_subject']) {
        $temp = explode(',', $it['it_supply_subject']);
        $supply_count = count($temp);
    }
}

function pg_anchor($anc_id) {
    global $default;
    global $item_use_count, $item_qa_count, $item_relation_count;
?>
    
<?php
}
?>

<?php if($is_orderable) { ?>
<script src="<?php echo G5_JS_URL; ?>/shop.js"></script>
<?php } ?>

<div id="sit">

    <?php
    // 상품 구입폼
    include_once($skin_dir.'/item.form.skin.php');
    ?>

    <?php
    // 상품 상세정보
    $info_skin = $skin_dir.'/item.info.skin.php';
    if(!is_file($info_skin))
        $info_skin = G5_SHOP_SKIN_PATH.'/item.info.skin.php';
    include $info_skin;
    ?>

</div>

<?php
// 하단 HTML
echo conv_content($it['it_tail_html'], 1);
?>

<?php
if ($ca['ca_include_tail'])
    @include_once($ca['ca_include_tail']);
else
    include_once('./_tail.php');
?>

<script src="<?php echo G5_JS_URL ?>/jquery.arctic_scroll.js"></script>

<script type="text/javascript">

    $(window).scroll(function () {
        var scrollTop = $(window).scrollTop();
        var inf_on_on = $('#sit_inf').offset().top;
        if (scrollTop >= (inf_on_on - 1)) {
            $('.sanchor').css({
                'position': 'fixed',
                'top': 0 + 'px',
                'display': 'block',
                'z-index': '9999999'
            });
            $('.sanchor_bg').css({
                'position': 'fixed',
                'top': 0 + 'px',
                'display': 'block',
                'z-index': '9999999'
            });

            /*모바일일때 넓이 조정
             var ua = window.navigator.userAgent.toLowerCase(); 
             if ( /iphone/.test(ua) || /android/.test(ua) || /opera/.test(ua) || /bada/.test(ua) ) { 
             $('#about_bar ul').css('width','100%');
             }else{
             $('#about_bar ul').css('width','1000px');
             }*/

        }
        else {
            $('.sanchor').css({
                'position': 'relative',
                'top': 0 + 'px',
                'display': 'none'
            });
            $('.sanchor_bg').css({
                'display': 'none'
            });

        }
    });




    $(function () {
        $(".sanchor li a").arctic_scroll({
            speed: "fast"
        });
    });

    /*
     $(document).ready(function(){
     
     $(".arctic_scroll3").click(function(){
     //divb의 top의 위치 값으로 이동
     var pos=$("#sit_inf_explan").position().top;
     $("#sit_inf_explan").animate({scrollTop:pos},'slow');
     });
     
     
     });
     */




</script>

<script type="text/javascript">
//상품페이지 현재 창 on

    $(window).scroll(function () {

        var scrollTop = $(window).scrollTop();
        var inf_on = $('#sit_inf').offset().top;
        var qa_on = $('#sit_qa').offset().top;


        if (scrollTop > (inf_on - 34) && scrollTop < (qa_on)) {
            $('.sanchor li a').css({
                'background-color': 'transparent'
            });
            $(' #inf_on a').css({
                'background': 'rgba(0,0,0,0.5)'
            });   
        }
        if (scrollTop > (qa_on - 34)) {

            $('.sanchor li a').css({
                'background-color': 'transparent'
            });
            $(' #qa_on a').css({
                'background': 'rgba(0,0,0,0.5)'

            });
        }
    });




</script>

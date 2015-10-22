<?php
if (!defined('_GNUBOARD_'))
    exit; // 개별 페이지 접근 불가



    
// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="' . G5_SHOP_SKIN_URL . '/style.css">', 0);
?>

<!-- 상품진열 10 시작 { -->
<?php
/*
  $sort_skin = $skin_dir.'/main.sort.skin.php';
  if(!is_file($sort_skin))
  $sort_skin = G5_SHOP_SKIN_PATH.'/main.sort.skin.php';
  include $sort_skin;
 */

for ($i = 1; $row = sql_fetch_array($result); $i++) {
    if ($this->list_mod >= 2) { // 1줄 이미지 : 2개 이상
        if ($i % $this->list_mod == 0)
            $sct_last = 'sct_last'; // 줄 마지막
        else if ($i % $this->list_mod == 1)
            $sct_last = 'sct_clear'; // 줄 첫번째
        else
            $sct_last = '';
    } else { // 1줄 이미지 : 1개
        $sct_last = 'sct_clear';
    }

    if ($i == 1) {
        if ($this->css) {
            echo "<ul class=\"{$this->css}\">\n";
        } else {
            echo "<ul class=\"sct sct_10\">\n";
        }
    }

    echo "<a href=\"{$this->href}{$row['it_id']}\" class=\"sct_a\"><li class=\"sct_li {$sct_last}\" style=\"width:{$this->img_width}px\">\n";

    if ($this->href) {
        echo "<div class=\"sct_img\">\n";
    }

    if ($this->view_it_img) {
        echo get_it_image($row['it_id'], $this->img_width, $this->img_height, '', '', stripslashes($row['it_name'])) . "\n";
    }

    if ($this->href) {
        echo "</div>\n";
    }

    if ($this->view_it_icon) {
        echo "<div class=\"sct_icon\">" . item_icon($row) . "</div>\n";
    }

    if ($this->view_it_id) {
        echo "<div class=\"sct_id\">&lt;" . stripslashes($row['it_id']) . "&gt;</div>\n";
    }

    if ($this->view_it_basic && $row['it_basic']) {
        echo "<div class=\"sct_basic_tool\"><div class=\"sct_basic\">" . stripslashes($row['it_basic']) . "</div></div>\n";
    }

    if ($this->href) {
        echo "<div class=\"sct_txt\" >\n";
    }

    if ($this->view_it_name) {
        echo stripslashes($row['it_name']) . "\n";
    }

    if ($this->href) {
        echo "</div>\n";
    }


    if ($this->view_it_cust_price || $this->view_it_price) {

        echo "<div class=\"sct_cost\">\n";
        //150824 나진수 시중 가격 없을때는 특별가로 처리

        if ($row['it_cust_price']) {
            echo "<div class=\"sct_sale\">" . round((1 - (get_price($row) / $row['it_cust_price'])) * 100, 0) . "% </div>\n";
        } else {
            echo "<div class=\"sct_no_sale\" style=\"font-size\">특별가</div>\n";
        }

        if ($this->view_it_cust_price && $row['it_cust_price']) {
            echo "<strike>" . display_price($row['it_cust_price']) . "</strike>\n";
        }

        if ($this->view_it_price) {
            //150824 나진수 시중 가격 없을때는 특별가로 처리

            if ($row['it_cust_price']) {
                echo "<div class=\"it_tel_inq\">" . display_price(get_price($row), $row['it_tel_inq']) . "</div>\n";
            } else {
                echo "<div class=\"it_tel_no_inq\">" . display_price(get_price($row), $row['it_tel_inq']) . "</div>\n";
            }
        }

        echo "</div>\n";
    }

    if ($this->view_sns) {
        $sns_top = $this->img_height + 10;
        $sns_url = G5_SHOP_URL . '/item.php?it_id=' . $row['it_id'];
        $sns_title = get_text($row['it_name']) . ' | ' . get_text($config['cf_title']);
        echo "<div class=\"sct_sns\" style=\"top:{$sns_top}px\">";
        echo get_sns_share_link('facebook', $sns_url, $sns_title, G5_SHOP_SKIN_URL . '/img/sns_fb_s.png');
        echo get_sns_share_link('twitter', $sns_url, $sns_title, G5_SHOP_SKIN_URL . '/img/sns_twt_s.png');
        echo get_sns_share_link('googleplus', $sns_url, $sns_title, G5_SHOP_SKIN_URL . '/img/sns_goo_s.png');
        echo "</div>\n";
    }

    echo "</li></a>\n";
}

if ($i > 1)
    echo "</ul>\n";

if ($i == 1)
    echo "<p class=\"sct_noitem\">등록된 상품이 없습니다.</p>\n";
?>
<!-- } 상품진열 10 끝 -->
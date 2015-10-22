<?php
if (!defined("_GNUBOARD_"))
    exit; // 개별 페이지 접근 불가




    
// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="' . G5_SHOP_CSS_URL . '/style.css">', 0);
?>

<?php if ($config['cf_kakao_js_apikey']) { ?>
    <script src="https://developers.kakao.com/sdk/js/kakao.min.js"></script>
    <script src="<?php echo G5_JS_URL; ?>/kakaolink.js"></script>
    <script>
        // 사용할 앱의 Javascript 키를 설정해 주세요.
        Kakao.init("<?php echo $config['cf_kakao_js_apikey']; ?>");
    </script>
<?php } ?>

<!-- 상품진열 10 시작 { -->
<?php
$li_width = intval(95 / $this->list_mod);
$li_width_style = ' style="width:' . $li_width . '%;"';

for ($i = 0; $row = sql_fetch_array($result); $i++) {
    if ($i == 0) {
        if ($this->css) {
            echo "<ul id=\"sct_wrap\" class=\"{$this->css}\">\n";
        } else {
            echo "<ul id=\"sct_wrap\" class=\"sct sct_10\">\n";
        }
    }

    if ($i % $this->list_mod == 0)
        $li_clear = ' sct_clear';
    else
        $li_clear = '';

    echo "<a href=\"{$this->href}{$row['it_id']}\" class=\"sct_a\"><li class=\"sct_li{$li_clear}\"$li_width_style>\n";

    if ($this->href) {
        echo "<div class=\"sct_img\">\n";
    }

    if ($this->view_it_img) {
        echo get_it_image($row['it_id'], $this->img_width, $this->img_height, '', '', stripslashes($row['it_name'])) . "\n";
    }

    if ($this->href) {
        echo "</div>\n";
    }


    if ($this->view_it_id) {
        echo "<div class=\"sct_id\">&lt;" . stripslashes($row['it_id']) . "&gt;</div>\n";
    }

    if ($this->href) {
        echo "<div class=\"sct_txt\">\n";
    }


    if ($this->view_it_name) {
        echo "<div>" . cut_str(stripslashes($row['it_name']), 26, "...") . "</div>\n";
    }

    if ($this->href) {
        echo "</div>\n";
    }

    if ($this->view_it_cust_price || $this->view_it_price) {
        //150824 나진수 시중 가격 없을때는 특별가로 처리

        echo "<div class=\"sct_sale\">\n";
        if ($row['it_cust_price']) {
            echo "<span>" . round((1 - (get_price($row) / $row['it_cust_price'])) * 100, 0) . "%</span>\n";
        } else {
            echo "<span class=\"sct_no_sale\">특별가</span>\n";
        }
        echo "</div>\n";

        echo "<div class=\"sct_cost\">\n";

        if ($this->view_it_cust_price && $row['it_cust_price']) {
            echo "<strike>" . display_price($row['it_cust_price']) . "</strike>\n";
        }
        //150824 나진수 시중 가격 없을때는 특별가로 처리

        if ($this->view_it_price) {
            if ($row['it_cust_price']) {
                echo "<span>" . display_price(get_price($row), $row['it_tel_inq']) . "</span>\n";
            } else {
                echo "<span class=\"it_no_tel_inq\">" . display_price(get_price($row), $row['it_tel_inq']) . "</span>\n";
            }
        }
        echo "</div>\n";
    }

    echo "</li></a>\n";
}

if ($i > 0)
    echo "</ul>\n";

if ($i == 0)
    echo "<p class=\"sct_noitem\">등록된 상품이 없습니다.</p>\n";
?>
<!-- } 상품진열 10 끝 -->

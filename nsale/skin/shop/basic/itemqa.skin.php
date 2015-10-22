<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.G5_SHOP_SKIN_URL.'/style.css">', 0);
?>

<script src="<?php echo G5_JS_URL; ?>/viewimageresize.js"></script>

<!-- 상품문의 목록 시작 { -->
<section id="sit_qa_list">
    <div id="sit_qa_table">
        <ul>
            <li class="sit_qa_num">순번</li>
            <li class="sit_qa_title">질문제목</li>
            <li class="sit_qa_ask">답변</li>
        </ul>
    </div>
    <?php
    $thumbnail_width = 500;
    $iq_num     = $total_count - ($page - 1) * $rows;

    for ($i=0; $row=sql_fetch_array($result); $i++)
    {
        $iq_name    = get_text($row['iq_name']);
        $iq_subject = conv_subject($row['iq_subject'],50,"…");

        $is_secret = false;
        if($row['iq_secret']) {
            $iq_subject .= ' <img src="'.G5_SHOP_SKIN_URL.'/img/icon_secret.gif" alt="비밀글">';

            if($is_admin || $member['mb_id' ] == $row['mb_id']) {
                $iq_question = get_view_thumbnail(conv_content($row['iq_question'], 1), $thumbnail_width);
            } else {
                $iq_question = '비밀글로 보호된 문의입니다.';
                $is_secret = true;
            }
        } else {
            $iq_question = get_view_thumbnail(conv_content($row['iq_question'], 1), $thumbnail_width);
        }
        $iq_time    = substr($row['iq_time'], 2, 8);

        $hash = md5($row['iq_id'].$row['iq_time'].$row['iq_ip']);

        $iq_stats = '';
        $iq_style = '';
        $iq_answer = '';

        if ($row['iq_answer'])
        {
            $iq_answer = get_view_thumbnail(conv_content($row['iq_answer'], 1), $thumbnail_width);
            $iq_stats = '답변보기';
            $is_answer = true;
        } else {
            $iq_stats = '답변보기';
            $iq_answer = '답변이 등록되지 않았습니다.';
            $is_answer = false;
        }

        if ($i == 0) echo '<ol id="sit_qa_ol">';
    ?>

        <li class="sit_qa_li">
            <button type="button" class="sit_qa_li_title">
                <div class="sit_qa_num"><?php echo $iq_num; ?></div> 
            <div class=" sit_qa_title sit_qa_title_align "><?php echo $iq_subject; ?></div>
            <div class="sit_qa_ask" ><?php echo $iq_stats; ?></div>
            </button>
            <div id="sit_qa_con_<?php echo $i; ?>" class="sit_qa_con">
                <div class="sit_qa_p">
                    <div class="sit_qa_qaq">
                        <strong>문의내용</strong><br>
                        <?php echo $iq_question; // 상품 문의 내용 ?>
                    </div>
                    <?php if(!$is_secret) { ?>
                    <div class="sit_qa_qaa">
                        <strong>답변</strong><br>
                        <?php echo $iq_answer; ?>
                    </div>
                    <?php } ?>
                </div>

                <?php if ($is_admin || ($row['mb_id'] == $member['mb_id'] && !$is_answer)) { ?>
                <div class="sit_qa_cmd">
                    <a href="<?php echo $itemqa_form."&amp;iq_id={$row['iq_id']}&amp;w=u"; ?>" class="itemqa_form btn01" onclick="return false;">수정</a>
                    <a href="<?php echo $itemqa_formupdate."&amp;iq_id={$row['iq_id']}&amp;w=d&amp;hash={$hash}"; ?>" class="itemqa_delete btn01">삭제</a>
                    <!-- <button type="button" onclick="javascript:itemqa_update(<?php echo $i; ?>);" class="btn01">수정</button>
                    <button type="button" onclick="javascript:itemqa_delete(fitemqa_password<?php echo $i; ?>, <?php echo $i; ?>);" class="btn01">삭제</button> -->
                </div>
                <?php } ?>
            </div>
        </li>

    <?php
        $iq_num--;
    }

    if ($i > 0) echo '</ol>';

    if (!$i) echo '<p class="sit_empty">자주묻는 질문이 없습니다.</p>';
    ?>
</section>

<?php
echo itemqa_page($config['cf_write_pages'], $page, $total_page, "./itemqa.php?it_id=$it_id&amp;page=", "");
?>
<?php if($is_admin){ ?>
<div id="sit_qa_wbtn">
    <!-- <a href="javascript:itemqawin('it_id=<?php echo $it_id; ?>');">상품문의 쓰기<span class="sound_only"> 새 창</span></a> -->
    <a href="<?php echo $itemqa_form; ?>" class="btn02 itemqa_form">상품문의 쓰기<span class="sound_only"> 새 창</span></a>
</div>
<?php } ?>
<script>
$(function(){
    $(".itemqa_form").click(function(){
        window.open(this.href, "itemqa_form", "width=810,height=680,scrollbars=1");
        return false;
    });

    $(".itemqa_delete").click(function(){
        return confirm("정말 삭제 하시겠습니까?\n\n삭제후에는 되돌릴수 없습니다.");
    });

    $(".sit_qa_li_title").click(function(){
        var $con = $(this).siblings(".sit_qa_con");
        if($con.is(":visible")) {
            $con.slideUp();
        } else {
            $(".sit_qa_con:visible").hide();
            $con.slideDown(
                function() {
                    // 이미지 리사이즈
                    $con.viewimageresize2();
                }
            );
        }
    });

    $(".qa_page").click(function(){
        $("#itemqa").load($(this).attr("href"));
        return false;
    });
});
</script>
<!-- } 상품문의 목록 끝 -->
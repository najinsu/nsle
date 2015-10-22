<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/style.css">', 0);
?>

<!-- 회원정보 찾기 시작 { -->


<div id="find_info" class="new_win mbskin">
    <h1 id="win_title">아이디찾기</h1>

    <form name="fidlost" action="<?php echo $action_url ?>" onsubmit="return fidlost_submit(this);" method="post" autocomplete="off">
    <fieldset id="info_fs">
        <p>
            회원가입 시 등록하신 이름 및 핸드폰 번호를 입력해 주세요.<br>
        </p>
        <label for="mb_name" class="mb_name_label">이름<strong class="sound_only">필수</strong></label>
        <input type="text" name="mb_name" id="mb_name" required class="required frm_input" size="30">
        <label for="mb_hp">핸드폰번호<strong class="sound_only">필수</strong></label>
        <input type="tel" name="mb_hp" id="mb_hp" onkeyup="this.value = this.value.replace(/\D/, '')"  placeholder="- 없이 숫자만 입력" required class="required frm_input" size="30" maxlength="11">
    </fieldset>
    <div class="win_btn">
        <button type="button" onclick="window.close();">창닫기</button>
        <input type="submit" value="확인" class="btn_submit" style="width:56px;margin-left:5px;">
    </div>
    </form>
</div>



<script>
function fpasswordlost_submit(f)


$(function() {
    var sw = screen.width;
    var sh = screen.height;
    var cw = document.body.clientWidth;
    var ch = document.body.clientHeight;
    var top  = sh / 2 - ch / 2 - 100;
    var left = sw / 2 - cw / 2;
    moveTo(left, top);
});
</script>

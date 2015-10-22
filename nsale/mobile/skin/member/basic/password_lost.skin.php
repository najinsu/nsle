<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/style.css">', 0);
?>

<div id="find_info" class="new_win mbskin">
    <h1 id="win_title">비밀번호찾기</h1>

    <form name="fpasswordlost" action="<?php echo $action_url ?>" onsubmit="return fpasswordlost_submit(this);" method="post" autocomplete="off">
        <fieldset id="info_fs">
            <p>
                핸드폰으로 임시비밀번호를 전송해드립니다.<br />
                로그인 후 쇼핑몰 상단에 있는 정보수정에서 비밀번호를 변경하세요
            </p>
            <label for="mb_id" class="mb_id_label">아이디<strong class="sound_only">필수</strong></label>
            <input type="text" name="mb_id" id="mb_id" required class="required frm_input" size="30">
            <label for="mb_name" class="mb_name_label">이름<strong class="sound_only">필수</strong></label>
            <input type="text" name="mb_name" id="mb_name" required class="required frm_input" size="30">
            <label for="mb_hp">핸드폰번호<strong class="sound_only">필수</strong></label>
            <input type="tel" name="mb_hp" id="mb_hp" onkeyup="this.value = this.value.replace(/\D/, '')"  placeholder="- 없이 숫자만 입력" required class="required frm_input" size="30" maxlength="11">
        </fieldset>
        <div class="win_btn">
            <input type="submit" value="확인" class="btn_submit" style="width:56px;margin-right:5px;">
            <button type="button" onclick="window.close();">창닫기</button>
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

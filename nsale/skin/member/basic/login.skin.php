<?php
if (!defined('_GNUBOARD_'))
    exit; // 개별 페이지 접근 불가

    
// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="' . $member_skin_url . '/style.css">', 0);
//로그인창 페이지 마다 다른 제목 생성
$login_page_title = "회원 로그인";
?>
<style>
    #wrapper_title{font-size:0px;height:0px;margin-bottom:0px;}

</style>
<!-- 로그인 시작 { -->
<div id="mb_login" class="mbskin">


    <?php if ($default['de_level_sell'] == 1) { // 상품구입 권한  ?>

        <!-- 주문하기, 신청하기 -->
        <?php
        if (preg_match("/orderform.php/", $url)) {
            $login_page_title = "회원 구매";
            ?>
            <h1>구매하기</h1>
            <section id="mb_login_notmb">
                <div class="mb_login_notmb_title">비회원구매</div>
                <div class="mb_login_notmb_sub_title">비회원으로 구매하시면<br />
                    주문조회 및 1:1 문의가 통합되어 관리되지 않습니다.</div>

                <div id="guest_privacy">
        <?php echo $default['de_guest_privacy']; ?>
                </div>
                <input type="checkbox" id="agree" value="1" checked="checked">

                <label for="agree">개인정보수집에 대한 내용을 읽었으며 이에 동의합니다.</label>

                <div class="btn_confirm">
                    <a href="javascript:guest_submit(document.flogin);" class="btn02">비회원으로 구매하기</a>
                </div>

                <script>
                    function guest_submit(f)
                    {
                        if (document.getElementById('agree')) {
                            if (!document.getElementById('agree').checked) {
                                alert("개인정보수집에 대한 내용을 읽고 이에 동의하셔야 합니다.");
                                return;
                            }
                        }

                        f.url.value = "<?php echo $url; ?>";
                        f.action = "<?php echo $url; ?>";
                        f.submit();
                    }
                </script>
            </section>

    <?php
    } else if (preg_match("/orderinquiry.php$/", $url)) {
        $login_page_title = "회원 주문 조회";
        ?>

            <h1>주문조회</h1>

            <fieldset id="mb_login_od">
                <legend>비회원 주문조회</legend>
                <div class="mb_login_od_title">비회원 주문조회</div>
                <div class="mb_login_od_sub_title">비회원 주문시 입력한 이름 및 휴대전화번호 조회</div>

                <form name="forderinquiry" method="post" id="mb_login_od_tool" action="./set_session_order.php" onsubmit="return checkForm()" autocomplete="off"><!-- 150811 나진수 action=" echo urldecode($url);" 세션 오류로 인한 action 부분 수정  -->
                    <input type="text" name="od_name" value="<?php echo $od_name; ?>" id="od_name" placeholder="주문자 이름" required class="frm_input required" size="20">
                    <input type="text" name="od_hp" size="20" id="od_hp" onkeyup="this.value = this.value.replace(/\D/, '')" value="<?php echo $od_hp ?>"  placeholder="휴대전화(- 없이 숫자만 입력)" required class="frm_input required" maxlength="11">
                    <input type="submit" value="확인" class="btn_submit">



                </form>
                <section id="mb_login_odinfo">
                    <h2>비회원 주문조회 안내</h2>
                    <p>주문 시 입력하신 주문자 이름 및 휴대전화를 정확히 입력해주십시오.</p>
                </section>
            </fieldset>






    <?php
    } else if (preg_match("/qalist.php$/", $url)) {
        $login_page_title = "회원 문의하기 / 내역 조회";
        ?>

            <h1>1:1 문의하기</h1>

            <fieldset id="mb_login_od_qa">  
                    
                <div class="mb_login_od_qa_title">비회원 문의하기 / 내역 조회</div>
                <div class="mb_login_od_qa_sub_title">이름과 휴대폰번호를 통하여 문의내역이 관리됩니다.</div>

                <form name="fqa" id="fqa" method="post" action="./set_session_qa.php" onsubmit="return checkForm2()" autocomplete="off">
                    <input type="text" name="qa_name" value="<?php echo $qa_name ?>" placeholder="주문자 이름" id="qa_name"  required class="frm_input required" size="20">
                    <input type="text" name="qa_hp" onkeyup="this.value = this.value.replace(/\D/, '')" value="<?php echo $qa_hp ?>" id="qa_hp"  placeholder="휴대전화(- 없이 숫자만 입력)" required class="frm_input required" size="20" maxlength="11" >
                    <input type="submit" value="확인" class="btn_submit">
                </form>
                <section id="mb_login_odinfo">
                    <h2>비회원 문의내역 안내</h2>
                    <p>나중에 동일한 이름과 휴대전화번호로 가입하셔도<br />기존의 비회원 문의내역은 통합되지 않습니다.</p>
                </section>
            </fieldset>



    <?php } ?>
<?php } ?>
    <form name="flogin" action="<?php echo $login_action_url ?>" onsubmit="return flogin_submit(this);" method="post">
        <input type="hidden" name="url" value="<?php echo $login_url ?>">

        <fieldset id="login_fs" <?php if ($login_page_title == "회원 로그인") { ?>style="width:100%;margin-top:40px;"<?php } ?>>
            <!-- 150811 로그인  ui 깨지는 부분이 있어 주소창에서 불러왔는데 title기준으로 변경 if (!preg_match("/http/", $url)) -->
            <div id="login_fs_con">
                <legend>회원로그인</legend>
                <div class="login_fs_con_title"><?php echo $login_page_title; ?></div>
                <div class="login_fs_con_sub_title">회원이신 고객님은 로그인하여 주십시오</div>
                <input type="text" name="mb_id" id="login_id" placeholder="회원아이디" required class="frm_input required" size="20" maxLength="20">
                <input type="password" name="mb_password" placeholder="비밀번호" id="login_pw" required class="frm_input required" size="20" maxLength="20">
                <input type="submit" value="로그인" class="btn_submit">
                <div class="btn_confirm" >
                    <div class="btn_confirm_title" <?php if ($login_page_title == "회원 로그인") { ?>style="margin-top:20px;"<?php } ?>>아이디/비밀번호를 잊으셨나요?</div>
                    <!-- 150811 로그인  ui 깨지는 부분이 있어 주소창에서 불러왔는데 title기준으로 변경 if (!preg_match("/http/", $url)) -->
                    <a href="<?php echo G5_BBS_URL ?>/id_lost.php" target="_blank" id="login_id_lost" class="btn02">아이디 찾기</a>
                    <a href="<?php echo G5_BBS_URL ?>/password_lost.php" target="_blank" id="login_password_lost" class="btn02">비밀번호 찾기</a>
                </div>
            </div>
        </fieldset>



    </form>

<?php // 쇼핑몰 사용시 여기까지 반드시 복사해 넣으세요  ?>
    <aside id="login_info">
        <div class="login_info_title">
            아직 회원이 아니신가요?<br />
            <a href="./register.php" class="btn02">회원 가입</a>
        </div>
    </aside>
</div>



<script>


    function flogin_submit(f)
    {
        return true;
    }
    
    
 //최소 핸드폰 수   
    function checkForm(){
    var forderinquiry = document.forderinquiry;
    if (forderinquiry.od_hp.value.length < 10) { 
        alert("핸드폰번호는 10~11자 이내로 입력하여 주십시오.");
        forderinquiry.od_hp.focus();
        return false;
    } 
    return true;
}
//최소 핸드폰 수   
    function checkForm2(){
    var fqa = document.fqa;
    if (fqa.qa_hp.value.length < 10) { 
        alert("핸드폰번호는 10~11자 이내로 입력하여 주십시오.");
        fqa.qa_hp.focus();
        return false;
    } 
    return true;
}
</script>



<!-- } 로그인 끝 -->
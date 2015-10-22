<?php
if (!defined('_GNUBOARD_'))
    exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="' . $member_skin_url . '/style.css">', 0);
?>
<style>
    #container_title{display:none;height:0px; font-size: 0px;background:#fcc;}
</style>
<div id="mb_login" class="mbskin">
    
  
<?php if ($default['de_level_sell'] == 1) { // 상품구입 권한   ?>

        <!-- 주문하기, 신청하기 -->
    <?php if (preg_match("/orderform.php/", $url)) { ?>

            <section id="mb_login_notmb">
                <h2>비회원 구매</h2>


                <div id="guest_privacy">
                    <?php echo $default['de_guest_privacy']; ?>
                </div>
                <div class="agree">
                <label for="agree">개인정보수집에 대한 내용을 읽었으며 이에 동의합니다.</label>
                <input type="checkbox" id="agree" value="1" checked="checked">
                </div>
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

    <?php } else if (preg_match("/orderinquiry.php$/", $url)) { ?>
            <fieldset id="mb_login_od">
                            <h1>주문조회</h1>

                <legend>비회원 주문조회</legend>

                <form name="forderinquiry" method="post" action="./set_session_order.php"  onsubmit="return checkForm()" autocomplete="off">

                    <label for="od_name" class="od_name sound_only">주문자 이름<strong class="sound_only"> 필수</strong></label>
                    <input type="text" name="od_name" value="<?php echo $od_name ?>" id="od_name" placeholder="주문자 이름" required class="frm_input required" size="20">
                    <label for="od_hp" class="od_hp sound_only">휴대전화<strong class="sound_only"> 필수</strong></label>
                    <input type="tel" name="od_hp" onkeyup="this.value = this.value.replace(/\D/, '')" value="<?php echo $od_hp ?>" id="od_hp" placeholder="휴대전화 ( - 없이 숫자만 입력)" required class="frm_input required" size="20">
                    <input type="submit" value="확인" class="btn_submit">

                </form>
            </fieldset>



    <?php } else if (preg_match("/qalist.php$/", $url)) { ?>
            <fieldset id="mb_login_od">
                <div id="order_login_top">
                    <div id="order_login_title">1:1문의하기 전에 자주묻는 질문을 확인해주세요 ^^</div>
                <div id="order_cate">
                    <ul>
                        <li><a href="<?php echo G5_BBS_URL; ?>/board.php?bo_table=ask_faq" >전체</a></li>
                        <li><a href="<?php echo G5_BBS_URL; ?>/board.php?bo_table=ask_faq&sca=%EC%A3%BC%EB%AC%B8%2F%EB%B0%B0%EC%86%A1">주문/배송</a></li>
                        <li><a href="<?php echo G5_BBS_URL; ?>/board.php?bo_table=ask_faq&sca=%EC%B7%A8%EC%86%8C%2F%EB%B0%98%ED%92%88">취소/반품</a></li>
                        <li><a href="<?php echo G5_BBS_URL; ?>/board.php?bo_table=ask_faq&sca=%EC%98%A4%EB%A5%98%EB%AC%B8%EC%9D%98" style="border-right:0px">오류문의</a></li>
                    </ul>
                </div>
            </div>
                
                 
                <form name="fqa" id="fqa" method="post" action="./set_session_qa.php"  onsubmit="return checkForm2()" autocomplete="off">
                    <h1>문의하기 / 내역 조회<br /><span>이름과 휴대폰번호를 통하여 문의내역이 관리됩니다.</span></h1>
                    <label for="qa_name" class="qa_name sound_only">주문자 이름<strong class="sound_only"> 필수</strong></label>
                    <input type="text" name="qa_name" value="<?php echo $qa_name ?>" id="qa_name" placeholder="문의자 이름" required class="frm_input required" size="20">
                    <label for="qa_hp" class="qa_hp sound_only">휴대전화<strong class="sound_only"> 필수</strong></label>
                    <input type="tel" name="qa_hp" onkeyup="this.value = this.value.replace(/\D/, '')" value="<?php echo $qa_hp ?>" id="qa_hp" placeholder="휴대전화 ( - 없이 숫자만 입력)" required class="frm_input required" size="20">
                    <input type="submit" value="확인" class="btn_submit">

                </form>
            </fieldset>



        <?php } ?>
        <?php } ?>

            <form name="flogin" action="<?php echo $login_action_url ?>" onsubmit="return flogin_submit(this);" method="post">
        <input type="hidden" name="url" value="<?php echo $login_url ?>">

            <div id="login_frm">
                        <h1><?php echo $g5['title'] ?></h1>

                <label for="login_id" class="sound_only">아이디<strong class="sound_only"> 필수</strong></label>
                <input type="text" name="mb_id" id="login_id" placeholder="아이디(필수)" required class="frm_input required" maxLength="20">
                <label for="login_pw" class="sound_only">비밀번호<strong class="sound_only"> 필수</strong></label>
                <input type="password" name="mb_password" id="login_pw" placeholder="비밀번호(필수)" required class="frm_input required" maxLength="20">
                <div class="auto_login">
                    <div class="find_id_pw"><a href="<?php echo G5_BBS_URL ?>/id_lost.php" target="_blank" id="login_id_lost" >아이디 찾기</a> | 
            <a href="<?php echo G5_BBS_URL ?>/password_lost.php" target="_blank" id="login_password_lost"  >비밀번호 찾기</a>
                    </div>
                </div>
                <input type="submit" value="로그인" class="btn_submit">
                <section id="login_info">
            <h2>아직 회원이 아니신가요?<h2>
            <a href="./register.php" class="btn02">회원가입</a>
            </section>
            </div>
            
        
    </form>
<?php // 쇼핑몰 사용시 여기까지 반드시 복사해 넣으세요   ?>


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
        alert("휴대전화번호는 10글자 이상 입력하세요.");
        forderinquiry.od_hp.focus();
        return false;
    } 
    return true;
}
//최소 핸드폰 수   
    function checkForm2(){
    var fqa = document.fqa;
    if (fqa.qa_hp.value.length < 10) { 
        alert("휴대전화번호는 10글자 이상 입력하세요.");
        fqa.qa_hp.focus();
        return false;
    } 
    return true;
}
</script>

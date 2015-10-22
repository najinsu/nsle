<?php
if (!defined('_GNUBOARD_'))
    exit; // 개별 페이지 접근 불가


    
// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="' . $member_skin_url . '/style.css">', 0);
?>

<!-- 회원정보 입력/수정 시작 { -->
<div class="mbskin">

    <script src="<?php echo G5_JS_URL ?>/jquery.register_form.js"></script>
    <?php if ($config['cf_cert_use'] && ($config['cf_cert_ipin'] || $config['cf_cert_hp'])) { ?>
        <script src="<?php echo G5_JS_URL ?>/certify.js"></script>
<?php } ?>

    <form id="fregisterform" name="fregisterform" action="<?php echo $register_action_url ?>" onsubmit="return fregisterform_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off">
        <input type="hidden" name="w" value="<?php echo $w ?>">
        <input type="hidden" name="url" value="<?php echo $urlencode ?>">
        <input type="hidden" name="agree" value="<?php echo $agree ?>">
        <input type="hidden" name="agree2" value="<?php echo $agree2 ?>">
        <input type="hidden" name="cert_type" value="<?php echo $member['mb_certify']; ?>">
        <input type="hidden" name="cert_no" value="">
<?php if (isset($member['mb_sex'])) { ?><input type="hidden" name="mb_sex" value="<?php echo $member['mb_sex'] ?>"><?php } ?>


        <div class="tbl_frm01 tbl_wrap">
            <table>
                <caption>사이트 이용정보 입력</caption>
                <tbody>
                    <tr>
                        <th scope="row"><label for="reg_mb_id">아이디<strong class="sound_only">필수</strong></label></th>
                        <td>
                            <?php if($w==''){?><span class="frm_info">영문자, 숫자, _ 만 입력 가능. 최소 4자이상 입력하세요.</span><?php } ?>
                            <input type="text" name="mb_id" value="<?php echo $member['mb_id'] ?>" id="reg_mb_id" <?php echo $required ?> <?php echo $readonly ?> <?php if($w=='u'){?> style="border:1px solid #ffffff !important; background: #ffffff !important;" readonly onfocus="this.blur();"<?php } ?> class="frm_input <?php echo $required ?> <?php echo $readonly ?>" minlength="4" maxlength="20">
                            <span id="msg_mb_id"></span>
                            <?php if($w==''){?>
                            <a href="#" class="overlap idcheck btn02" style="padding:4px 2px;">중복확인</a> 
                            <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="reg_mb_password">비밀번호<strong class="sound_only">필수</strong></label></th>
                        <td><input type="password" name="mb_password" id="reg_mb_password" <?php echo $required ?> class="frm_input <?php echo $required ?>" minlength="3" maxlength="20"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="reg_mb_password_re">비밀번호 확인<strong class="sound_only">필수</strong></label></th>
                        <td><input type="password" name="mb_password_re" id="reg_mb_password_re" <?php echo $required ?> class="frm_input <?php echo $required ?>" minlength="3" maxlength="20"></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="tbl_frm01 tbl_wrap">
            <table>
                <caption>개인정보 입력</caption>
                <tbody>
                    <tr>
                        <th scope="row"><label for="reg_mb_name">이름<strong class="sound_only">필수</strong></label></th>
                        <td>
                            <?php if ($config['cf_cert_use']) { ?>
                                <span class="frm_info">아이핀 본인확인 후에는 이름이 자동 입력되고 휴대폰 본인확인 후에는 이름과 휴대폰번호가 자동 입력되어 수동으로 입력할수 없게 됩니다.</span>
                            <?php } ?>
                            <input type="text" id="reg_mb_name" name="mb_name" value="<?php echo $member['mb_name'] ?>" <?php echo $required ?> <?php echo $readonly; ?> class="frm_input <?php echo $required ?> <?php echo $readonly ?>" size="20">
                            <?php
                            if ($config['cf_cert_use']) {
                                if ($config['cf_cert_ipin'])
                                    echo '<button type="button" id="win_ipin_cert" class="btn_frmline">아이핀 본인확인</button>' . PHP_EOL;
                                if ($config['cf_cert_hp'])
                                    echo '<button type="button" id="win_hp_cert" class="btn_frmline">휴대폰 본인확인</button>' . PHP_EOL;

                                echo '<noscript>본인확인을 위해서는 자바스크립트 사용이 가능해야합니다.</noscript>' . PHP_EOL;
                            }
                            ?>
                            <?php
                            if ($config['cf_cert_use'] && $member['mb_certify']) {
                                if ($member['mb_certify'] == 'ipin')
                                    $mb_cert = '아이핀';
                                else
                                    $mb_cert = '휴대폰';
                                ?>
                                <div id="msg_certify">
                                    <strong><?php echo $mb_cert; ?> 본인확인</strong><?php if ($member['mb_adult']) { ?> 및 <strong>성인인증</strong><?php } ?> 완료
                                </div>
<?php } ?>
                        </td>
                    </tr>


                    <tr>
                        <th scope="row"><label for="reg_mb_email">E-mail<strong class="sound_only">필수</strong></label></th>
                        <td>
                                <?php if ($config['cf_use_email_certify']) { ?>
                                <span class="frm_info">
                                    <?php
                                    if ($w == '') {
                                        echo "E-mail 로 발송된 내용을 확인한 후 인증하셔야 회원가입이 완료됩니다.";
                                    }
                                    ?>
                                <?php
                                if ($w == 'u') {
                                    echo "E-mail 주소를 변경하시면 다시 인증하셔야 합니다.";
                                }
                                ?>
                                </span>
<?php } ?>
                            <input type="hidden" name="old_email" value="<?php echo $member['mb_email'] ?>">
                            <input type="text" name="mb_email" value="<?php echo isset($member['mb_email']) ? $member['mb_email'] : ''; ?>" id="reg_mb_email" style="margin-bottom:5px;" required class="frm_input email required" maxlength="100">
                            <input type="checkbox" name="mb_mailling" value="1" id="reg_mb_mailling" <?php echo ($w == '' || $member['mb_mailling']) ? 'checked' : ''; ?>>&nbsp; E-mail 수신동의

                            <br />* 입력한 연락처로 각종 이벤트 / 혜택 등의 정보를 안내받겠습니다.(거래정보 관련 내역은 동의와 관계없이 수신됩니다.)
                        </td>
                    </tr>

                    <?php if ($config['cf_use_homepage']) { ?>
                        <tr>
                            <th scope="row"><label for="reg_mb_homepage">홈페이지<?php if ($config['cf_req_homepage']) { ?><strong class="sound_only">필수</strong><?php } ?></label></th>
                            <td><input type="text" name="mb_homepage" value="<?php echo $member['mb_homepage'] ?>" id="reg_mb_homepage" <?php echo $config['cf_req_homepage'] ? "required" : ""; ?> class="frm_input <?php echo $config['cf_req_homepage'] ? "required" : ""; ?>" size="70" maxlength="255"></td>
                        </tr>
<?php } ?>

                    <?php if ($config['cf_use_tel']) { ?>
                        <tr>
                            <th scope="row"><label for="reg_mb_tel">전화번호<?php if ($config['cf_req_tel']) { ?><strong class="sound_only">필수</strong><?php } ?></label></th>
                            <td><input type="text" name="mb_tel" value="<?php echo $member['mb_tel'] ?>" id="reg_mb_tel" <?php echo $config['cf_req_tel'] ? "required" : ""; ?> class="frm_input <?php echo $config['cf_req_tel'] ? "required" : ""; ?>" maxlength="20"></td>
                        </tr>
<?php } ?>

<?php if ($config['cf_use_hp'] || $config['cf_cert_hp']) { ?>
                        <tr>
                            <th scope="row"><label for="reg_mb_hp">휴대폰번호<?php if ($config['cf_req_hp']) { ?><strong class="sound_only">필수</strong><?php } ?></label></th>
                            <td>
                                <input type="text" name="mb_hp" value="<?php echo $member['mb_hp'] ?>"  onkeyup="this.value = this.value.replace(/\D/, '')" placeholder="( - 없이 숫자만 입력)" id="reg_mb_hp" <?php echo ($config['cf_req_hp']) ? "required" : ""; ?> class="frm_input <?php echo ($config['cf_req_hp']) ? "required" : ""; ?>" maxlength="11">
                                <input type="checkbox" name="mb_sms" value="1" id="reg_mb_sms" <?php echo ($w == '' || $member['mb_sms']) ? 'checked' : ''; ?>>&nbsp;휴대폰 수신 동의

                        <?php if ($config['cf_cert_use'] && $config['cf_cert_hp']) { ?>
                                    <input type="hidden" name="old_mb_hp" value="<?php echo $member['mb_hp'] ?>">
                        <?php } ?>
                            </td>
                        </tr>
                            <?php } ?>

<?php if ($config['cf_use_addr']) { ?>
                        <tr>
                            <th scope="row">
                                주소
    <?php if ($config['cf_req_addr']) { ?><strong class="sound_only">필수</strong><?php } ?>
                            </th>
                            <td><!-- 150811 나진수 구매자 주소 우편번호 / 기본주소 박스 수정 못하게 / 주소검색 되게 작동 소스 추가  onclick ="win_zip()" readonly="readonly"-->
                                <label for="reg_mb_zip" class="sound_only">우편번호<?php echo $config['cf_req_addr'] ? '<strong class="sound_only"> 필수</strong>' : ''; ?></label>
                                <input type="text" name="mb_zip" onclick="win_zip('forderform', 'od_b_zip', 'od_b_addr1', 'od_b_addr2', 'od_b_addr3', 'od_b_addr_jibeon');return false;"  value="<?php echo $member['mb_zip1'] . $member['mb_zip2']; ?>" id="reg_mb_zip" <?php echo $config['cf_req_addr'] ? "required" : ""; ?> class="frm_input <?php echo $config['cf_req_addr'] ? "required" : ""; ?>" size="10" maxlength="6" readonly="readonly">
                                <button type="button" class="btn_frmline" onclick="win_zip('fregisterform', 'mb_zip', 'mb_addr1', 'mb_addr2', 'mb_addr3', 'mb_addr_jibeon');">주소 검색</button><br>
                                <input type="text" readonly="readonly" onclick="win_zip('forderform', 'od_b_zip', 'od_b_addr1', 'od_b_addr2', 'od_b_addr3', 'od_b_addr_jibeon');return false;"  name="mb_addr1" value="<?php echo $member['mb_addr1'] ?>" id="reg_mb_addr1" <?php echo $config['cf_req_addr'] ? "required" : ""; ?> class="frm_input frm_address <?php echo $config['cf_req_addr'] ? "required" : ""; ?>" size="50" >
                                <label for="reg_mb_addr1">기본주소<?php echo $config['cf_req_addr'] ? '<strong class="sound_only"> 필수</strong>' : ''; ?></label><br>
                                <input type="text" name="mb_addr2" value="<?php echo $member['mb_addr2'] ?>" id="reg_mb_addr2" class="frm_input frm_address" size="50">
                                <label for="reg_mb_addr2">상세주소</label>
                                <br>
                                <input type="text" name="mb_addr3" value="<?php echo $member['mb_addr3'] ?>" id="reg_mb_addr3" class="frm_input frm_address" size="50" readonly="readonly">
                                <label for="reg_mb_addr3">참고항목</label>
                                <input type="hidden" name="mb_addr_jibeon" value="<?php echo $member['mb_addr_jibeon']; ?>">
                            </td>
                        </tr>
<?php } ?>
                </tbody>
            </table>
        </div>

        <div class="tbl_frm01 tbl_wrap">
            <table>
              <!--  <caption>기타 개인설정</caption>-->
                <tbody>
                    <?php if ($config['cf_use_signature']) { ?>
                        <tr>
                            <th scope="row"><label for="reg_mb_signature">서명<?php if ($config['cf_req_signature']) { ?><strong class="sound_only">필수</strong><?php } ?></label></th>
                            <td><textarea name="mb_signature" id="reg_mb_signature" <?php echo $config['cf_req_signature'] ? "required" : ""; ?> class="<?php echo $config['cf_req_signature'] ? "required" : ""; ?>"><?php echo $member['mb_signature'] ?></textarea></td>
                        </tr>
<?php } ?>

                    <?php if ($config['cf_use_profile']) { ?>
                        <tr>
                            <th scope="row"><label for="reg_mb_profile">자기소개</label></th>
                            <td><textarea name="mb_profile" id="reg_mb_profile" <?php echo $config['cf_req_profile'] ? "required" : ""; ?> class="<?php echo $config['cf_req_profile'] ? "required" : ""; ?>"><?php echo $member['mb_profile'] ?></textarea></td>
                        </tr>
<?php } ?>

<?php if ($config['cf_use_member_icon'] && $member['mb_level'] >= $config['cf_icon_level']) { ?>
                        <tr>
                            <th scope="row"><label for="reg_mb_icon">회원아이콘</label></th>
                            <td>
                                <span class="frm_info">
                                    이미지 크기는 가로 <?php echo $config['cf_member_icon_width'] ?>픽셀, 세로 <?php echo $config['cf_member_icon_height'] ?>픽셀 이하로 해주세요.<br>
                                    gif만 가능하며 용량 <?php echo number_format($config['cf_member_icon_size']) ?>바이트 이하만 등록됩니다.
                                </span>
                                <input type="file" name="mb_icon" id="reg_mb_icon" class="frm_input">
    <?php if ($w == 'u' && file_exists($mb_icon_path)) { ?>
                                    <img src="<?php echo $mb_icon_url ?>" alt="회원아이콘">
                                    <input type="checkbox" name="del_mb_icon" value="1" id="del_mb_icon">
                                    <label for="del_mb_icon">삭제</label>
    <?php } ?>
                            </td>
                        </tr>
<?php } ?>






                    <?php if ($w == "" && $config['cf_use_recommend']) { ?>
                        <tr>
                            <th scope="row"><label for="reg_mb_recommend">추천인아이디</label></th>
                            <td><input type="text" name="mb_recommend" id="reg_mb_recommend" class="frm_input"></td>
                        </tr>
<?php } ?>


                </tbody>
            </table>
        </div>

        <div class="btn_confirm">
            <!-- 20150921 나진수 회원탈퇴기능 추가 시작 -->
            <?php if($w==''){?>
            <a href="<?php echo G5_URL ?>" class="btn_cancel">취소</a>
            <?php }else{ ?>
            <a href="<?php echo G5_BBS_URL; ?>/member_confirm.php?url=member_leave.php" onclick="return member_leave();" class="btn_cancel">회원탈퇴</a>
            <?php } ?>
            <!-- 20150921 나진수 회원탈퇴기능 추가 끝 -->

            <input type="submit" value="<?php echo $w == '' ? '회원가입' : '정보수정'; ?>" id="btn_submit" class="btn_submit" accesskey="s">
        </div>
    </form>


    <script>

        $(".idcheck").click(function () {
            var msg = reg_mb_id_check();
            if (msg == "" || msg == null) {
                // 중복된 아이디가 존재하지 않는다.
                if (!confirm("가입할 수 있는 아이디입니다.\n현재 아이디를 사용하시겠습니까?")) {
                    document.getElementById("reg_mb_id").value = "";
                }
            }
            else
            {
                // 중복된 아이디가 존재한다.
                alert(msg);
            }
        });
    </script>







    <script>
        $(function () {
            $("#reg_zip_find").css("display", "inline-block");

<?php if ($config['cf_cert_use'] && $config['cf_cert_ipin']) { ?>
                // 아이핀인증
                $("#win_ipin_cert").click(function () {
                    if (!cert_confirm())
                        return false;

                    var url = "<?php echo G5_OKNAME_URL; ?>/ipin1.php";
                    certify_win_open('kcb-ipin', url);
                    return;
                });

<?php } ?>
<?php if ($config['cf_cert_use'] && $config['cf_cert_hp']) { ?>
                // 휴대폰인증
                $("#win_hp_cert").click(function () {
                    if (!cert_confirm())
                        return false;

    <?php
    switch ($config['cf_cert_hp']) {
        case 'kcb':
            $cert_url = G5_OKNAME_URL . '/hpcert1.php';
            $cert_type = 'kcb-hp';
            break;
        case 'kcp':
            $cert_url = G5_KCPCERT_URL . '/kcpcert_form.php';
            $cert_type = 'kcp-hp';
            break;
        case 'lg':
            $cert_url = G5_LGXPAY_URL . '/AuthOnlyReq.php';
            $cert_type = 'lg-hp';
            break;
        default:
            echo 'alert("기본환경설정에서 휴대폰 본인확인 설정을 해주십시오");';
            echo 'return false;';
            break;
    }
    ?>

                    certify_win_open("<?php echo $cert_type; ?>", "<?php echo $cert_url; ?>");
                    return;
                });
<?php } ?>
        });

        // submit 최종 폼체크
        function fregisterform_submit(f)
        {
            // 회원아이디 검사
            if (f.w.value == "") {
                var msg = reg_mb_id_check();
                if (msg) {
                    alert(msg);
                    f.mb_id.select();
                    return false;
                }
            }



            if (f.w.value == "") {
                if (f.mb_password.value.length < 3) {
                    alert("비밀번호를 3글자 이상 입력하십시오.");
                    f.mb_password.focus();
                    return false;
                }
            }

            if (f.mb_password.value != f.mb_password_re.value) {
                alert("비밀번호가 같지 않습니다.");
                f.mb_password_re.focus();
                return false;
            }

            if (f.mb_password.value.length > 0) {
                if (f.mb_password_re.value.length < 3) {
                    alert("비밀번호를 3글자 이상 입력하십시오.");
                    f.mb_password_re.focus();
                    return false;
                }
            }
            // 150811 나진수 휴대폰 번호 10자 이상 스크립트 추가
            if (f.mb_hp.value.length > 0) {
                if (f.mb_hp.value.length < 10) {
                    alert("휴대폰 번호는 최소 10글자, 최대 11자 입니다.");
                    f.mb_hp.focus();
                    return false;
                }
            }
            // 이름 검사
            if (f.w.value == "") {
                if (f.mb_name.value.length < 1) {
                    alert("이름을 입력하십시오.");
                    f.mb_name.focus();
                    return false;
                }

                /*
                 var pattern = /([^가-힣\x20])/i;
                 if (pattern.test(f.mb_name.value)) {
                 alert("이름은 한글로 입력하십시오.");
                 f.mb_name.select();
                 return false;
                 }
                 */
            }

<?php if ($w == '' && $config['cf_cert_use'] && $config['cf_cert_req']) { ?>
                // 본인확인 체크
                if (f.cert_no.value == "") {
                    alert("회원가입을 위해서는 본인확인을 해주셔야 합니다.");
                    return false;
                }
<?php } ?>



            // E-mail 검사
            if ((f.w.value == "") || (f.w.value == "u" && f.mb_email.defaultValue != f.mb_email.value)) {
                var msg = reg_mb_email_check();
                if (msg) {
                    alert(msg);
                    f.reg_mb_email.select();
                    return false;
                }
            }

<?php if (($config['cf_use_hp'] || $config['cf_cert_hp']) && $config['cf_req_hp']) { ?>
                // 휴대폰번호 체크
                var msg = reg_mb_hp_check();
                if (msg) {
                    alert(msg);
                    f.reg_mb_hp.select();
                    return false;
                }
<?php } ?>

            if (typeof f.mb_icon != "undefined") {
                if (f.mb_icon.value) {
                    if (!f.mb_icon.value.toLowerCase().match(/.(gif)$/i)) {
                        alert("회원아이콘이 gif 파일이 아닙니다.");
                        f.mb_icon.focus();
                        return false;
                    }
                }
            }

            if (typeof (f.mb_recommend) != "undefined" && f.mb_recommend.value) {
                if (f.mb_id.value == f.mb_recommend.value) {
                    alert("본인을 추천할 수 없습니다.");
                    f.mb_recommend.focus();
                    return false;
                }

                var msg = reg_mb_recommend_check();
                if (msg) {
                    alert(msg);
                    f.mb_recommend.select();
                    return false;
                }
            }

<?php //echo chk_captcha_js();  ?>

            document.getElementById("btn_submit").disabled = "disabled";

            return true;
        }
    </script>

</div>
<!-- } 회원정보 입력/수정 끝 -->
<?php
if (!defined('_GNUBOARD_'))
    exit; // 개별 페이지 접근 불가
// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="' . $qa_skin_url . '/style.css">', 0);
?>

<section id="bo_w">
    <!-- 게시물 작성/수정 시작 { -->
    <form name="fwrite" id="fwrite" action="<?php echo $action_url ?>" onsubmit="return fwrite_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off">
        <input type="hidden" name="w" value="<?php echo $w ?>">
        <input type="hidden" name="qa_id" value="<?php echo $qa_id ?>">
        <input type="hidden" name="sca" value="<?php echo $sca ?>">
        <input type="hidden" name="stx" value="<?php echo $stx ?>">
        <input type="hidden" name="page" value="<?php echo $page ?>">
        <?php
        $option = '';
        $option_hidden = '';
        $option = '';

        if ($is_dhtml_editor) {
            $option_hidden .= '<input type="hidden" name="qa_html" value="1">';
        } else {
            $option .= "\n" . '<input type="checkbox" id="qa_html" name="qa_html" onclick="html_auto_br(this);" value="' . $html_value . '" ' . $html_checked . '>' . "\n" . '<label for="qa_html">html</label>';
        }

        echo $option_hidden;
        ?>

        <div class="tbl_frm01 tbl_wrap">
            <table>
                <tbody>
<?php if ($category_option) { ?>
                        <tr>
                            <th scope="row"><label for="qa_category">분류<strong class="sound_only">필수</strong></label></th>
                            <td>
                                <select name="qa_category" id="qa_category" required class="required" >
                                    <option value="">선택하세요</option>
    <?php echo $category_option ?>
                                </select>
                                <!-- 150811 나진수 1:1문의 카테 옆 설명 추가-->
                                <div class="qa_cate_info">※ 취소 및 반품 시 <strong >분류를 정확하게 선택</strong>해주세요!<br>※ 가상계좌로 결제하신 고객님은 <strong >환불계좌번호 계좌주명</strong>을 함께 써주셔야합니다!</div>
                            </td>
                        </tr>
<?php } ?>

                                <?php if ($cate_option) { ?>
        <tr>
            <th scope="row"><label for="qa_1">카테고리 선택<strong class="sound_only">필수</strong></label></th>
            <td>

                <select   name="qa_1" id="qa_1" required>
                    <option value="">카테고리 선택</option>
                    <?php echo $cate_option ?>
                </select>

               <label for="qa_2" style="display:none;">상품명 <strong class="sound_only">필수</strong></label>
                <select name="qa_2"  id="qa_2"  required>
                    <!-- 151013 나진수 상품명 수정시 상품 이름이 생성이 안되어서 수정 작업 시작-->
                    <?php if($write['qa_2']){ ?>
                    <option value="<?php echo $write['qa_2']; ?>"><?php echo $write['qa_2']; ?></option>
                    <?php 
                        $sql_it = "SELECT * FROM {$g5['g5_shop_item_table']} WHERE ca_id = '{$write['qa_1']}' and it_use = '1' ";		
                        $rst_it = sql_query($sql_it);
                        
                        while($row_it = mysql_fetch_assoc($rst_it)) { ?>
                        <option value="<?php echo $row_it['it_name']; ?>"><?php echo $row_it['it_name']; ?></option>
                        <?php  }  ?>
                    <?php }else{ ?>
                    <option value="">상품명</option>
                    <?php } ?>
                    <!-- 151013 나진수 상품명 수정시 상품 이름이 생성이 안되어서 수정 작업 끝-->
                </select>
            </td>
        </tr>
        <script>
	$("#qa_1").change(function() {
	var val = $(this).val();
	$.ajax({
		type: 'POST',
		url: './ajax.select.php',
		data: {
			qa_1: val
		},
		success: function(data) {
			$("#qa_2").html(data);
		},
		dataType: 'html'
	});
});
	</script>
        <?php } ?>
                    <tr>
                        <th scope="row"><label for="qa_name">이름</label></th>
                        <td>

                            <input type="text" name="qa_name" value="<?php echo $ss_qa_name ? $ss_qa_name : $member['mb_name']; ?>" style="color:gray;" readonly="readonly" id="qa_name" required class="frm_input required" maxlength="100">
                        </td>
                    </tr>

<?php if ($is_email) { ?>
                        <tr>
                            <th scope="row"><label for="qa_email">이메일</label></th>
                            <td>
                                <input type="email" name="qa_email" value="<?php echo $write['qa_email']; ?>" id="qa_email" <?php echo $req_email; ?> class="<?php echo $req_email . ' '; ?>frm_input email" maxlength="100">
                                <input type="checkbox" name="qa_email_recv" value="1" id="qa_email_recv" <?php if ($write['qa_email_recv']) echo 'checked="checked"'; ?>>
                                <label for="qa_email_recv">답변받기</label>
                            </td>
                        </tr>
                    <?php } ?>
<?php if ($is_hp) { ?>
                        <tr>
                            <th scope="row"><label for="qa_hp">휴대폰</label></th>
                            <td>
                                <input type="text" name="qa_hp" onkeyup="this.value = this.value.replace(/\D/, '')" style="color:gray;" readonly="readonly" placeholder="휴대전화 ( - 없이 숫자만 입력)" value="<?php echo $ss_qa_hp ? $ss_qa_hp : $member['mb_hp']; ?>" id="qa_hp" <?php echo $req_hp; ?> class="<?php echo $req_hp . ' '; ?>frm_input" size="30">

                                <?php if ($qaconfig['qa_use_sms']) { ?>
                                    <input type="checkbox" name="qa_sms_recv" value="1" <?php if ($write['qa_sms_recv']) echo 'checked="checked"'; ?>> 답변등록 SMS알림 수신
    <?php } ?>
                            </td>
                        </tr>
<?php } ?>

                    <tr>
                        <th scope="row"><label for="qa_subject">제목<strong class="sound_only">필수</strong></label></th>
                        <td>
                            <input type="text" name="qa_subject" value="<?php echo $write['qa_subject']; ?>" id="qa_subject" required class="frm_input required" maxlength="255">
                        </td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="qa_content">내용<strong class="sound_only">필수</strong></label></th>
                        <td class="wr_content">
<?php echo $editor_html; // 에디터 사용시는 에디터로, 아니면 textarea 로 노출  ?>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">첨부이미지 #1</th>
                        <td>
                            <input type="file" name="bf_file[1]" accept="image/*" title="파일첨부 1 :  용량 <?php echo $upload_max_filesize; ?> 이하만 업로드 가능" class="frm_file frm_input">
                            <?php if ($w == 'u' && $write['qa_file1']) { ?>
                                <input type="checkbox" id="bf_file_del1" name="bf_file_del[1]" value="1"> <label for="bf_file_del1"><?php echo $write['qa_source1']; ?> 파일 삭제</label>
<?php } ?>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">첨부이미지 #2</th>
                        <td>
                            <input type="file" name="bf_file[2]" accept="image/*" title="파일첨부 2 :  용량 <?php echo $upload_max_filesize; ?> 이하만 업로드 가능" class="frm_file frm_input">
                            <?php if ($w == 'u' && $write['qa_file2']) { ?>
                                <input type="checkbox" id="bf_file_del2" name="bf_file_del[2]" value="1"> <label for="bf_file_del2"><?php echo $write['qa_source2']; ?> 파일 삭제</label>
<?php } ?>
                        </td>
                    </tr>

                </tbody>
            </table>

            <span>※ 1장 당 5MB 이하의 이미지파일만 업로드 가능합니다.</span>
        </div>

        <div class="btn_confirm">
            <a href="<?php echo $list_href; ?>" class="btn_cancel">목록으로</a>
            <input type="submit" value="작성완료" id="btn_submit" accesskey="s" class="btn_submit">
        </div>
    </form>

    <script>
        function html_auto_br(obj)
        {
            if (obj.checked) {
                result = confirm("자동 줄바꿈을 하시겠습니까?\n\n자동 줄바꿈은 게시물 내용중 줄바뀐 곳을<br>태그로 변환하는 기능입니다.");
                if (result)
                    obj.value = "2";
                else
                    obj.value = "1";
            }
            else
                obj.value = "";
        }

        function fwrite_submit(f)
        {
<?php echo $editor_js; // 에디터 사용시 자바스크립트에서 내용을 폼필드로 넣어주며 내용이 입력되었는지 검사함    ?>

            var subject = "";
            var content = "";
            $.ajax({
                url: g5_bbs_url + "/ajax.filter.php",
                type: "POST",
                data: {
                    "subject": f.qa_subject.value,
                    "content": f.qa_content.value
                },
                dataType: "json",
                async: false,
                cache: false,
                success: function (data, textStatus) {
                    subject = data.subject;
                    content = data.content;
                }
            });

            if (subject) {
                alert("제목에 금지단어('" + subject + "')가 포함되어있습니다");
                f.qa_subject.focus();
                return false;
            }

            if (content) {
                alert("내용에 금지단어('" + content + "')가 포함되어있습니다");
                if (typeof (ed_qa_content) != "undefined")
                    ed_qa_content.returnFalse();
                else
                    f.qa_content.focus();
                return false;
            }

<?php if ($is_hp) { ?>
                var hp = f.qa_hp.value.replace(/[0-9\-]/g, "");
                if (hp.length > 0) {
                    alert("휴대폰번호는 숫자, - 으로만 입력해 주십시오.");
                    return false;
                }
<?php } ?>

            document.getElementById("btn_submit").disabled = "disabled";

            return true;
        }
    </script>
</section>
<!-- } 게시물 작성/수정 끝 -->
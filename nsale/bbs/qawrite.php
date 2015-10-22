<?php

include_once('./_common.php');
include_once(G5_EDITOR_LIB);



$ss_qa_name = get_session(ss_qa_name);
$ss_qa_hp = get_session(ss_qa_hp);

if ($w != '' && $w != 'u' && $w != 'r') {
    alert('올바른 방법으로 이용해 주십시오.');
}

$qaconfig = get_qa_config();

$g5['title'] = $qaconfig['qa_title'];
include_once('./qahead.php');







$skin_file = $qa_skin_path . '/write.skin.php';

if (is_file($skin_file)) {
    /* ==========================
      $w == a : 답변
      $w == r : 추가질문
      $w == u : 수정
      ========================== */

    if ($w == 'u' || $w == 'r') {
        $sql = " select * from {$g5['qa_content_table']} where qa_id = '$qa_id' " . $sql_search2 . $sql_search;

// 회원인 경우
        if ($is_admin || $member['mb_level'] <= '8') {
            $sql_search = "";
        } else if ($is_member) {
            $sql_search2 = " mb_id = '{$member['mb_id']}' ";
        }
        /*
          150811 나진수
         * 비회원 세션 오류 / 디비 데이터 불러들이는 멤버와 비회원 정보 값 오류로 인한 변경 mb_id='' and 추가
         * 
          else if ($qa_name && $qa_hp)
          {
          $sql_search2 = " qa_name = '$qa_name' and qa_hp = '$qa_hp' ";
          }
          else if ($ss_qa_name && $ss_qa_hp)
          {
          $sql_search2 = " qa_name = '$ss_qa_name' and qa_hp = '$ss_qa_hp' ";
          }
         *  */ else if ($ss_qa_name && $ss_qa_hp) { // 비회원인 경우 주문서번호와 비밀번호가 넘어왔다면
            $sql_search2 = "mb_id ='' and qa_name = '$ss_qa_name' and qa_hp = '$ss_qa_hp' ";
        } else { // 그렇지 않다면 로그인으로 가기

            goto_url(G5_BBS_URL . '/login.php?url=' . urlencode(G5_BBS_URL . '/qalist.php'));
        }
        $write = sql_fetch($sql);

        if ($w == 'u') {
            if (!$write['qa_id'])
                alert('게시글이 존재하지 않습니다.\\n삭제되었거나 자신의 글이 아닌 경우입니다.');

            if (!$is_admin && $member['mb_level'] < '8') {
                if ($write['qa_type'] == 0 && $write['qa_status'] == 1)
                    alert('답변이 등록된 문의글은 수정할 수 없습니다.');

                if ($write['mb_id'] != $member['mb_id']) {
                    alert('게시글을 수정할 권한이 없습니다.\\n\\n올바른 방법으로 이용해 주십시오.', G5_URL);
                }
            }
        }
    }

    // 분류
    $category_option = '';
    if (trim($qaconfig['qa_category'])) {
        $category = explode('|', $qaconfig['qa_category']);
        for ($i = 0; $i < count($category); $i++) {
            $category_option .= option_selected($category[$i], $write['qa_category']);
        }
    } else {
        alert('1:1문의 설정에서 분류를 설정해 주십시오');
    }




    //제품명 카테고리 
    $catesql = " select ca_id, ca_name from {$g5['g5_shop_category_table']} where length(ca_id) = '2' and ca_use = '1' order by ca_order, ca_id ";
    $cateresult = sql_query($catesql);
    $cate_option = '';
    for ($i = 0; $row = sql_fetch_array($cateresult); $i++) {
        $sql2 = " select ca_id, ca_name from {$g5['g5_shop_category_table']} where LENGTH(ca_id) = '2' and SUBSTRING(ca_id,1,2) = '{$row['ca_id']}' and ca_use = '1' order by ca_order, ca_id ";
        $result2 = sql_query($sql2);
        $count = mysql_num_rows($result2);

        for ($j = 0; $row2 = sql_fetch_array($result2); $j++) {
            $cate_option .= option_selected($row2['ca_id'], $write['qa_1'], $row2['ca_name']);
        }
    }



    $is_dhtml_editor = false;
    // 모바일에서는 DHTML 에디터 사용불가
    if ($config['cf_editor'] && $qaconfig['qa_use_editor'] && !G5_IS_MOBILE) {
        $is_dhtml_editor = true;
    }

    // 추가질문에서는 제목을 공백으로
    if ($w == 'r')
        $write['qa_subject'] = '';

    $content = '';
    if ($w == '') {
        $content = $qaconfig['qa_insert_content'];
    } else if ($w == 'r') {
        if ($is_dhtml_editor)
            $content = '<div><br><br><br>====== 이전 답변내용 =======<br></div>';
        else
            $content = "\n\n\n\n====== 이전 답변내용 =======\n";

        $content .= get_text($write['qa_content'], 0);
    } else {
        $content = get_text($write['qa_content'], 0);
    }

    $editor_html = editor_html('qa_content', $content, $is_dhtml_editor);
    $editor_js = '';
    $editor_js .= get_editor_js('qa_content', $is_dhtml_editor);
    $editor_js .= chk_editor_js('qa_content', $is_dhtml_editor);

    $upload_max_filesize = number_format($qaconfig['qa_upload_size']) . ' 바이트';

    $html_value = '';
    if ($write['qa_html']) {
        $html_checked = 'checked';
        $html_value = $write['qa_html'];

        if ($w == 'r' && $write['qa_html'] == 1 && !$is_dhtml_editor)
            $html_value = 2;
    }

    $is_email = false;
    $req_email = '';
    if ($qaconfig['qa_use_email']) {
        $is_email = true;

        if ($qaconfig['qa_req_email'])
            $req_email = 'required';

        if ($w == '' || $w == 'r')
            $write['qa_email'] = $member['mb_email'];

        if ($w == 'u' && $is_admin && $write['qa_type'])
            $is_email = false;
    }

    $is_hp = false;
    $req_hp = '';
    if ($qaconfig['qa_use_hp']) {
        $is_hp = true;

        if ($qaconfig['qa_req_hp'])
            $req_hp = 'required';

        if ($w == '' || $w == 'r')
            $write['qa_hp'] = $qa_hp;

        if ($w == 'u' && $is_admin && $write['qa_type'])
            $is_hp = false;
    }

    $list_href = G5_BBS_URL . '/qalist.php' . preg_replace('/^&amp;/', '?', $qstr);

    $action_url = https_url(G5_BBS_DIR) . '/qawrite_update.php';

    include_once($skin_file);
} else {
    echo '<div>' . str_replace(G5_PATH . '/', '', $skin_file) . '이 존재하지 않습니다.</div>';
}

include_once('./qatail.php');
?>
<?php
include_once('./_common.php');
defined("_GNUBOARD_");


$qaconfig = get_qa_config();


$g5['title'] = $qaconfig['qa_title'];
include_once('./qahead.php');



$ss_qa_name = get_session(ss_qa_name);
$ss_qa_hp = get_session(ss_qa_hp);

$skin_file = $qa_skin_path.'/list.skin.php';

$category_option = '';
if ($qaconfig['qa_category']) {
    $category_href = G5_BBS_URL.'/qalist.php';

    $category_option .= '<li><a href="'.$category_href.'"';
    if ($sca=='')
        $category_option .= ' id="bo_cate_on"';
    $category_option .= '>전체</a></li>';

    $categories = explode('|', $qaconfig['qa_category']); // 구분자가 | 로 되어 있음
    for ($i=0; $i<count($categories); $i++) {
        $category = trim($categories[$i]);
        if ($category=='') continue;
        $category_msg = '';
        $category_option .= '<li><a href="'.($category_href."?sca=".urlencode($category)).'"';
        if ($category==$sca) { // 현재 선택된 카테고리라면
            $category_option .= ' id="bo_cate_on"';
            $category_msg = '<span class="sound_only">열린 분류 </span>';
        }
        $category_option .= '>'.$category_msg.$category.'</a></li>';
    }
}

if(is_file($skin_file)) {
    $sql_common = " from {$g5['qa_content_table']}  where ";
    $sql_search = " and qa_type = '0' ";
 
    
// 회원인 경우
if($is_admin||$member['mb_level']>='8'){
	$sql_search = "qa_type = '0' ";
}
else if ($is_member)
{
    $sql_search2 = " mb_id = '{$member['mb_id']}' ";
}
/*
150811 나진수
 * 비회원 세션 오류 / 디비 데이터 불러들이는 멤버와 비회원 정보 값 오류로 인한 변경 mb_id='' and 추가
 * 
else if ($qa_name && $qa_hp) // 비회원인 경우 주문서번호와 비밀번호가 넘어왔다면
{
    $sql_search2 = " qa_name = '$qa_name' and qa_hp = '$qa_hp' ";
}
else if ($ss_qa_name && $ss_qa_hp) // 비회원인 경우 주문서번호와 비밀번호가 넘어왔다면
{
    $sql_search2 = " qa_name = '$ss_qa_name' and qa_hp = '$ss_qa_hp' ";
}
 *  */
else if ($ss_qa_name && $ss_qa_hp) // 비회원인 경우 주문서번호와 비밀번호가 넘어왔다면
{
    $sql_search2 = " mb_id='' and qa_name ='$ss_qa_name' and qa_hp = '$ss_qa_hp' ";
}

else // 그렇지 않다면 로그인으로 가기
{
    
    goto_url(G5_BBS_URL.'/login.php?url='.urlencode(G5_BBS_URL.'/qalist.php'));
}


    if($sca) {
        if (preg_match("/[a-zA-Z]/", $sca))
            $sql_search .= " and INSTR(LOWER(qa_category), LOWER('$sca')) > 0 ";
        else
            $sql_search .= " and INSTR(qa_category, '$sca') > 0 ";
    }

    $stx = trim($stx);
    if($stx) {
        if (preg_match("/[a-zA-Z]/", $stx))
            $sql_search .= " and ( INSTR(LOWER(qa_subject), LOWER('$stx')) > 0 or INSTR(LOWER(qa_content), LOWER('$stx')) > 0 )";
        else
            $sql_search .= " and ( INSTR(qa_subject, '$stx') > 0 or INSTR(qa_content, '$stx') > 0 ) ";
    }

    $sql_order = " order by qa_num ";

    $sql = " select count(*) as cnt
                $sql_common
                    $sql_search2
                $sql_search ";
    $row = sql_fetch($sql);
    $total_count = $row['cnt'];

    $page_rows = G5_IS_MOBILE ? $qaconfig['qa_mobile_page_rows'] : $qaconfig['qa_page_rows'];
    $total_page  = ceil($total_count / $page_rows);  // 전체 페이지 계산
    if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
    $from_record = ($page - 1) * $page_rows; // 시작 열을 구함
   
    
    
    $sql = " select *
                $sql_common
                $sql_search2    
                $sql_search
                    
                $sql_order
                limit $from_record, $page_rows ";
    $result = sql_query($sql);
    
    
    $list = array();
    $num = $total_count - ($page - 1) * $page_rows;
    $subject_len = G5_IS_MOBILE ? $qaconfig['qa_mobile_subject_len'] : $qaconfig['qa_subject_len'];
    for($i=0; $row=sql_fetch_array($result); $i++) {
        $list[$i] = $row;

        $list[$i]['category'] = get_text($row['qa_category']);
        $list[$i]['subject'] = conv_subject($row['qa_subject'], $subject_len, '…');
        if ($stx) {
            $list[$i]['subject'] = search_font($stx, $list[$i]['subject']);
        }

        $list[$i]['view_href'] = G5_BBS_URL.'/qaview.php?qa_id='.$row['qa_id'].$qstr;

        $list[$i]['icon_file'] = '';
        if(trim($row['qa_file1']) || trim($row['qa_file2']))
            $list[$i]['icon_file'] = '<img src="'.$qa_skin_url.'/img/icon_file.gif">';

        $list[$i]['name'] = get_text($row['qa_name']);
        $list[$i]['date'] = substr($row['qa_datetime'], 2, 8);

        $list[$i]['num'] = $num - $i;
    }

    $is_checkbox = false;
    $admin_href = '';
    if($is_admin||$member['mb_level']>='8') {
        $is_checkbox = true;
        $admin_href = G5_ADMIN_URL.'/shop_admin/admin_qalist.php';
    }

    $list_href = G5_BBS_URL.'/qalist.php';
    $write_href = G5_BBS_URL.'/qawrite.php';

    $list_pages = preg_replace('/(\.php)(&amp;|&)/i', '$1?', get_paging(G5_IS_MOBILE ? $qaconfig['qa_mobile_page_rows'] : $qaconfig['qa_page_rows'], $page, $total_page, './qalist.php'.$qstr.'&amp;page='));

    $stx = get_text(stripslashes($stx));
    include_once($skin_file);
} else {
    echo '<div>'.str_replace(G5_PATH.'/', '', $skin_file).'이 존재하지 않습니다.</div>';
}

include_once('./qatail.php');
?>
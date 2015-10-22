<?php

$sub_menu = '400680';

include_once('./_common.php');
defined("_GNUBOARD_");

auth_check($auth[$sub_menu], "r");
$qaconfig = get_qa_config();


$g5['title'] = $qaconfig['qa_title'];
include_once('./admin_qahead.php');

$skin_file = $admin_qa_skin_path . '/list.skin.php';

$category_option = '';
if ($qaconfig['qa_category']) {
    $category_href = G5_ADMIN_URL . '/shop_admin/admin_qalist.php';

    $category_option .= '<li><a href="' . $category_href . '"';
    if ($sca == '')
        $category_option .= ' id="bo_cate_on"';
    $category_option .= '>전체</a></li>';

    $categories = explode('|', $qaconfig['qa_category']); // 구분자가 | 로 되어 있음
    for ($i = 0; $i < count($categories); $i++) {
        $category = trim($categories[$i]);
        if ($category == '')
            continue;
        $category_msg = '';
        $category_option .= '<li><a href="' . ($category_href . "?sca=" . urlencode($category) . "&sca_id=" . $sca_id) . '"';
        if ($category == $sca) { // 현재 선택된 카테고리라면
            $category_option .= ' id="bo_cate_on"';
            $category_msg = '<span class="sound_only">열린 분류 </span>';
        }
        $category_option .= '>' . $category_msg . $category . '</a></li>';
    }
    // 150828 나진수 검색후 페이징 이동시 오류로 인한 추가
    $qstr .="&sca=" . $sca;
}
// 150828 나진수 카테고리 분류 추가 시작
$ca_id_href = G5_ADMIN_URL . '/shop_admin/admin_qalist.php';

$ca_id_option .= '<li><a href="' . $ca_id_href . '"';
if ($sca_id == '')
    $ca_id_option .= ' id="bo_cate_on"';
$ca_id_option .= '>전체</a></li>';
$sql_ca_id = "select qa_1 from {$g5['qa_content_table']} where qa_type = '0' and qa_1 like '%0' group by substr(qa_1,1,2)";
$result_ca_id = sql_query($sql_ca_id);
for ($i = 0; $row_ca_id = sql_fetch_array($result_ca_id); $i++) {

    $row_ca_id = substr($row_ca_id['qa_1'], 0, 2);
    $ca_id_option .= '<li><a href="' . ($list_href . "?sca=" . $sca . "&stx=" . $stx . "&sca_id=" . $row_ca_id) . '"';
    if ($row_ca_id == $sca_id) { // 현재 선택된 카테고리라면
        $ca_id_option .= ' id="bo_cate_on"';
    }

    if ($row_ca_id == '10') {
        $row_ca_name = '뷰티상품';
    } else if ($row_ca_id == '20') {
        $row_ca_name = '일반';
    }

    $ca_id_option .= '>' . $row_ca_name . '</a></li>';
}

$qstr .="&sca_id=" . $sca_id;
// 150828 나진수 카테고리 분류 추가 끝

if (is_file($skin_file)) {
    $sql_common = " from {$g5['qa_content_table']}  where ";
    $sql_search = " qa_type = '0' ";




    if ($sca) {
        if (preg_match("/[a-zA-Z]/", $sca))
            $sql_search .= " and INSTR(LOWER(qa_category), LOWER('$sca')) > 0 ";
        else
            $sql_search .= " and INSTR(qa_category, '$sca') > 0 ";
    }
    // 150820 나진수 상품명 / 글쓴이 / 핸드폰 번호 검색 가능
    $stx = trim($stx);
    if ($stx) {
        if (preg_match("/[a-zA-Z]/", $stx))
            $sql_search .= " and ( INSTR(LOWER(qa_subject), LOWER('$stx')) > 0 or INSTR(LOWER(qa_content), LOWER('$stx')) > 0 or INSTR(LOWER(qa_name), LOWER('$stx')) > 0 or INSTR(LOWER(qa_hp), LOWER('$stx')) > 0 or INSTR(LOWER(qa_2), LOWER('$stx')) > 0)";
        else
            $sql_search .= " and ( INSTR(qa_subject, '$stx') > 0 or INSTR(qa_content, '$stx') > 0 or INSTR(qa_name, '$stx') > 0 or INSTR(qa_hp, '$stx') > 0 or INSTR(qa_2, '$stx') > 0 ) ";
    }
    // 150828 나진수 카테고리 분류 검색 추가 시작
    if ($sca_id) {
        $sql_search .= "and substr(qa_1,1,2)='$sca_id'";
    }
    // 150828 나진수 카테고리 분류 검색 추가 끝
    
    // 150831 나진수 글쓰기 상태 정렬이 안되어서 추가함 시작
    if (!$sst) {
        $sst = "qa_num";
        $sod = "";
    }
    
    $sql_order = " order by $sst $sod ";
    // 150831 나진수 글쓰기 상태 정렬이 안되어서 추가함 끝

    $sql = " select count(*) as cnt
                $sql_common
                $sql_search ";
    $row = sql_fetch($sql);
    $total_count = $row['cnt'];

    $page_rows = $qaconfig['qa_page_rows'];
    $total_page = ceil($total_count / $page_rows);  // 전체 페이지 계산
    if ($page < 1) {
        $page = 1;
    } // 페이지가 없으면 첫 페이지 (1 페이지)
    $from_record = ($page - 1) * $page_rows; // 시작 열을 구함
    if ($total_count == 0) {
        if (!$is_member) // 회원일 경우는 메인으로 이동
            alert('문의글이 없습니다.', G5_BBS_URL . '/qawrite.php');
    }


    $sql = " select *
                $sql_common
                $sql_search
                $sql_order
                limit $from_record, $page_rows ";
    $result = sql_query($sql);


    $list = array();
    $num = $total_count - ($page - 1) * $page_rows;
    $subject_len = $qaconfig['qa_subject_len'];
    for ($i = 0; $row = sql_fetch_array($result); $i++) {
        $list[$i] = $row;

        $list[$i]['category'] = get_text($row['qa_category']);
        $list[$i]['subject'] = conv_subject($row['qa_subject'], $subject_len, '…');
        if ($stx) {
            $list[$i]['subject'] = search_font($stx, $list[$i]['subject']);
        }

        $list[$i]['view_href'] = G5_ADMIN_URL . '/shop_admin/admin_qaview.php?qa_id=' . $row['qa_id'] . $qstr;

        $list[$i]['icon_file'] = '';
        if (trim($row['qa_file1']) || trim($row['qa_file2']))
            $list[$i]['icon_file'] = '<img src="' . $admin_qa_skin_url . '/img/icon_file.gif">';

        $list[$i]['name'] = get_text($row['qa_name']);
        $list[$i]['date'] = substr($row['qa_datetime'], 2, 8);

        $list[$i]['num'] = $num - $i;
    }

    $is_checkbox = true;



    $list_href = G5_ADMIN_URL . '/shop_admin/admin_qalist.php';
    $write_href = G5_ADMIN_URL . '/shop_admin/admin_qawrite.php';

    $list_pages = preg_replace('/(\.php)(&amp;|&)/i', '$1?', get_paging($qaconfig['qa_page_rows'], $page, $total_page, './admin_qalist.php?' . $qstr . '&amp;page='));

    $stx = get_text(stripslashes($stx));
    include_once($skin_file);
} else {
    echo '<div>' . str_replace(G5_PATH . '/', '', $skin_file) . '이 존재하지 않습니다.</div>';
}

include_once('./admin_qatail.php');
?>
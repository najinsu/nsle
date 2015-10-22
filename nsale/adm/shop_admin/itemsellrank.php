<?php
// 150918 나진수 테이블 형식에서 div 형식으로 변경 및 상품 옵션별로 되어져 있는걸 아이템 및 옵션별로 변경 및 검색 부분 상품명 까지 검색가능하도록 추가 
// 전체적 변경 함

$sub_menu = '500100';
include_once('./_common.php');


auth_check($auth[$sub_menu], "r");

$g5['title'] = '상품판매순위';
include_once (G5_ADMIN_PATH . '/admin.head.php');
include_once(G5_PLUGIN_PATH . '/jquery-ui/datepicker.php');

if (!$to_date)
    $to_date = date("Ymd", time());

if ($sort1 == "")
    $sort1 = "it_price_it";
if ($sort2 == "")
    $sort2 = "desc";

$sql = "select sc.it_id, sc.it_name, sc.ct_time, si.ca_id, si.ca_id2, si.ca_id3,
    sum(if((ct_status in ('입금','준비','배송','완료')),(sc.ct_price+sc.io_price)*sc.ct_qty,0)) AS it_price_it,
                    sum(if((ct_status = '주문'),ct_qty,0)) AS ct_status_2, 
                    sum(if((ct_status = '입금'),ct_qty,0)) AS ct_status_3,
                    sum(if((ct_status = '준비'),ct_qty,0)) AS ct_status_4, 
                    sum(if((ct_status = '배송'),ct_qty,0)) AS ct_status_5,
                    sum(if((ct_status = '완료'),ct_qty,0)) AS ct_status_6,
                    sum(if((ct_status = '취소'),ct_qty,0)) AS ct_status_7,
                    sum(if((ct_status = '반품'),ct_qty,0)) AS ct_status_8,
                    sum(if((ct_status = '품절'),ct_qty,0)) AS ct_status_9,
                    sum(if((ct_status = '쇼핑'),0,ct_qty)) AS ct_status_10
        from g5_shop_cart sc left join g5_shop_item si on(sc.it_id=si.it_id) left join g5_shop_order so on(sc.od_id=so.od_id) ";
$sql .= " where sc.it_id = si.it_id ";
if ($fr_date && $to_date) {
    $fr = preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})/", "\\1-\\2-\\3", $fr_date);
    $to = preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})/", "\\1-\\2-\\3", $to_date);
    $sql .= " and sc.ct_time between '$fr 00:00:00' and '$to 23:59:59' ";
}
if ($sel_ca_id) {
    $sql .= "and ( si.ca_id like '$sel_ca_id%' or  si.ca_id2 like '$sel_ca_id%' or  si.ca_id3 like '$sel_ca_id%') ";
}

if ($search != "") {
    if ($sel_field != "") {
        $sql .= " and $sel_field like '%$search%' ";
    }
}

$sql .= " group by it_id
          order by $sort1 $sort2 ";
$result = sql_query($sql);
$total_count = mysql_num_rows($result);

$rows = 10;
$total_page = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) {
    $page = 1;
} // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$rank = ($page - 1) * $rows;

$sql = $sql . " limit $from_record, $rows ";
$result = sql_query($sql);

//$qstr = 'page='.$page.'&amp;sort1='.$sort1.'&amp;sort2='.$sort2;
$qstr1 = $qstr . '&amp;sort1=' . $sort1 . '&amp;sort2=' . $sort2 . '&amp;fr_date=' . $fr_date . '&amp;to_date=' . $to_date . '&amp;sel_ca_id=' . $sel_ca_id;

$listall = '<a href="' . $_SERVER['SCRIPT_NAME'] . '" class="ov_listall">전체목록</a>';
?>

<div class="local_ov01 local_ov">
<?php echo $listall; ?>
    등록상품 <?php echo $total_count; ?>건
</div>

<form name="flist" class="local_sch01 local_sch">
    <input type="hidden" name="doc" value="<?php echo $doc; ?>">
    <input type="hidden" name="sort1" value="<?php echo $sort1; ?>">
    <input type="hidden" name="sort2" value="<?php echo $sort2; ?>">
    <input type="hidden" name="page" value="1">
    <input type="hidden" name="sel_field" value="<?php echo $sel_field; ?>">
    <input type="hidden" name="search" value="<?php echo $search; ?>">

    <label for="sel_ca_id" class="sound_only">검색대상</label>
    <select name="sel_ca_id" id="sel_ca_id">
        <option value=''>전체분류</option>
        <?php
        $sql1 = " select ca_id, ca_name from {$g5['g5_shop_category_table']} order by ca_order, ca_id ";
        $result1 = sql_query($sql1);
        for ($i = 0; $row1 = mysql_fetch_array($result1); $i++) {
            $len = strlen($row1['ca_id']) / 2 - 1;
            $nbsp = "";
            for ($i = 0; $i < $len; $i++)
                $nbsp .= "&nbsp;&nbsp;&nbsp;";
            echo '<option value="' . $row1['ca_id'] . '" ' . get_selected($sel_ca_id, $row1['ca_id']) . '>' . $nbsp . $row1['ca_name'] . '</option>' . PHP_EOL;
        }
        ?>
    </select>

    기간설정
    <label for="fr_date" class="sound_only">시작일</label>
    <input type="text" name="fr_date" value="<?php echo $fr_date ? $fr_date : $to_date; ?>" id="fr_date" required class="required frm_input" size="10" maxlength="8"> 에서
    <label for="to_date" class="sound_only">종료일</label>
    <input type="text" name="to_date" value="<?php echo $to_date; ?>" id="to_date" required class="required frm_input" size="10" maxlength="8"> 까지

    <label for="sel_field" class="sound_only">검색대상</label>
    <select name="sel_field" id="sel_field">
        <option value="si.it_name" <?php echo get_selected($sel_field, 'si.it_name'); ?>>상품명</option>
        <option value="si.it_id" <?php echo get_selected($sel_field, 'si.it_id'); ?>>상품코드</option>
    </select>

    <label for="search" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
    <input type="text" name="search" value="<?php echo $search; ?>" class="frm_input">
    <input type="submit" value="검색" class="btn_submit">
</form>
<div class="local_desc01 local_desc">
    <p>판매량을 합산하여 상품판매순위를 집계합니다.</p>
</div>

<div class="btn_add01 btn_add">
    <a href="./itemlist.php" class="btn_add01 btn_add_optional">상품등록</a>
    <a href="./itemstocklist.php" class="btn_add01 btn_add_optional">상품재고관리</a>
</div>

<div id="sellrank">

    <div class="sellrank_title">
        <ul>
            <li>순위</li>
            <li class="it_name">상품명</li>
            <li>주문</li>
            <li>입금</li>
            <li>준비</li>
            <li>배송</li>
            <li>완료</li>
            <li>취소</li>
            <li>반품 </li>
            <li>품절</li>
            <li>합계 </li>
            <li class="it_sum">매출액(입금기준)</li>
        </ul>
    </div>

    <?php
    
    for ($i = 0; $row = mysql_fetch_array($result); $i++) {
        
        $num = $rank + $i + 1;
        ?>

        <div class="it_sellrank">
            <button type="button" class="io_view">
                <ul>
                    <li><?php echo $num; ?></li>
                    <li class="it_name" style="text-align: left;"><?php echo get_it_image($row['it_id'], 50, 50); ?> <?php echo strip_tags($row['it_name']); ?></li>
                    <li><?php echo $row['ct_status_2']; ?></li>
                    <li><?php echo $row['ct_status_3']; ?></li>
                    <li><?php echo $row['ct_status_4']; ?></li>
                    <li><?php echo $row['ct_status_5']; ?></li>
                    <li><?php echo $row['ct_status_6']; ?></li>
                    <li><?php echo $row['ct_status_7']; ?></li>
                    <li><?php echo $row['ct_status_8']; ?></li>
                    <li><?php echo $row['ct_status_9']; ?></li>
                    <li><?php echo $row['ct_status_10']; ?></li>
                    <li class="it_sum"><?php echo number_format($row['it_price_it']); ?>원</li>
                </ul>
            </button>


            <?php
            $sql_io = "select io_id, 
                sum(if((ct_status in ('입금','준비','배송','완료')), (sc_io.ct_price+sc_io.io_price)*sc_io.ct_qty,0)) AS it_price_io,
                    sum(if((ct_status = '주문'),ct_qty,0)) AS io_status_2, 
                    sum(if((ct_status = '입금'),ct_qty,0)) AS io_status_3,
                    sum(if((ct_status = '준비'),ct_qty,0)) AS io_status_4, 
                    sum(if((ct_status = '배송'),ct_qty,0)) AS io_status_5,
                    sum(if((ct_status = '완료'),ct_qty,0)) AS io_status_6,
                    sum(if((ct_status = '취소'),ct_qty,0)) AS io_status_7,
                    sum(if((ct_status = '반품'),ct_qty,0)) AS io_status_8,
                    sum(if((ct_status = '품절'),ct_qty,0)) AS io_status_9,
                    sum(if((ct_status = '쇼핑'),0,ct_qty)) AS io_status_10
                    from g5_shop_cart sc_io left join g5_shop_order so_io on (sc_io.od_id=so_io.od_id) where it_id ='{$row['it_id']}' ";
if ($fr_date && $to_date) {
    $fr = preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})/", "\\1-\\2-\\3", $fr_date);
    $to = preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})/", "\\1-\\2-\\3", $to_date);
    $sql_io .= " and sc_io.ct_time between '$fr 00:00:00' and '$to 23:59:59' ";
}
$sql_io .='group by io_id';
            $result_io = sql_query($sql_io);
            for ($y = 0; $row_io = mysql_fetch_array($result_io); $y++) {
              $cadf +=  $row_io['io_status_2']; 
                
                ?>

                <div class="io_con" >
                    <ul>
                        <li>-</li>
                        <li class="it_name" style="text-align: left;">&nbsp;<?php echo $row_io['io_id']; ?></li>
                        <li><?php echo $row_io['io_status_2']; ?></li>
                        <li><?php echo $row_io['io_status_3']; ?></li>
                        <li><?php echo $row_io['io_status_4']; ?></li>
                        <li><?php echo $row_io['io_status_5']; ?></li>
                        <li><?php echo $row_io['io_status_6']; ?></li>
                        <li><?php echo $row_io['io_status_7']; ?></li>
                        <li><?php echo $row_io['io_status_8']; ?></li>
                        <li><?php echo $row_io['io_status_9']; ?></li>
                        <li><?php echo $row_io['io_status_10']; ?></li>
                        
                        <li class="it_sum"><?php echo number_format($row_io['it_price_io']); ?>원</li>
                    </ul>
                </div>     
            <?php
        }
        ?>
        </div>
        <?php
    }

    if ($i == 0) {
        echo '<div class="empty_table">자료가 없습니다.</div>';
    }
    ?>

</div>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr1&amp;page="); ?>

<div class="btn_add01 btn_add" style="margin-top:10px;">
    <a href="<?php echo G5_ADMIN_URL; ?>/shop_admin/itemsellrank.php" class="btn_add01 btn_add_optional">목록</a>
</div>

<script>
    $(function () {
        $("#fr_date, #to_date").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "yymmdd",
            showButtonPanel: true,
            yearRange: "c-99:c+99",
            maxDate: "+0d"
        });
    });
</script>
<script>
    $(function () {


        $(".io_view").click(function () {
            var $con = $(this).siblings(".io_con");
            if ($con.is(":visible")) {
                $con.hide();
            } else {
                $con.show();
            }
        });


    });
</script>
<?php
include_once (G5_ADMIN_PATH . '/admin.tail.php');
?>

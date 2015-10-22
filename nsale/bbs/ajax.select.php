<?php
include_once("_common.php");
$qa_1 = escape_trim($_POST['qa_1']);

$sqla = "SELECT		*
		FROM		{$g5['g5_shop_item_table']} 
		WHERE		 ca_id = '{$qa_1}' and it_use = '1' ";
		
$rsta = sql_query($sqla);
?>
<option value="">제품선택</option>
<!-- 150908 나진수 사용자 판매 종료 된상품 선택 가능하도록 추가 시작-->
<option value="판매 종료된 상품">판매 종료된 상품</option>
<!-- 150908 나진수 사용자 판매 종료 된상품 선택 가능하도록 추가 끝-->
<?php
while($rowaa = mysql_fetch_assoc($rsta)) { ?>
<option value="<?php echo $rowaa['it_name']; ?>"><?php echo strip_tags($rowaa['it_name']); ?></option>
<?php  } ?>

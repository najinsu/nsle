<?php
include_once('./_common.php');

set_session('ss_qa_name', $qa_name);
set_session('ss_qa_hp', $qa_hp);
goto_url(G5_BBS_URL."/qalist.php");

?>

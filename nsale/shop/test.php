<?php
include_once('./_common.php');

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//echo "Linux";
//
//$temp = `ifconfig`;
//
//preg_match('/inet addr:([d.]+)/',$temp,$match);
//
//echo $match[1]; 


//echo "sss<br>";
//echo $_SERVER['SERVER_ADDR']."<br>";
//echo $_SERVER['REMOTE_ADDR']."<br>";
//echo "HTTP_X_FORWARDED_FOR<br>";
//
//if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)){
//    echo  $_SERVER["HTTP_X_FORWARDED_FOR"];  
//}else if (array_key_exists('REMOTE_ADDR', $_SERVER)) { 
//    echo $_SERVER["REMOTE_ADDR"]; 
//}else if (array_key_exists('HTTP_CLIENT_IP', $_SERVER)) {
//    echo $_SERVER["HTTP_CLIENT_IP"]; 
//}

//
//$url =  "http://14.63.169.254/lsy/Api/test.php";
//$data = array( "MDCODE" => "ss");
//
//$ch = curl_init();
//$postvars = ''; 
//foreach($data as $key=>$value) {
//  $postvars.= $key.'='.$value.'&';
//}
//$postvars = substr($postvars, 0, -1);
//curl_setopt($ch,CURLOPT_URL, $url);
//curl_setopt($ch,CURLOPT_POST, count($data));
//curl_setopt($ch,CURLOPT_POSTFIELDS, $postvars);
//curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
//curl_setopt($ch,CURLOPT_CONNECTTIMEOUT, 3);
//curl_setopt($ch,CURLOPT_TIMEOUT, 20);
//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded; charset=UTF-8')); 
//$response = curl_exec($ch);
//curl_close ($ch);
//
//echo $response;

echo G5_URL;



//$referer = parse_url($_SERVER['HTTP_REFERER']);
//$ip = gethostbyname($referer[host]); 
//echo "$referer[host]"; // 도메인 출력 
//echo "$ip"; // ip 출력
    
    ?>
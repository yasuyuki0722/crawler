<?php
require_once('function.php');
date_default_timezone_set('Asia/Tokyo');
echo date('Y/m/d/H/i/s').'actopi crawler start >> ';
$url = 'http://aucfan.com/article/?paged=';

$page_number=pageNumberGet();
var_dump($page_number);



/更新分取得
$url_array=getLink($url, $page_number);
//inputUrlData($url_array);
var_dump($url_array);

//更新文url先のhtmlを取得
getHtml($url_array);

//更新分の中から必要な要素を取得してtsvに
getTopic($url_array);

//DBに書き込む
inputData($url_array);
echo date('Y/m/d/H/i/s').'actopi crawler end';
return;
?>

<?php
date_default_timezone_set('Asia/Tokyo');
echo date('Y/m/d/H/i/s').'actopi crawler start >> ';
$url = 'http://aucfan.com/article/?paged=';
$page_number=pageNumberGet();
var_dump($page_number);
//exit;

//url_data.txtの最新のurlをとる
//$last_url=getLastUrl();
//var_dump($last_url);
//更新分取得
$url_array=getLink($url, $page_number);
//inputUrlData($url_array);
var_dump($url_array);

//更新分をurl_data.txtに書き込む
//inputUrlData($url_data);

//更新文url先のhtmlを取得
getHtml($url_array);

//更新分の中から必要な要素を取得してtsvに
getTopic($url_array);


//DBに書き込む
inputData($url_array);
echo date('Y/m/d/H/i/s').'actopi crawler end';
return;
/**
 *オクトピが何ページあるか取得して返す
 *@return $last_number
 */
function pageNumberGet(){
  $script='sh page_number.sh';
  $last_number=rtrim(shell_exec($script)); 
  return $last_number;
}
/**
 *@param $url :aucfan.com/article/
 *@param $last_u:過去分で一番新しい記事のurl
 *@param $page_num:articleのページ数
 *@return $url_array: key:トピのid value:トピのurlの配列
 */
function getLink($url, $page_num){
  $url_array=array();
  $url_pattern = "/<a[^>]+href=[\"']?([-_.!~*'()a-zA-Z0-9;\/?:\@&=+\$,%#]+)[\"']?[^>]*class=\"box_link\">$/ims";
  for ($i=1;$i<=$page_num;$i++){
    $html = file_get_contents($url.$i);
    $html = mb_convert_encoding($html,"UTF-8",mb_detect_encoding($html));
    preg_match_all($url_pattern, $html, $preg, PREG_PATTERN_ORDER);
    foreach($preg[1] as $value){
      preg_match('/^http:\/\/aucfan.com\/article\/(.*)\/$/', $value, $name);
      if (is_file('htmldata/'.$name[1].'.html')){
        break;
      }
      $url_array[$name[1]] = $value;
    }
  //sleep(1);
  }
  $url_array=array_reverse($url_array, true);
  return $url_array;
}

/**
 *@return 過去分で一番新しい記事のurl
 *
 */
function getLastUrl(){
  $fp=fopen("url_data.text", 'r');
  $last_url=rtrim(fgets($fp));
  fclose($fp);
  return $last_url;
}

/**
 *新規トピックのurlをurl_data.txtに書き込む
 *@param $url:urlの配列
 *
 */
function inputUrlData($url){
  $fp=fopen('url_data.text', 'a');
  foreach($url as $value){
    fwrite($fp, $value."\n");
  } 
  fclose($fp);
}
/**
 * 新規トピックの詳細ページに飛んでhtmlファイルに保存
 *@param $url :新規トピックのurlの配列
 *@param $url_id:保存するhtmlファイルの名前
 *@return $url_file_name:保存したhtmlファイルの名前の配列
 */
function getHtml($url){
  foreach($url as $key =>  $value){
    $script='wget -O "htmldata/"'.$key.'.html '.$value;
    shell_exec($script);
    sleep(0.1);
  }
  return; 
}

/**
 * topic.shで各htmlファイルから要素を抽出し、actopi_url.tsvmに書き込む
 *@param $url_id:新規トピックのhtmlファイル名
 *
 */
function getTopic($url_name){
  foreach($url_name as $name  =>  $value){
    $script='sh topic.sh htmldata/'.$name.'.html '.$value.' '.$name;
    shell_exec($script);
  }
  return;
}

/**
 *DBに登録してactopi_url.tsvを削除
 *
 */
function inputData(){
  $script='sh load.sh';
  shell_exec($script);
}?>

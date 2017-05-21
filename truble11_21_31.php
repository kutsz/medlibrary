<?php
require_once 'db.php';
require 'phpQuery.php';


 function strip($string)
    {
        $string = str_replace('&nbsp;', ' ', $string);
        $string = strip_tags($string);
        $string = preg_replace('/([^\pL\pN\pP\pS\pZ])|([\xC2\xA0])/u', ' ', $string);
        $string = str_replace('  ', ' ', $string);
        $string = trim($string);
        while (strripos($string, '  ') !== false)
        {
            $string = str_replace('  ', ' ', $string);
        }
        return $string;
    }

function truble($url)
{

    $file = file_get_contents($url);
    $doc = phpQuery::newDocument($file);
    //var_dump($doc->find('#content > article > footer')->is('.wp-pagenavi'));
//if($doc->is('#content > article > footer > div.wp-pagenavi'))
//if($doc->is('#content div.wp-pagenavi'))
//if($doc->hasClass('.wp-pagenavi'))
{
  $page = $doc->find('#content > article > footer > div.wp-pagenavi > span.pages')->text();
  var_dump($page);
  echo '<br>';

  //$page = html_entity_decode($page);
  // $page = htmlspecialchars_decode($page);
  // $page = trim($page);
  //$page=str_replace('&nbsp;','', $page);

  // $page = strrev($page);
  // $page = (int)$page;
  //$page = (int)(strrev($page));
  //$page = str_replace("&nbsp;", ",");
  // $page = preg_replace('/&nbsp;/', 'i', $page);
  //$page = preg_replace("/&#?[a-z0-9]+;/i","",$page);
    // $page = mb_split('&nbsp;', $page);
    // var_dump($page);
    // echo '<br>';
echo "$page".'<br>';
echo strlen($page).'<br>';
echo "$page[7]".'<br>';
//$page = strstr($page, "$page[13]");
echo "$page".'<br>';

    $html = $doc->find('#content > article');
    $html=pq($html);
    $html->find('script')->remove();

    foreach ($doc->find('#content img') as $img) {
        $img= pq($img);

        $img_url = $img->attr('src');

        $img_url = 'http:'.$img_url; // url foto native
        echo $img_url.'<br>'; //test

        $image_name = basename($img_url);
        if (!file_exists('img/'.$image_name)) {
            file_put_contents('img/'.$image_name, file_get_contents($img_url));
        }
        $img_url_local = 'img/'.$image_name; // url foto local

        echo 'local: '.$img_url_local.'<br>'; //test



        $img->attr('src', $img_url_local);
        $img->parent()->filter('a')->attr('href', $img_url_local).'<br>';
        echo $img->parent()->parent()->filter('div.post_image_img')->next()->filter('div.post_image_attr')->find('a')->attr('href', $img_url_local);


        $img_url_text_src = $img->attr('src');//test

        echo 'src: '.$img_url_text_src.'<br>';//test
    }

//
 }

// else{
//   echo 'OOOOOPs';
// }
      //   $fp = fopen('html_cont1.txt', 'a');//test
      // fwrite($fp, "$i".'-----------------------------------------------------------');
      //   fwrite($fp, $html);
      //   fclose($fp);
}


$link = open_database_connection();



$id = 11;

    $drug_arr = getDrugReference($id);
// var_dump($drug_arr);
 echo $drug_arr['drug_name'].'<br>';
// echo $drug_arr['drug_reference'].'<br><br>';
truble($drug_arr['drug_reference']);

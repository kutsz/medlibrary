<?php
require_once 'db.php';
require 'phpQuery.php';


// function numOfpage($url)
// {
//     $file = file_get_contents($url);
//     $doc = phpQuery::newDocument($file);
//     $numOfpage = $doc->find('#content > article > footer > div.wp-pagenavi > span.pages')->text();
//     return (int)(strrev($numOfpage));
// }

  function content($url, $i)
  {
      echo $i.'<br>';
      $file = file_get_contents($url);
      $doc = phpQuery::newDocument($file);
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


      $fp = fopen('html_cont1.txt', 'a');//test
      fwrite($fp, "$i".'-----------------------------------------------');
      fwrite($fp, $html);
      fclose($fp);


      $next = $doc->find('.wp-pagenavi .current')->next()->attr('href');
      if (!empty($next)) {
          $i++;
          $next_url = 'http:'.$next;
          $html.= content($next_url, $i);
      }



      return $html;
  }


function getContent($NAME, $URL)
{
    $html_content = content($URL, 1);

    $fp = fopen('db.txt', 'a');//test
    fwrite($fp, $html_content);
    fclose($fp);

      //insertDrugHtml($NAME,$html_content);
}

$link = open_database_connection();
//$num =  maxId_new_medicals();
//echo $num;

$count = 1980;         // 11  21  ?? new_medicals

$numTotal = 1980;

while ($count <= $numTotal) {
    $drug_arr = getDrugReference($count);
// var_dump($drug_arr);
// echo $drug_arr['drug_name'].'<br>';
// echo $drug_arr['drug_reference'].'<br><br>';
getContent($drug_arr['drug_name'], $drug_arr['drug_reference']);

    $count++;
}


echo 'OK';

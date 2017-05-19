<?php
require_once 'db.php';
require 'phpQuery.php';


function getContent($NAME, $URL)
{
    $i = 1;
    $flag = true;
    $html_content = '';
    while ($flag) {
        if ($i==1) {
            $url = $URL;
        } else {
            $url= $URL."page/{$i}/";
        }
//$url = ($i==1) ? $URL : $URL."page/{$i}/";
        // $file = file_get_contents($url) ?? false;
        $file = file_get_contents($url);


        if (!$file) {
            $flag = false;
          //insertDrugHtml($NAME,$html_content);
            echo '</hr>';//test
          return;
        }


        echo $i.'<br>';//test
        $doc = phpQuery::newDocument($file);

        $html = $doc->find('#content > article');
        $html=pq($html);
        $html->find('script')->remove();
      //  $html = $doc->find('#content > article')->find('script')->remove();
    //$html = $html->html(); //html one page

       //foreach ($doc->find('#content > article > div.post_image') as $img) { //OK
       //foreach ($doc->find('#content > article .post_image') as $img) { //??
 //foreach ($doc->find('#content .post_image img') as $img) { //?Ok

   foreach ($doc->find('#content img') as $img) { //?Ok
        $img= pq($img);


           //$img_url = $img->find('.post_image_img img')->attr('src');
        //$img_url = $img->find('img')->attr('src'); ??
        $img_url = $img->attr('src');

        $img_url = 'http:'.$img_url; // url foto native
        echo $img_url.'<br>'; //test

        $image_name = basename($img_url);
           if (!file_exists('img/'.$image_name)) {
               file_put_contents('img/'.$image_name, file_get_contents($img_url));
           }
           $img_url_local = 'img/'.$image_name; // url foto local

    echo 'local: '.$img_url_local.'<br>'; //test

      // $html->find('.post_image_img img')->attr('src', $img_url_local); // change url img
        //$html->find('img')->attr('src',$img_url_local); // change url img ??


      $img->attr('src',$img_url_local);
     $img->parent()->filter('a')->attr('href',$img_url_local).'<br>';
      echo $img->parent()->parent()->filter('div.post_image_img')->next()->filter('div.post_image_attr')->find('a')->attr('href',$img_url_local);
        //echo $img->parent();
      //  $html->find('.post_image_img a')->attr('href', $img_url_local);
      //      $html->find('.post_image_attr a')->attr('href', $img_url_local);
       //
      //      $img_url_text_src = $html->find('.post_image_img img')->attr('src');//test

      $img_url_text_src = $img->attr('src');//test

      echo 'src: '.$img_url_text_src.'<br>';//test

      // $img_url_text_a = $html->find('.post_image_img a')->attr('href');//test
      // echo 'a: '.$img_url_text_a.'<br>';//test
      // $img_url_text_a1 = $html->find('.post_image_attr a')->attr('href');//test
      // echo 'a1: '.$img_url_text_a1.'<br>';//test

   //$html = $html->html();
      //$html = $html->html();
       }
//unset($img);
  //$html = $html->html();

        $fp = fopen('html_content.txt', 'a');//test
      fwrite($fp, "$i".'-----------------------------------------------------------');
        fwrite($fp, $html);
        fclose($fp);

        $html_content.=$html;

        $i++;
    }

    $fp1 = fopen('html_content1.txt', 'a');//test
    fwrite($fp1, $html_content);
    fclose($fp1);
}


$link = open_database_connection();
//$num =  maxId_new_medicals();
//echo $num;
 $num = 1980;

$i = 1980;
while ($i <= $num) {
    $drug_arr = getDrugReference($i);
// var_dump($drug_arr);
// echo $drug_arr['drug_name'].'<br>';
// echo $drug_arr['drug_reference'].'<br><br>';
getContent($drug_arr['drug_name'], $drug_arr['drug_reference']);
    $i++;
}

#content > article > div > div.post_image_img > a
#content > article > div > div.post_image_img > a > img

#content > article > div > div.post_image_attr > a

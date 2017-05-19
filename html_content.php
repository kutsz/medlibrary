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
          //return;
        }


        echo $i.'<br>';//test
        $doc = phpQuery::newDocument($file);

        $html = $doc->find('#content > article');
        $html=pq($html);
        $html->find('script')->remove();
    //$html = $html->html(); //html one page

       foreach ($doc->find('#content > article > div.post_image') as $img) {
           //foreach ($doc->find('img') as $img) {

        $img= pq($img);
           $img_url = $img->find('.post_image_img img')->attr('src');
        //$img_url = $img->find('img')->attr('src'); ??
        //$img_url = $img->attr('src');

        $img_url = 'http:'.$img_url; // url foto native
        echo $img_url.'<br>'; //test

        $image_name = basename($img_url);
           if (!file_exists('img/'.$image_name)) {
               file_put_contents('img/'.$image_name, file_get_contents($img_url));
           }
           $img_url_local = 'img/'.$image_name; // url foto local

    echo 'local: '.$img_url_local.'<br>'; //test

       $html->find('.post_image_img img')->attr('src', $img_url_local); // change url img
      //  $html->find('img')->attr('src',$img_url_local); // change url img


    // $img->attr('src',$img_url_local);
       $html->find('.post_image_img a')->attr('href', $img_url_local);
           $html->find('.post_image_attr a')->attr('href', $img_url_local);

           $img_url_text_src = $html->find('.post_image_img img')->attr('src');//test
      //$img_url_text_src = $html->attr('src');//test

      echo 'src: '.$img_url_text_src.'<br>';//test

      // $img_url_text_a = $html->find('.post_image_img a')->attr('href');//test
      // echo 'a: '.$img_url_text_a.'<br>';//test
      // $img_url_text_a1 = $html->find('.post_image_attr a')->attr('href');//test
      // echo 'a1: '.$img_url_text_a1.'<br>';//test

  $html = $html->html();
       }

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
 $num = 1;

$i = 1;
while ($i <= $num) {
    $drug_arr = getDrugReference($i);
// var_dump($drug_arr);
// echo $drug_arr['drug_name'].'<br>';
// echo $drug_arr['drug_reference'].'<br><br>';
getContent($drug_arr['drug_name'], $drug_arr['drug_reference']);
    $i++;
}





///////////////////////////////////
#content > article > table.contentTablePetite > tbody > tr:nth-child(7) > td > div > div.post_image_img > a > img
/*
Недавно как раз и парсил картинки этой библиотекой и она очень хорошо справилась

Для того, что бы сохранить конкретно картинку, то надо при помощи библиотеки найти ссылки на картинки, я искал на странице и помещал все найденные ссылки в массив, пример кода:
$model_page_url = file_get_contents($page);  //Получаем всю страницу
  $model_page = phpQuery::newDocument($model_page_url); //Создаём объект страницы библиотекой
  $images_link = $model_page->find('img'); //Ищем все теги img
  foreach ($images_link as $image_link) {
    $images[] = pq($image_link)->attr('src'); //В цикле помещаем ссылку на картинку в массив
  }


Затем примерно так:
foreach($images as $image){
        $image_name = basename($image); //Определяем имя и расширение картинки
        if(!file_exists('img/'.$image_name)){ //Проверяем нет ли такой картинки
          file_put_contents('img/'.$image_name, file_get_contents($image)); //через file_get_contents($image) получаем картинку по ссылке и file_put_contents кладём её в нужную нам папку
        }else{
          continue;
        }
      }

*/
//-----------------

  //UPDATE `table` SET `column` = CONCAT( 'чего-то спереди ', `column` )

  //drug_html [id, drug_name, html_description, img_reference, local_img_reference]

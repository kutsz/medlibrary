<?php

require 'phpQuery.php';
// function get_content($url)
// {
//     $ch = curl_init($url);
//     curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//     curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:47.0) Gecko/20100101 Firefox/47.0');
//    //curl_setopt ($ch, CURLOPT_REFERER, 'https://localhost/curl_tutorial/index_medLibrary.php');
//    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
//     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//     $res = curl_exec($ch);
//     curl_close($ch);
//     return $res;
// }
function open_database_connection()
{
    $host ='localhost';
    $dbname ='medlibrary';
    $user = 'vK';
    $password = '69';
    $charset = 'utf8';

    $link = new PDO("mysql:host=$host;dbname=$dbname;charset=$charset", $user, $password);

    //$link = new PDO("mysql:host=localhost;dbname=protest14;charset=utf8", 'bloguser', '123');
    return $link;
}

function close_database_connection($link)
{
    $link = null;
}


function addDrud($drug_name, $drug_reference, $last_revised)
{
    $db = open_database_connection();

    $sql = 'INSERT INTO medicals(drug_name, drug_reference, last_revised)'
        .'VALUES (:drug_name, :drug_reference, :last_revised)';

    $result = $db->prepare($sql);
    $result->bindParam(':drug_name', $drug_name, PDO::PARAM_STR);
    $result->bindParam(':drug_reference', $drug_reference, PDO::PARAM_STR);
    $result->bindParam(':last_revised', $last_revised, PDO::PARAM_STR);

    return $result->execute();
}


//******************
function data($str)
{
    $pieces = array_reverse(explode(" ", $str));

    return "$pieces[2] $pieces[1] $pieces[0]";
}

function numOfpages($str)
{
    $pieces = array_reverse(explode("&nbsp", $str));

    return "$pieces[0]";
}


function getInfoDrug($URL, $numOfpages)
{
    $num = $numOfpages;
    for ($i=1; $i <= $num; $i++) {
        if ($i==1) {
            $url = $URL;
        } else {
            $url= $URL."page/{$i}/";
        }
  //header("Location:$url");
  $file = file_get_contents($url);
        $doc = phpQuery::newDocument($file);

        //$num_pages = $doc->find('#content div.wp-pagenavi>span.pages')->text();
//$num_pages = numOfpages($num_pages);
//echo $num_pages.'<br>';

        foreach ($doc->find('#content .post a') as $drug) {
            $drug = pq($drug);

            $drug_name = $drug->find('h1')->text();

            $url =$drug->attr("href");
            $drug_reference = 'http:'.$url;

            $data =$drug->find('header > div.meta')->text();
            $last_revised = data($data);
            $last_revised = date("Y-m-d", strtotime($last_revised));
            
            addDrud($drug_name, $drug_reference, $last_revised);
            //
            // echo $drug_name.'<br>';
            // echo $drug_reference.'<br>';
            // echo $last_revised.'<br>';
            // echo '<hr>';
        }
        // echo '<hr>';
        // echo '<hr>';
    }
}
//******************

// $url = "http://medlibrary.org/lib/rx/alpha_title/b/";
// for($i=1;$i<4;$i++){
// getInfoDrug($url, $i);
//    }

//*************************************

//$pages = [1=>450,228,519,386,195,242,161,174,145,12,64,343,402,248,189,448,33,156,254,333,34,152,16,21,3,98];
$pages = [1=>450,228,519];
$url = 'http://medlibrary.org/lib/rx/alpha_title/';
//$html = get_content($url);
$html = file_get_contents($url);
$doc = phpQuery::newDocument($html);


$count = 1;
$numA_Z = 3;
while ($count<=$numA_Z) {
    $url = $doc->find("#content > article > ul > li:nth-child($count) > a")->attr("href");//url
//$ref = $doc->find("#content > article > ul > li:nth-child($count) > a");//ref

    $url = 'http:'.$url;

    getInfoDrug($url, $pages[$count]);

    $count++;
    //echo $url."<br>"; $pages[$count]
//echo $ref."<br>";
}

echo 'END';
// localhost/medlibrary/index.php
//http://medlibrary.org/medlibrary/index.php




//phpQuery::unloadDocuments($doc);

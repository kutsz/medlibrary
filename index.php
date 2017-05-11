<?php

require 'phpQuery.php';


function open_database_connection()
{
    $host ='localhost';
    $dbname ='medlibrary';
    $user = 'vK';
    $password = '69';
    $charset = 'utf8';

    $link = new PDO("mysql:host=$host;dbname=$dbname;charset=$charset", $user, $password);

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



function getInfoDrug($URL, $numOfpages)
{
    $num = $numOfpages;
    for ($i=1; $i <= $num; $i++) {
        if ($i==1) {
            $url = $URL;
        } else {
            $url= $URL."page/{$i}/";
        }
        $file = file_get_contents($url);
        $doc = phpQuery::newDocument($file);

        //$num_pages = $doc->find('#content div.wp-pagenavi>span.pages')->text();
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
    }
}

//*************************************

//$pages = [1=>450,228,519,386,195,242,161,174,145,12,64,343,402,248,189,448,33,156,254,333,34,152,16,21,3,98];
// $pages = [4=>386,195];//D,E
// $pages = [6=>242,161]; //F,G
$pages = [8=>174,145]; //H,I
$url = 'http://medlibrary.org/lib/rx/alpha_title/';
$html = file_get_contents($url);
$doc = phpQuery::newDocument($html);


$count = 8;
$numA_Z = 9;
while ($count<=$numA_Z) {
    $url = $doc->find("#content > article > ul > li:nth-child($count) > a")->attr("href");//url

    $url = 'http:'.$url;

    getInfoDrug($url, $pages[$count]);

    $count++;
}

echo 'END';

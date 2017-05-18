<?php

function open_database_connection()
{
    $host ='localhost';
    $dbname ='medlibrary';
    $user = 'root';
    $password = '123';
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

//////////////////////
function getUniqueDrugNname($id)
{
    $link = $link ?? open_database_connection();
    $result = $link->query("SELECT drug_name FROM unique_drug_name WHERE id = '$id'");
    $name = $result->fetch();
    return $name[0];

}



function getList_last_revised($drugName)
{
    $link = $link ?? open_database_connection();


    $result = $link->query("SELECT last_revised FROM medicals WHERE drug_name = '$drugName'");
    $last_revised = [];
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $last_revised[] = $row['last_revised'];
    }
        //close_database_connection($link);

        return $last_revised;
}

function maxId_unique_drug_name()
{
  $link = $link ?? open_database_connection();

  $result = $link->query("SELECT MAX(id) FROM unique_drug_name");
   $num = $result->fetch();
   return $num[0];
}



    function getUniqueDrug($drugName, $last_revised)
    {
        $link = $link ?? open_database_connection();

        $result = $link->query("SELECT drug_name,drug_reference,last_revised FROM medicals WHERE drug_name = '$drugName' AND last_revised ='$last_revised'");
        $data = [];
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $data['drug_name'] = $row['drug_name'];
            $data['drug_reference'] = $row['drug_reference'];
            $data['last_revised'] = $row['last_revised'];

        }
        //close_database_connection($link);

        return $data;
    }



    function insertDrug($drug_name, $drug_reference, $last_revised)
    {
        $link = $link ?? open_database_connection();

        $sql = 'INSERT INTO new_medicals(drug_name, drug_reference, last_revised)'
            .'VALUES (:drug_name, :drug_reference, :last_revised)';

        $result = $link->prepare($sql);
        $result->bindParam(':drug_name', $drug_name, PDO::PARAM_STR);
        $result->bindParam(':drug_reference', $drug_reference, PDO::PARAM_STR);
        $result->bindParam(':last_revised', $last_revised, PDO::PARAM_STR);

        return $result->execute();
    }

///////////////////////// new_medicals //////////////

function maxId_new_medicals()
{
  $link = $link ?? open_database_connection();

  $result = $link->query("SELECT MAX(id) FROM new_medicals");
   $num = $result->fetch();
   return $num[0];
}


function getDrugReference($id)
{
    $link = $link ?? open_database_connection();
    $result = $link->query("SELECT drug_name,drug_reference FROM new_medicals WHERE id = '$id'");
    $name = $result->fetch(PDO::FETCH_ASSOC);
    return $name;

}

//drug_html [id, drug_name, html_description, img_reference, local_img_reference]

    function insertDrugHtml($drug_name, $html_description)
    {
        $link = $link ?? open_database_connection();

        $sql = 'INSERT INTO drug_html(drug_name, html_description)'
            .'VALUES (:drug_name, :html_description)';

        $result = $link->prepare($sql);
        $result->bindParam(':drug_name', $drug_name, PDO::PARAM_STR);
        $result->bindParam(':html_description', $html_description, PDO::PARAM_STR);

        return $result->execute();
    }


function updateDrugHtml()
{


}

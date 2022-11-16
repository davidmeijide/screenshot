<?php
include("../src/Screenshot.php");

if(isset($_POST["getWebsites"])){
    echo file_get_contents("../websites.json");
}

if(isset($_POST['id'])){
    $jsonData = json_decode(file_get_contents('../websites.json'),true)[$_POST['id']-1];
    $filename = requestScreenshot($jsonData['website'],$jsonData['id'],$jsonData['name']);
    echo json_encode(['id' => uploadImgToDrive($filename,"10ze2oFvaMFhnPGM7e53Q8vWumel04nxi")]);

}




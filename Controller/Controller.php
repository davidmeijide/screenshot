<?php
include("../src/Screenshot.php");


if(isset($_POST["getWebsites"])){
    echo file_get_contents("../websites.json");
}

if(isset($_GET['id'])){
    $jsonData = json_decode(file_get_contents('../websites.json'),true)[$_GET['id']-1];
    $filename = requestScreenshot($jsonData['website'],$jsonData['id'],$jsonData['name']);
    header("Location: ../src/uploadImgToDrive.php?filename=$filename");
}


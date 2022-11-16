<?php
include("../src/Screenshot.php");


if(isset($_POST["getWebsites"])){
    echo file_get_contents("../websites.json");
}

if(isset($_GET['id'])){
    $jsonData = json_decode(file_get_contents('../websites.json'),true)[$_GET['id']-1];
    $filename = requestScreenshot($jsonData['website'],$jsonData['id'],$jsonData['name']);
    echo uploadImgToDrive($filename,"10ze2oFvaMFhnPGM7e53Q8vWumel04nxi");
    echo "<script>window.close()</script>";
    //header("Location: ../src/uploadImgToDrive.php?filename=$filename");
}


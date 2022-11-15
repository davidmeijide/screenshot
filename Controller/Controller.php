<?php
include("../src/Screenshot.php");


if(isset($_POST["getWebsites"])){
    echo file_get_contents("../websites.json");
}

if(isset($_POST['takeScreenshot'])){
    $filename = requestScreenshot($_POST['website'],$_POST['id'],$_POST['name']);
    echo uploadImgToDrive($filename,"10ze2oFvaMFhnPGM7e53Q8vWumel04nxi");
}
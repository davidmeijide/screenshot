<?php
include_once __DIR__ . '/../vendor/autoload.php';

use Google\Service\Drive;

function requestScreenshot($url, $id, $name){
    try{
        require_once("../private/API_KEYS.php");
    
        $hash = md5($url.$SCREENSHOT_API_SECRET_PHRASE);  //As required in the documentation, the hash is a combined md5 hash of the url and the secret phrase.
        $src = "https://api.screenshotmachine.com?key=$SCREENSHOT_API_KEY&url=$url&dimension=1920x1080&format=jpg&hash=$hash&delay=200";
        $name = str_replace(" ","-",$name); //Whitespaces are replaced for dashes to avoid errors.
        $filename = $id."_".$name.".jpg"; //Filename is id_name.jpg
        $img = imagecreatefromstring(file_get_contents($src)); //The screenshot is imported and stored in memory
        imagejpeg($img, "../public/img/$filename"); //Image is saved in local folder /img
        imagedestroy($img); //Image destroy for memory saving.
        return $filename;
    }
    catch(Exception $e){
        echo 'Error. Screenshot request failed: ' .$e->getMessage();
    }
}

function uploadImgToDrive($filename, $parentFolder){
    try{
        require("../private/API_KEYS.php");

        $client = new Google_Client();
        // Get your credentials from the console
        $client->setClientId($GOOGLE_CLIENT_ID);
        $client->setClientSecret($GOOGLE_SECRET_KEY);
        // $client->setAuthConfig("../credentials.json");
        $client->setRedirectUri('http://localhost:8000/screenshot_generator/src/Screenshot.php'); //Redirect after Google Authentication
        //$client->setRedirectUri('http://localhost:8000/screenshot_generator/Controller/Controller.php');
        $client->setScopes(array('https://www.googleapis.com/auth/drive')); // Permissions. Only file manipulation on Drive.

        session_start();

        if (isset($_GET['code']) || (isset($_SESSION['accessToken']) && $_SESSION['accessToken'])) {
            if (isset($_GET['code'])) {
                $client->fetchAccessTokenWithAuthCode($_GET['code']); //Exchange the authorization code for an access token
                $_SESSION['accessToken'] = $client->getAccessToken(); //Store the access token in the $_SESSION array
            } 
            else{
                $client->setAccessToken($_SESSION['accessToken']);
            }

            $service = new Google\Service\Drive($client); //Contruct the Drive Service

            //Insert a file
            $file = new Drive\DriveFile();
            $file->setName($filename);
            $file->setDescription("A screenshot of ".substr($filename,strpos($filename,".")),"'s landing page.");
            $file->setMimeType('image/jpeg');
            $file->setParents(array($parentFolder)); //The desired drive target folder

            $data = file_get_contents("../public/img/$filename");

            //Create a file and set its data, mime types and type of upload. Multipart allows file and metadata upload.
            $createdFile = $service->files->create($file, array(
                'data' => $data,
                'mimeType' => 'image/jpeg',
                'uploadType' => 'multipart'
                ));

            return $createdFile->id;

        } 
        else { 
            // Redirects to Google authorization url if auth code or access token are not set. Redirection is done to get the auth code.
            $authUrl = $client->createAuthUrl();
            header('Location: ' . $authUrl); 
            exit();
        }
    }
    catch(Exception $e) {
        echo 'Error. Image upload to Drive failed: ' .$e->getMessage();
    }
}
/* uploadImgToDrive('1_iFunded.jpg',"10ze2oFvaMFhnPGM7e53Q8vWumel04nxi"); */





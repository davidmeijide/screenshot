<?php
include_once __DIR__ . '/../vendor/autoload.php';

use Google\Service\Drive;

$filename = $_GET['filename'];
$parentFolder = '10ze2oFvaMFhnPGM7e53Q8vWumel04nxi';

try{
    require("../private/API_KEYS.php");

    $client = new Google_Client();
    // Get your credentials from the console
    $client->setClientId($GOOGLE_CLIENT_ID);
    $client->setClientSecret($GOOGLE_SECRET_KEY);
    /* $client->setAuthConfig("../credentials.json"); */
    $client->setRedirectUri('http://localhost:8000/screenshot_generator/src/Screenshot.php'); //Redirect after Google Authentication
    $client->setScopes(array('https://www.googleapis.com/auth/drive')); // Permissions. Only file manipulation on Drive.

    session_start();

    if (isset($_GET['code']) || (isset($_SESSION['accessToken']) && $_SESSION['accessToken'])) {
        if (isset($_GET['code'])) {
            $client->fetchAccessTokenWithAuthCode($_GET['code']); //Exchange the authorization code for an access token
            $_SESSION['accessToken'] = $client->getAccessToken(); //Store the access token in the $_SESSION array
        } else
            $client->setAccessToken($_SESSION['accessToken']);

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

        return print_r($createdFile);

    } else { 
        // Redirects to Google authorization url if auth code or access token are not set. Redirection is done to get the auth code.
        $authUrl = $client->createAuthUrl();
        header('Location: ' . $authUrl); 
        exit();
    }
}
catch(Exception $e) {
    echo 'Error. Image upload to Drive failed: ' .$e->getMessage();
}
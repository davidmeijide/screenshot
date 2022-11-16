<?php
use Google\Service\Drive;
include_once __DIR__ . '/../vendor/autoload.php';

require("../private/API_KEYS.php");
try{
    $client = new Google_Client();
    // Get your credentials from the console
    $client->setClientId($GOOGLE_CLIENT_ID);
    $client->setClientSecret($GOOGLE_SECRET_KEY);
    $client->setAuthConfig("../credentials.json");
    //$client->setRedirectUri('http://localhost:8000/screenshot_generator/src/Screenshot.php'); //Redirect after Google Authentication
    $client->setRedirectUri('http://localhost:8000/screenshot_generator/src/Login.php');

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
        echo "<script>window.close()</script>";
    }
    else { 
        // Redirects to Google authorization url if auth code or access token are not set. Redirection is done to get the auth code.
        $authUrl = $client->createAuthUrl();
        header('Location: ' . $authUrl); 
        exit();
    }
}
catch(Exception $e){
    echo 'Login failed: ' .$e->getMessage();
    
}
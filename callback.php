<?php
session_start();
require_once 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$google_client = new Google_Client();
$google_client->setClientId($_ENV['GOOGLE_CLIENT_ID']);
$google_client->setClientSecret($_ENV['GOOGLE_CLIENT_SECRET_KEY']);
$google_client->setRedirectUri($_ENV['REDIRECT_URI']);
$google_client->addScope('email');


if (isset($_GET['code'])) {
    $token = $google_client->fetchAccessTokenWithAuthCode($_GET['code']);
    $google_client->setAccessToken($token);

    // Get user profile data
    $oauth2 = new Google_Service_Oauth2($google_client);
    $userInfo = $oauth2->userinfo->get();

    // Save user data in session
    $_SESSION['user'] = [
        'id' => $userInfo->id,
        'name' => $userInfo->name,
        'email' => $userInfo->email,
        'picture' => $userInfo->picture,
    ];

   
    header('Location: dashboard.php');
    exit();
} else {
   
    echo "Error: Invalid request.";
    exit();
}
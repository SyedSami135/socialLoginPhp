<?php
// github-callback.php
session_start();
require_once './vendor/autoload.php';
use League\OAuth2\Client\Provider\Github;
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$provider = new Github([
    'clientId'          => $_ENV['GITHUB_CLIENT_ID'],
    'clientSecret'      => $_ENV['GITHUB_CLIENT_SECRET'],
    'redirectUri'       => 'http://localhost/socialLogin/git-callback.php',
]);


// Verify the state parameter to prevent CSRF
if (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {
    unset($_SESSION['oauth2state']);
    exit('Invalid state');
}

try {
    // Get an access token using the authorization code
    $accessToken = $provider->getAccessToken('authorization_code', [
        'code' => $_GET['code'],
    ]);

    // Get the user's details
    $resourceOwner = $provider->getResourceOwner($accessToken);
    $user = $resourceOwner->toArray();

    // Save user data in session
    $_SESSION['user'] = [
        'id' => $user['id'],
        'name' => $user['name'],
        'email' => $user['email'],
        'username' => $user['login'],
        'picture' => $user['avatar_url'],
    ];

    // Redirect to dashboard
    header('Location: dashboard.php');
    exit();

} catch (Exception $e) {
    exit('Error: ' . $e->getMessage());
}
<?php
// fb-callback.php (Facebook callback handler)
session_start();
require_once './vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Initialize Facebook SDK
$fb = new Facebook\Facebook([
    'app_id' => $_ENV['FACEBOOK_CLIENT_ID'],
    'app_secret' => $_ENV['FACEBOOK_APP_SECRET'],
    'default_graph_version' => 'v19.0',
]);

$helper = $fb->getRedirectLoginHelper();

// Fix for "Cross-site request forgery validation failed"
if (isset($_GET['state'])) {
    $helper->getPersistentDataHandler()->set('state', $_GET['state']);
}

try {
    // Get access token
    $accessToken = $helper->getAccessToken();
} catch (Facebook\Exceptions\FacebookResponseException $e) {
    exit('Graph returned an error: ' . $e->getMessage());
} catch (Facebook\Exceptions\FacebookSDKException $e) {
    exit('Facebook SDK returned an error: ' . $e->getMessage());
}

if (!isset($accessToken)) {
    // Handle error if access token is not available
    if ($helper->getError()) {
        header('HTTP/1.0 401 Unauthorized');
        echo "Error: " . $helper->getError() . "\n";
        echo "Error Code: " . $helper->getErrorCode() . "\n";
        echo "Error Reason: " . $helper->getErrorReason() . "\n";
        echo "Error Description: " . $helper->getErrorDescription() . "\n";
    } else {
        header('HTTP/1.0 400 Bad Request');
        echo 'Bad request';
    }
    exit;
}

// Store access token in session
$_SESSION['fb_access_token'] = (string) $accessToken;

try {
    // Get user profile data
    $response = $fb->get('/me?fields=id,name,email,picture', $accessToken);
    $fbUser = $response->getGraphUser();
    
    // Save user data in session
    $_SESSION['user'] = [
        'id' => $fbUser['id'],
        'name' => $fbUser['name'],
        'email' => $fbUser['email'],
        'picture' => $fbUser['picture']['url'] ?? null, // Handle missing picture
    ];
    
    // Redirect to dashboard
    header('Location: dashboard.php');
    exit();

} catch (Facebook\Exceptions\FacebookResponseException $e) {
    exit('Graph returned an error: ' . $e->getMessage());
} catch (Facebook\Exceptions\FacebookSDKException $e) {
    exit('Facebook SDK returned an error: ' . $e->getMessage());
}
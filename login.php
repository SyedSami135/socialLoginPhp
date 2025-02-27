<?php
require_once './vendor/autoload.php';
session_start();
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

use League\OAuth2\Client\Provider\Github;


$google_client = new \Google_Client();
$google_client->setClientId($_ENV['GOOGLE_CLIENT_ID']);
$google_client->setClientSecret($_ENV['GOOGLE_CLIENT_SECRET_KEY']);
$google_client->setRedirectUri($_ENV['REDIRECT_URI']);
$google_client->addScope('email');


$google_auth_url = $google_client->createAuthUrl();


// ##################  FACEBOOK  ##############################


$fb = new Facebook\Facebook([
    'app_id' => $_ENV['FACEBOOK_CLIENT_ID'],
    'app_secret' => $_ENV['FACEBOOK_APP_SECRET'],
    'default_graph_version' => 'v19.0',
]);
$helper = $fb->getRedirectLoginHelper();
$permissions = ['email']; // Optional permissions
$facebook_loginUrl = $helper->getLoginUrl('http://localhost/socialLogin/fb-callback.php', $permissions);




// ##################  GITHUB  ##############################



$provider = new Github([
    'clientId'          => $_ENV['GITHUB_CLIENT_ID'],
    'clientSecret'      => $_ENV['GITHUB_CLIENT_SECRET'],
    'redirectUri'       => 'http://localhost/socialLogin/git-callback.php',
]);


$github_authUrl = $provider->getAuthorizationUrl();
$_SESSION['oauth2state'] = $provider->getState(); 


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Social Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg max-w-md w-full text-center">
        <h1 class="text-3xl font-bold mb-6">Sign In</h1>
        <a href="<?php echo $google_auth_url; ?>">

            <!-- Google Login Button -->
            <button class="w-full mb-4 px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded flex items-center justify-center">
                <!-- Google Icon SVG -->
                <svg class="w-5 h-5 mr-2" viewBox="0 0 48 48">
                    <path fill="#FFC107" d="M43.6,20.3h-4.8V20H24v8h11.3C34.3,34.7,29.2,38,23.8,38c-7.1,0-13-5.9-13-13c0-7.1,5.9-13,13-13 c3.3,0,6.4,1.2,8.8,3.2l6.2-6.2C34.4,7.6,29.9,5,24,5C13.5,5,5,13.5,5,24s8.5,19,19,19c10.9,0,18.9-7.7,18.9-18.9 C42.9,22.2,43.2,21.2,43.6,20.3z" />
                    <path fill="#FF3D00" d="M6.3,14.7l6.6,4.8C14.2,16.4,18.7,13,24,13c3.3,0,6.4,1.2,8.8,3.2l6.2-6.2C34.4,7.6,29.9,5,24,5 C16.1,5,9,9.6,6.3,14.7z" />
                    <path fill="#4CAF50" d="M24,43c5.9,0,11.3-2.1,15.1-5.7l-7.2-6.1C29.3,34.5,26.8,35.5,24,35.5c-4.2,0-7.7-2.8-8.9-6.6l-7.3,5.7 C9.2,38.7,16.1,43,24,43z" />
                    <path fill="#1976D2" d="M43.6,20.3h-4.8V20H24v8h11.3c-0.3,2.1-0.9,4.1-1.9,6l7.2,6.1C42.9,34.7,43.2,22.2,43.6,20.3z" />
                </svg>
                Sign in with Google
            </button>
        </a>
        <a href="<?php echo $facebook_loginUrl; ?>">
            <!-- Facebook Login Button -->
            <button class="w-full mb-4 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded flex items-center justify-center">
                <!-- Facebook Icon SVG -->
                <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24">
                    <path fill="currentColor" d="M22 12C22 6.48 17.52 2 12 2S2 6.48 2 12c0 5 3.66 9.12 8.44 9.88v-6.99H7.9v-2.89h2.54V9.41c0-2.5 1.49-3.89 3.77-3.89 1.09 0 2.23.2 2.23.2v2.45h-1.26c-1.24 0-1.63.77-1.63 1.56v1.88h2.78l-.44 2.89h-2.34V22C18.34 21.12 22 17 22 12z"></path>
                </svg>
                Sign in with Facebook
            </button>
        </a>
        <!-- GitHub Login Button -->

        <a href="<?php echo $github_authUrl; ?>">
        <button class="w-full mb-4 px-4 py-2 bg-gray-800 hover:bg-gray-900 text-white font-semibold rounded flex items-center justify-center">
            <!-- GitHub Icon SVG -->
            <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24">
                <path fill="currentColor" d="M12,0.5C5.65,0.5,0.5,5.65,0.5,12c0,5.1,3.29,9.43,7.87,10.97c0.58,0.11,0.79-0.25,0.79-0.56
          c0-0.28-0.01-1.02-0.02-2.01c-3.21,0.7-3.89-1.55-3.89-1.55c-0.53-1.35-1.29-1.71-1.29-1.71c-1.06-0.72,0.08-0.71,0.08-0.71
          c1.17,0.08,1.78,1.21,1.78,1.21c1.04,1.78,2.73,1.27,3.4,0.97c0.11-0.75,0.41-1.27,0.75-1.56c-2.56-0.29-5.25-1.28-5.25-5.68
          c0-1.25,0.45-2.27,1.19-3.07c-0.12-0.29-0.52-1.45,0.11-3.02c0,0,0.97-0.31,3.18,1.18c0.92-0.26,1.91-0.39,2.89-0.39
          c0.98,0,1.97,0.13,2.89,0.39c2.21-1.5,3.18-1.18,3.18-1.18c0.63,1.57,0.23,2.73,0.11,3.02c0.74,0.8,1.19,1.82,1.19,3.07
          c0,4.41-2.69,5.38-5.25,5.67c0.42,0.36,0.8,1.08,0.8,2.18c0,1.57-0.01,2.84-0.01,3.22c0,0.31,0.21,0.68,0.8,0.56
          C20.71,21.43,24,17.1,24,12C24,5.65,18.35,0.5,12,0.5z" />
            </svg>
            Sign in with GitHub
        </button>
        </a>
    </div>
</body>

</html>
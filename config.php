<?php
return [
  'callback' => 'http://localhost/social-login/callback.php', // Your callback URL
  'providers' => [
    'Google' => [
      'enabled' => true,
      'keys' => [
        'id'     => 'YOUR_GOOGLE_CLIENT_ID',
        'secret' => 'YOUR_GOOGLE_CLIENT_SECRET',
      ],
    ],
    'Facebook' => [
      'enabled' => true,
      'keys' => [
        'id'     => 'YOUR_FACEBOOK_APP_ID',
        'secret' => 'YOUR_FACEBOOK_APP_SECRET',
      ],
      'scope' => 'email, public_profile', // Facebook permissions
    ],
    'Twitter' => [
      'enabled' => true,
      'keys' => [
        'key'    => 'YOUR_TWITTER_API_KEY',
        'secret' => 'YOUR_TWITTER_API_SECRET',
      ],
    ],
    // Add more providers like GitHub, LinkedIn, etc.
  ],
];
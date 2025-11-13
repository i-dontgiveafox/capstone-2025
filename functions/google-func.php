<?php
require_once __DIR__ . '/../vendor/autoload.php';

$client = new Google_Client();
$client->setClientId('256255783631-i4l8ffiljag7m0e48qcp78cv9gj0rkii.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('http://palegreen-kangaroo-842869.hostingersite.com/capstone-2025/public/google-callback.php');
$client->addScope('email');
$client->addScope('profile');

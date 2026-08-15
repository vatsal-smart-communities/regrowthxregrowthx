<?php
// config/payments.php

// Square API Configuration
// Using Sandbox credentials by default for development
define('SQUARE_ENV', 'sandbox'); // 'sandbox' or 'production'

// Replace these with your actual Square Sandbox / Production credentials
define('SQUARE_APP_ID', 'sandbox-sq0idb-TEST_APP_ID_REPLACE_ME');
define('SQUARE_ACCESS_TOKEN', 'EAAAE_TEST_ACCESS_TOKEN_REPLACE_ME');
define('SQUARE_LOCATION_ID', 'TEST_LOCATION_ID_REPLACE_ME');

function getSquareEndpoint() {
    return SQUARE_ENV === 'production' 
        ? 'https://connect.squareup.com/v2' 
        : 'https://connect.squareupsandbox.com/v2';
}
?>

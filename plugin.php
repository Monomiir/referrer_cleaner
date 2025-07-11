<?php
/*
Plugin Name: Referrrer Cleaner
Plugin URI: https://github.com/Monomiir/referrer_cleaner
Description: Redirect using intermediate HTML page to remove referrer info.
Version: 1.0
Author: Monomiir
Author URI: https://encrypt.zip
*/

if (!defined('YOURLS_ABSPATH')) die();

yourls_add_action('redirect_shorturl', 'refcleaner_redirect');

function refcleaner_redirect($args) {
    list($url, $keyword) = $args;

    header('Content-Type: text/html; charset=UTF-8');
    header('Referrer-Policy: no-referrer');

    $escaped_url = htmlspecialchars($url, ENT_QUOTES);

    echo "<!DOCTYPE html>
<html lang='en'>
<head>
  <meta charset='UTF-8'>
  <meta http-equiv='refresh' content='0;url={$escaped_url}'>
  <title>Redirecting...</title>
  <style>
    body {
      background-color: #000;
      color: #ccc;
      font-family: sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }
    a {
      color: #ccc;
    }
  </style>
</head>
<body>
  <p>Securely redirecting via encrypt.zip...</p>
  <script>
    window.location.replace(" . json_encode($url) . ");
  </script>
</body>
</html>";
    exit;
}

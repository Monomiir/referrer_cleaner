<?php
/*
Plugin Name: Referrrer Cleaner
Plugin URI: https://github.com/Monomiir/referrer_cleaner
Description: Redirect using intermediate HTML page to remove referrer info.
Version: 1.1
Author: Monomiir
Author URI: https://encrypt.zip
*/

if (!defined('YOURLS_ABSPATH')) die();

yourls_add_action('redirect_shorturl', 'refcleaner_redirect');

function refcleaner_redirect($args) {
    list($url, $keyword) = $args;

    // 크롤러 유저 에이전트 확인 (OG 태그 수집 허용 대상)
    $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';
    $is_crawler = (
        stripos($ua, 'facebookexternalhit') !== false ||
        stripos($ua, 'kakaotalk-scrap') !== false
    );

    if ($is_crawler) {
        return;
    }

    // 일반 사용자의 경우: 통계 수동 기록 후 중간 리디렉션 페이지로 Referrer 제거
    yourls_update_clicks($keyword);
    yourls_log_redirect($keyword);
    header('Content-Type: text/html; charset=UTF-8');
    header('Referrer-Policy: no-referrer');

    $escaped_url = htmlspecialchars($url, ENT_QUOTES);
    $escaped_json_url = json_encode($url); // JS-safe

    echo "<!DOCTYPE html>
<html lang='en'>
<head>
  <meta charset='UTF-8'>
  <meta http-equiv='refresh' content='3;url={$escaped_url}'>
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
  </style>
</head>
<body>
  <p>Securely redirecting via encrypt.zip...</p>
  <script>
    window.location.replace($escaped_json_url);
  </script>
</body>
</html>";
    exit;
}

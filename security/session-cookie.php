<?php
ini_set('session.use_only_cookie', 1);
ini_set('session.use_strict_mode', 1);

//Время жизни cooke в секундах
$lifetime = 60 * 30; //30 минут в секундах
$httponly = true;
$secure = true;

$domain = $_SERVER['SERVER_NAME'];

session_set_cookie_params($lifetime, '/;', $_SERVER['SERVER_NAME'], $secure, $httponly);

session_start();

if (!isset($_SESSION['last_regeneration'])) {

  session_regenerate_id(true);
  $_SESSION['last_regeneration'] = time();

} else {

  if (time() - $_SESSION['last_regeneration'] >= $lifetime) {
    session_regenerate_id(true);
    $_SESSION['last_regeneration'] = time();
  }

}
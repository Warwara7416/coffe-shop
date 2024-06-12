<?php
require_once ("config.php");
//Соединение с Базой Данных
$connect = new mysqli(HOST, USER, PASSWORD, DATABASE);

if ($connect->connect_error) {
  exit("Ошибка подключения к Базе Данных: " . $connect->connect_error);
}

//Установка кодировки
$connect->set_charset("utf8");
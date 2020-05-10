<?php

$dsn = 'mysql:host=localhost;dbname=db';
$username = 'username';
$password = 'password';
$options = [
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4',
];

$dsn = 'sqlite::memory:';
$username = '';
$password = '';
$options = [];

return (object)[
  'dsn' => $dsn,
  'username' => $username,
  'password' => $password,
  'options' => $options
];

<?php
$old_db_conf = [
    // required
    'database_type' => 'mysql',
    'database_name' => '数据库名称',
    'server' => 'ip地址',
    'username' => 'root',
    'password' => '密码',
    // [optional]
    'charset' => 'utf8',
    'port' => 3306,
    // [optional] Enable logging (Logging is disabled by default for better performance)
    'logging' => true,
    // [optional] driver_option for connection, read more from http://www.php.net/manual/en/pdo.setattribute.php
    'option' => [
        PDO::ATTR_CASE => PDO::CASE_NATURAL
    ],
    // [optional] Medoo will execute those commands after connected to the database for initialization
    'command' => [
        'SET SQL_MODE=ANSI_QUOTES'
    ]
];
$new_db_conf = [
    'database_type' => 'mysql',
    'database_name' => '数据库名称',
    'server' => 'ip地址',
    'username' => 'root',
    'password' => '密码',
    // [optional]
    'charset' => 'utf8',
    'port' => 3306,
    // [optional] Enable logging (Logging is disabled by default for better performance)
    'logging' => true,
    // [optional] driver_option for connection, read more from http://www.php.net/manual/en/pdo.setattribute.php
    'option' => [
        PDO::ATTR_CASE => PDO::CASE_NATURAL
    ],
    // [optional] Medoo will execute those commands after connected to the database for initialization
    'command' => [
        'SET SQL_MODE=ANSI_QUOTES'
    ]
];

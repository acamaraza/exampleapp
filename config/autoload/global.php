<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

return array(
    'db' => array(
        'driver' => 'Pdo',
        'dsn' => 'mysql:dbname=X;host=localhost;charset=utf8mb4',
        'user' => 'X',
        'password' => 'X',
        'driver_options' => array(
            1002 => 'SET NAMES \'utf8mb4\'', // 1002 -> \PDO::MYSQL_ATTR_INIT_COMMAND
            20 => false, // 20 -> \PDO::ATTR_EMULATE_PREPARES
        )
    ),
);

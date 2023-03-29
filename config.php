<?php

use App\models\User;

return [
    "authClass" => User::class,
    "db" => [
        "dsn" => $_ENV["DB_DSN"],
        "connection" => $_ENV["DB_CONNECTION"],
        "host" => $_ENV["DB_HOST"],
        "port" => $_ENV["DB_PORT"],
        "name" => $_ENV["DB_NAME"],
        "char_set" => $_ENV["DB_CHAR_SET"],
        "user" => $_ENV["DB_USER"],
        "password" => $_ENV["DB_PASSWORD"],
    ],
];
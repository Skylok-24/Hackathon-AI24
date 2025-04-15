<?php

require_once __DIR__ . '/app.php';

try{
    $pdo = new PDO(
        "mysql:unix_socket={$config['unix_socket']};dbname={$config['database']}",
        "{$config['user']}",
        ""
    );
}catch (PDOException $e) {
    die("could not connect to database".$e->getMessage());
}
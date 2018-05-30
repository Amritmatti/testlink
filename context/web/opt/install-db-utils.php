<?php
function getConnection($host, $database, $user, $password) {
    $conn = null;
    $connTries = 0;
    $connMaxTries = 10;
    while ($connTries < $connMaxTries) {
        sleep(2);
        try {
            return new PDO("mysql:host=${host};dbname=${database}", $user, $password);
            break;
        } catch (Exception $e) {
            if (++$connTries === $connMaxTries) {
                echo "It wasn't possible to connect to database ${_SERVER['MYSQL_HOST']}. Reason: ", $e->getMessage();
                exit(1);
            }
        }
    }
}
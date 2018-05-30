<?php
require 'install-db-utils.php';

$conn = getConnection($_SERVER['MYSQL_HOST'], $_SERVER['MYSQL_DATABASE'], 'root', $_SERVER['MYSQL_ROOT_PASSWORD']);
if ($conn->query('SHOW TABLES')->rowCount() > 0) {
    exit(0);
}

foreach (['tables', 'udf0', 'default_data'] as $migration) {
    $conn->exec(file_get_contents("${argv[1]}/install/sql/mysql/testlink_create_${migration}.sql"));
}

$conn->exec("
  DELETE FROM users;
  INSERT INTO users (`login`, `password`, `role_id`, `email`, `first`, `last`, `locale`, `active`, `cookie_string`) 
  VALUES ('${_SERVER['TL_ADMIN']}', MD5('${_SERVER['TL_ADMIN_PASSWD']}'),  8, '${_SERVER['TL_ADMIN_EMAIL']}',  'Testlink', 'Administrator',  'pt_BR', 1, CONCAT(MD5(RAND()), MD5('${_SERVER['TL_ADMIN']}')))
");

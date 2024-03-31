<?php

include __DIR__ . '/src/Framework/Database.php';

use Framework\Database;

$db = new Database('mysql', [
    'host' => 'localhost',
    'port' => 3306,
    'dbname' => 'phpr',
], 'root', '');

// try {
//     $db->connection->beginTransaction();

//     $db->connection->query("INSERT INTO products values(20, 'Cups')");
//     // $search = "Shoes 'OR 1=1 --";
//     $search = "Shoes";
//     // $query = "SELECT * FROM products WHERE name=?";
//     $query = "SELECT * FROM products WHERE name=:name";

//     $stmt = $db->connection->prepare($query);
//     // Using the bind statement
//     $stmt->bindValue('name', 'Cups', PDO::PARAM_STR);
//     $stmt->execute(
//         // [
//         // 'name' => $search
//         // ]
//     );
//     var_dump($stmt->fetchAll(PDO::FETCH_OBJ));

//     $db->connection->commit();
// } catch (Exception $error) {
//     if ($db->connection->inTransaction()) {
//         $db->connection->rollBack();
//     }
//     echo "Transaction failed!";
// }

$sqlFile = file_get_contents("./database.sql");

$db->query($sqlFile);

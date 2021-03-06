<?php
require_once('tinc.php');
$stmti = $mysqli->prepare("INSERT INTO test VALUES (NULL, uuid(), now(), ?)");
$stmts = $mysqli->prepare("SELECT COUNT(*) AS 'count', @@gtid_executed AS 'gtid_executed', @@server_id AS 'server_id' FROM test WHERE i = ?");
$i = 0;
$stmti->bind_param('i', $i);
$id = 0;
$stmts->bind_param('i', $id);
for ($i=0; $i<100; $i++)
{
    $stmti->execute();
    $id = $mysqli->insert_id;
    $stmts->execute();
    $result = $stmts->get_result();
    if ($result->num_rows != 1) {
        echo "Error! Dirty read for $i id is $id\n";
        var_dump($result->fetch_row());
        break;
    } 
}
$mysqli->close();

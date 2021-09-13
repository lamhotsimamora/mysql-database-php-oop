<?php 


include 'src/db.php';


$db = new DB();

/*
* @return Boolean
*/
$lock = $db->table('table_name')
             ->lockTable();

echo 'Lock Table : '.$lock.'<br>';

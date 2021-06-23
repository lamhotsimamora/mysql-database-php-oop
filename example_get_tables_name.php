<?php

include 'src/db.php';


$db = new DB();

/*
* select table_name FROM information_schema.tables where table_schema = "database_name"
*/
$obj = $db->getTables();

/*
* @return Array
* $obj->data()
*/

// OR

/*
* @return JSON
*/
echo $obj->toJson();

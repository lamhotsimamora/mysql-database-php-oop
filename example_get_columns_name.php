<?php

include 'src/db.php';


$db = new DB();

/*
* select * from table_name
*/
$obj = $db->table('table_name')
           ->getColumns();

/*
* @return Array
*/
//var_dump($obj->data());

// OR

/*
* @return JSON
*/
echo $obj->toJson();

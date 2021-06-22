<?php

include 'src/db.php';


$db = new DB();

/*
* select * from table_name
*/
$obj = $db->query('select * from table_name');

/*
* @return Array
* $obj->data()
*/

// OR

/*
* @return JSON
*/
echo $obj->toJson();
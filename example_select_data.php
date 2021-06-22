<?php

include 'src/db.php';


$db = new DB();

/*
* select * from table_name
*/
$obj = $db->select('*')
            ->from('table_name')
            ->get();

/*
* @return Array
* $obj->data()
*/

// OR

/*
* @return JSON
*/
echo $obj->toJson();
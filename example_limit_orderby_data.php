<?php

include 'src/db.php';


$db = new DB();

/*
* select * from table_name limit 2 order by id desc
*/
$obj = $db->select('*')
            ->from('table_name')
            ->limit(2)
            ->orderBy('id desc')
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
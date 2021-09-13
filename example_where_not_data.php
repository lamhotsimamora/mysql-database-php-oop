<?php

include 'src/db.php';


$db = new DB();

/*
* select * from table_name where id != 1
*/
$obj = $db->select('*')
          ->from('table_name')
           ->whereNot([
                'id'=>1
            ])
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


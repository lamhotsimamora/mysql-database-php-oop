<?php

include 'src/db.php';


$db = new DB();

/*
* select * from table_name where id = 303
*/
$obj = $db->select('*')
          ->from('table_name')
           ->where([
                'id'=>2
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

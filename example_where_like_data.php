<?php

include 'src/db.php';


$db = new DB();

/*
* select * from table_name where id like 1 and username like '%john%'
*/
$obj = $db->select('*')
          ->from('table_name')
           ->whereLike([
                'id'=>1,
                'username' =>'john'
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


<?php

include 'src/db.php';


$db = new DB();

/*
* select * from table_name where id like 303 and username like '%john%'
*/
$obj = $db->select('*')
          ->from('table_name')
           ->whereLike([
                'id'=>1,
                'column1' =>'A'
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


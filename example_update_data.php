<?php

include 'src/db.php';


$db = new DB();


/*
* update table_name set column_table='value' where id = 1
*/
$obj = $db->table('table_name')
          ->where(['id'=>1])
          ->update(['column1'=>'value11','column2'=>'value22']);

/*
* @return Boolean
*/
var_dump($obj->result);

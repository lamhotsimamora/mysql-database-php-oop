<?php

include 'src/db.php';


$db = new DB();

/*
* delete from table_name where id=1
*/
$obj = $db->table('table_name')
            ->where(['id'=>1])
            ->delete();
/*
* @return Boolean
*/    
var_dump($obj->result);
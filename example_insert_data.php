<?php

include 'src/db.php';


$db = new DB();

/*
* insert into table_name(column1,column2) values('value1','value2')
*/
$obj = $db->select('column1','column2')
           ->table('table_name')
            ->insert('value1','value2');
/*
* @return Boolean
*/       
var_dump($obj->result);

/* 
* For get last insert id
* @return Integer
*/
echo $obj->getInsertId();
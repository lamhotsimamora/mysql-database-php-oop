<?php

include 'src/db.php';



$db = new DB();

/*
* select count(*) from table_name
*/
$obj = $db->select('*')
          ->from('table_name')
          ->getCount();

/*
* @return Integer
*/    
echo $obj;

<?php 


include 'src/db.php';


$db1 = new DB();
$db2 = new DB();


$obj1 = $db1->getConId();
$obj2 = $db2->getConId();

var_dump($obj1);
echo '<hr>';
var_dump($obj2);

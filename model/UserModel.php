<?php 

include 'Model.php';


$user = new Model('users');

// insert data
$process = $user->db->select('username','email','password')
                     ->table('users')
                     ->insert('lamhot','lamhot@gmail.com','12345');

 echo $process->result;
 
 echo $process->getInsertId();

// get all data from table users
echo $user->getAll();

// delete data from table users where id_user = 1
echo $user->delete([
                'id_user'=> 1
            ]);
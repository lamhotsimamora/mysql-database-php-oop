<?php 

include '../src/db.php';

class Model 
{
    public $db;
    public $table_name;

    public function __construct($table_name){
        $this->db = new DB();

        $this->table_name = $table_name;
    }

    public function getAll(){
        return $this->db->select('*')
                          ->from($this->table_name)
                          ->get()->toJson();
    }

    public function delete($data){
        return $this->db->table($this->table_name)
            ->where($data)
            ->delete()->toJson();
    }

}


<?php

/*
* Original File 'MySQL OOP Class'
* Made With Love by Lamhot Simamora
* https://github.com/lamhotsimamora
* Free license & open source
* September@2021
*/

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: POST , GET, OPTIONS");

class DB
{
	private $table      = null;
	private $username   = null;
	private $server     = null;
	private $password   = null;
	private $database   = null;

	private $conn       = null;
	
	public  $result     = null;
	private $query      = null;
	private $query_where = false;
	private $query_limit = false;
	private $query_orderby = false;
	private $query_where_like =false;

	private $columns    = array();
	private $wheres     = null;
	private $where_like =null;
	private $values     = null;
	private $limit_number = false;
	private $order_by_columns = false;
	private $conn_id=null;
	private $lock_table = false;

	private $data = null;
	public $count = null;

	private $all_tables = null;
	
	private $init = [
		'username' => 'YOUR_USERNAME',
		'password' => 'YOUR_PASSWORD',
		'server'   => 'YOUR_SERVER',
		'database' => 'YOUR_DATABASE'
	];

	public function __construct(){
		$this->username = $this->init['username'];
		$this->password = $this->init['password'];
		$this->server   = $this->init['server'];
		$this->database = $this->init['database'];
		$this->lock_table = false;
		$this->connect();
		$this->error();
		return $this;
	}    

	public function lockTable($permission=null){
		$permission_access='WRITE';
		if ($permission){
			if ($permission['read'] && $permission['read']==true){
				$permission_access .= ',READ';
			}
		}
		$final_query = 'LOCK TABLES '.$this->table.' '.$permission_access;
		$this->query = ($final_query);
		return $this->proccess();
	}

	public function unlockTable(){
		$this->query = ('UNLOCK TABLES');
		$this->proccess();
		return $this->toJson();
	}

	/*
	* Connection Id
	*/
	public function getConId(){
		$this->query('SELECT CONNECTION_ID()');
		return $this->conn_id = $this->data['CONNECTION_ID()'];
	}

	public function getSessionUser(){
		$this->query('SELECT SESSION_USER()');
		return $this->toJson();
	}

	public function showDatabase(){
		$this->query('SELECT DATABASE()');
		return $this->toJson();
	}

	public function from($val){
		$this->table($val);
		return $this;
	}

	public function table($val){
		$this->table = $val;
		return $this;
	}

	public function insert($val)
	{
		$this->values = func_get_args();
		$this->queryInsert();
		$this->proccess();
		return $this;
	}

	public function limit($val){
		$this->limit_number = $val;
		$this->queryLimit();
		return $this;
	}

	public function orderBy($val){
		$this->order_by_columns = $val;
		$this->queryOrderBy();
		return $this;
	}

	private function error(){
		if ($this->conn->connect_error){
			exit("Error when try to connect database !");
		}
	}

	private function connect(){
		try {
			$conn =  $this->conn = new mysqli(
								$this->server, 
								$this->username, 
								$this->password, 
								$this->database
							);
			return $conn;
		} catch (Exception $e) {
			exit('[Error] '.$e);
		}
	}

	public function select(){
		$this->columns = func_get_args();
		return $this;
	}

	public function whereLike($val){
		$this->where_like = $val;
		$this->queryWhereLike();
		return $this;
	}

	public function where($val){
		$this->wheres = $val;
		$this->queryWhere();
		return $this;
	}

	public function getQuery(){
		return $this->query;
	}

	public function update($val){
		$this->queryUpdate($val);
		$this->proccess();
		return $this;
	}

	public function query($query){
		$this->query = $query;
		$this->proccessWithData();
		return $this;
	}

	public function getTables(){
		$this->query('select table_name FROM information_schema.tables
		where table_schema = "'.$this->init['database'].'"');
		$this->all_tables = $this->proccessWithData();
		$this->proccessCount();
		return $this;
	}

	public function getFields(){
		$this->getColumns();
		return $this;
	}

	public function getColumns(){
		$this->query('DESCRIBE '.$this->table);
		$this->proccessWithData();
		$this->proccessCount();
		return $this;
	}

	public function getInsertId(){
		return $this->conn->insert_id;
	}

	private function queryOrderBy(){
		if ($this->order_by_columns!=false){
			$this->query_orderby = ' order by '.$this->order_by_columns;
		}
	}

	private function queryUpdate($val){
		$columns ='';
		$count_column = count($val);
		
		$i=0;
		foreach ($val as $key => $value) {
			if (is_string($value)){
				$value='"'.$value.'"';
			}
			if ($i==0 && ($count_column==1)){
				$columns .= $key.'='.$value;
			}else{
				if ($i==($count_column-1)){
					$columns .= $key.'='.$value;
				}else{
					$columns .= $key.'='.$value.' , ';
				}
			}
			$i++;	
		}
		$this->query = 'update '.$this->table.' set '.$columns.$this->query_where.'';
		return $this;
	}

	private function queryWhereLike()
	{
		if ($this->where_like!=false){
			$where_like = '';
			$count_where_like = count($this->where_like);
			
			$i=0;
			foreach ($this->where_like as $key => $value) {
				if (is_string($value)){
					$value = '"%'.$value.'%"';
				}
				if ($i==0 && ($count_where_like==1)){
					$where_like = $key. ' like '.$value.'';
				}else{
					if ($i==($count_where_like-1)){
						$where_like .= $key.' like '.$value.'';
					}else{
						$where_like .= $key.' like '.$value.' AND ';
					}
				}	
				$i++;
			}
			$this->query_where_like = ' where '.$where_like;
		}
	}

	private function queryLimit(){
		if ($this->limit_number !=false){
			$this->query_limit = ' limit '.$this->limit_number;
		}
	}

	private function queryGet(){
		$columns = '';
		$count_column = count($this->columns);
	
		foreach ($this->columns as $key => $value) {
			if ($key==0 && ($count_column==1)){
				$columns .= $value;
			}else{
				if ($key==($count_column-1)){
					$columns .= $value;
				}else{
					$columns .= $value.',';
				}
			}	
		}
		$this->query =  'select '.$columns.' from '.$this->table.'';

		if ($this->query_where!=false){
			$this->queryWhere();
			$this->query =  $this->query.$this->query_where;
		}
		else if ($this->query_where_like != false){
			$this->queryWhereLike();
			$this->query =  $this->query.$this->query_where_like;
		}

		$this->queryOrderBy();

		if ($this->query_orderby!=false){
			$this->query =  $this->query.$this->query_orderby;
		}

		$this->queryLimit();

		if ($this->query_limit!=false)
		{	
			$this->query =  $this->query.$this->query_limit;
		}
	}

	private function queryDelete(){
		$this->query ='delete from '.$this->table.$this->query_where;
	}

	private function queryInsert(){
		
		$columns = '';
		$count_column = count($this->columns);
	
		foreach ($this->columns as $key => $value) {
			if ($key==0 && ($count_column==1)){
				$columns .= $value;
			}else{
				if ($key==($count_column-1)){
					$columns .= $value;
				}else{
					$columns .= $value.',';
				}
			}	
		}
		$values = '';
		$count_values = count($this->values);
	
		foreach ($this->values as $key => $value) {
			if (is_string($value)){
				$value = '"'.$value.'"';
			}
			if ($key==0 && ($count_values==1)){
				$values .= $value;
			}else{
				if ($key==($count_values-1)){
					$values .= $value;
				}else{
					$values .= $value.',';
				}
			}	
		}

		$this->query  = 'insert into '.$this->table.' ( '.$columns.') VALUES ('.$values.')';
		return $this;
	}


	private function queryWhere(){
		if ($this->wheres != null ){
			$wheres = '';
			$count_where = count($this->wheres);
			
			$i=0;
			foreach ($this->wheres as $key => $value) {
				if (is_string($value)){
					$value = '"'.$value.'"';
				}
				if ($i==0 && ($count_where==1)){
					$wheres .= $key.'='.$value;
				}else{
					if ($i==($count_where-1)){
						$wheres .= $key.'='.$value;
					}else{
						$wheres .= $key.'='.$value.' AND ';
					}
				}	
				$i++;
			}
			$this->query_where =' where '.$wheres.'';
		}
	}

	public function toJson(){
		return json_encode($this->data);
	}

	public function data(){
		return $this->data;
	}	

	public function getCount(){
		$this->queryGet();
		$this->proccessCount();
		return $this;
	}

	private function proccessCount(){
		try {
			$result =$this->conn->query($this->query);
            if ($result) 
			{
                $this->count =  ($result->num_rows) ? $result->num_rows : null;
            }
			return $this->count;
		} catch (\Throwable $th) {
			exit('Proccess Count Error Because '.$th);
		}
	}

	public function get(){
		$this->queryGet();
		$this->proccessWithData();
		return $this;
	}

	public function delete(){
		$this->queryDelete();
		$this->proccess();
		return $this;
	}

	private function proccess(){
		$this->result = $this->conn->query($this->query);
		return $this->result ? true : false;
	}

	private function proccessWithData(){
		$this->result = $this->conn->query($this->query);

		if ($this->result){
			$this->count =$count_data  = $this->result->num_rows;
			if ($count_data==1){
				$this->data = $this->result->fetch_assoc();
				return true;
			}
			else
			{
				$obj = [];
	            $i =0 ;
	            while($row = $this->result->fetch_assoc()) {
	                $obj[$i] = $row;
	                $i++;
	            }
	            $this->data = $obj;
	            return true;
			}
		}
		else{
			return false;
		}
	}
}

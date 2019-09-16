<?php


class application extends db
{
	public $table_name = '';
	
//	DB connection
	public function __construct(){
		parent::__construct();
	}
	
//	getting all rows from table
	public function getrows($query){	
		try{	
			$result = $this->conn->query($query);

			if ($result == false) {
				return false;
			} 

			$rows = array();

			while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
				$rows[] = $row;
			}

			return $rows;
		}
		catch(PDOException $exception){
			echo "Error: " . $exception->getMessage();
		}
	}
	
//	getting one row form table
	public function getrow($query){	
		try{	
			$result = $this->conn->query($query);

			if ($result == false) {
				return false;
			} 

			$row = array();
			$row = $result->fetch(PDO::FETCH_ASSOC);
			return $row;
		}
		catch(PDOException $exception){
			echo "Error: " . $exception->getMessage();
		}
	 
	}
	
//	inserting a row into table
	public function add($data){
		try{
			foreach($data as $key => $value){
				if($key == 'password'){
					$keys[] = $key;
					$values[] = password_hash($value, PASSWORD_DEFAULT);
//					print_r($values);
				}
				else{
					$keys[] = $key;
					$values[] = htmlspecialchars($value);
				}
			}
//			print_r($data);
			$sql = "INSERT INTO `" .$this->table_name. "`(`". implode('`, `', $keys)."`) 
			VALUES ('". implode("', '", $values)."')";
			$stmt = $this->conn->prepare($sql);
			$stmt->execute();
			
			if($stmt === false){
				return false;
			}else{
				return true;
			}
		}
		catch(PDOException $exception){
			echo "Error: " . $exception->getMessage();
		}
	}
	
//	updating a row of data in the table
	public function update($data, $where = array()){
		try{
			foreach($data as $key => $value){
                if($key == 'password'){
					$values = password_hash($value, PASSWORD_DEFAULT);
                    $update[] = "$key = '".htmlspecialchars($values)."'";
//					print_r($values);
				}else{
                    $update[] = "$key = '".htmlspecialchars($value)."'";
                }
				
			}
			foreach($where as $key => $value){
				$where_condition[] = "$key = '".htmlspecialchars($value)."'";
			}
				
			$sql = "UPDATE `" .$this->table_name. "` SET ".implode(', ', $update). " WHERE ".implode(' AND ', $where_condition);
			$stmt = $this->conn->prepare($sql);
			$stmt->execute();
			if($stmt === false){
				return false;
			}else{
//				$row = $this->getrow("SELECT*FROM ".$this->table_name." WHERE ".implode(' AND ', $where_condition));
//				if($row['username']){
//					$_SESSION['username'] = $row['username'];
//				}
				return true;
			}
		}
		catch(PDOException $exception){
			echo "Error: " . $exception->getMessage();
		}
	}
	
//	deleting a row
	public function deletes($data = array()){
		try{
			foreach($data as $key => $value){
				$where_condition[] = "$key = '".htmlspecialchars($value)."'";
			}
			
			$sql = "DELETE FROM `" .$this->table_name. "` WHERE ".implode(' AND ', $where_condition);
			$stmt = $this->conn->prepare($sql);
			$stmt->execute();
			
			if($stmt === false){
				return false;
			}else{
				return true;
			}
		}
		catch(PDOException $exception){
			echo "Error: " . $exception->getMessage();
		}
	}
	
//  executing query
	public function query($query){	
		try{	
			$result = $this->conn->prepare($query);
			$result->execute();

			if ($result == false) {
				return false;
			} 

			$rows = array();

			while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
				$rows[] = $row;
			}

			return $rows;
		}
		catch(PDOException $exception){
			echo "Error: " . $exception->getMessage();
		}
	}
	
//  count all rows
	public function countRows(){
		
		try{
			$query = "SELECT * FROM " . $this->table_name . "";

			$stmt = $this->conn->prepare( $query );
			$stmt->execute();

			$num = $stmt->rowCount();

			return $num;
		}
		catch(PDOException $exception){
			echo "Error: " . $exception->getMessage();
		}
		
	}
	
//  count rows with condition
	public function countRow($where){
		
		try{
			foreach($where as $key => $value){
				$where_condition[] = "$key = '".htmlspecialchars($value)."'";
			}

		$query = "SELECT * FROM " . $this->table_name . " WHERE " . implode(' AND ', $where_condition);
//		echo $query;
		$stmt = $this->conn->prepare( $query );
		$stmt->execute();

		$num = $stmt->rowCount();

		return $num;
		}
		catch(PDOException $exception){
			echo "Error: " . $exception->getMessage();
		}
	}
	
//  get rows with condition and limit value
	function read($where, $from_record_num, $records_per_page){
 		
		try{
			foreach($where as $key => $value){
				$where_condition[] = "$key = '".htmlspecialchars($value)."'";
			}
		$query = "SELECT*FROM " . $this->table_name . "
				WHERE ". implode(' AND ', $where_condition) ."
				ORDER BY
					pro_name ASC
				LIMIT
					{$from_record_num}, {$records_per_page}";
//		echo $query;
			
		$result = $this->conn->prepare( $query );
		$result->execute();
		
		if ($result == false) {
				return false;
			} 

		$rows = array();

		while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
			$rows[] = $row;
		}

		return $rows;

		}
		catch(PDOException $exception){
			echo "Error: " . $exception->getMessage();
		}
		
	}
	
//  get all rows with limit
	function readAll($from_record_num, $records_per_page){
 		
		try{
			
		$query = "SELECT*FROM " . $this->table_name . "
				ORDER BY
					pro_name ASC
				LIMIT
					{$from_record_num}, {$records_per_page}";
//		echo $query;
			
		$result = $this->conn->prepare( $query );
		$result->execute();
		
		if ($result == false) {
				return false;
			} 

		$rows = array();

		while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
			$rows[] = $row;
		}

		return $rows;

		}
		catch(PDOException $exception){
			echo "Error: " . $exception->getMessage();
		}
		
	}
	
		
}
?>
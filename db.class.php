<?php
class db{
  
    // specify your own database credentials
    private $host = "localhost";
    private $db_name = "db_name";
    private $username = "root";
    private $password = "";
    public $conn;
  
    // get the database connection
    public function __construct(){
  
        $this->conn = null;
  
        try{
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username,
             $this->password);
             $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }
  
        return $this->conn;
	}
	
	//login user 
	public function login($where){
		try{
//			$username = $where['username'];
            if($where['username']){
		      $result = $this->conn->query("SELECT*FROM ".$this->table_name." WHERE username = '".$where['username']."'");
            }
            else{
              $result = $this->conn->query("SELECT*FROM ".$this->table_name." WHERE email = '".$where['email']."'");
            }
            //OR email = '".$where['email']."'");
//			print_r($result);
			$row = $result->fetch(PDO::FETCH_ASSOC);
			$no_of_rows = $result->rowCount();
//			echo $row['password'];
			echo "password_verify(".$where['password'].", ".$row['password'].")";
			if($no_of_rows > 0)
			    {  
					if(password_verify($where['password'], $row['password'])){
						if($row['username']){
							$_SESSION['username'] = $row['username'];
						}
						if($row['id']){
							$_SESSION['id'] = $row['id'];
						}
						return true;
					}
					else{
						$_SESSION['message'] = "password incorrect!";
						return false;
					}
					
				}
			else
				{
					$_SESSION['message'] = "username and password incorrect!";
					return false;
				}
			  }
		    catch(PDOException $exception){
				echo "Error: " . $exception->getMessage();
			}
	    }

	//check user loggedin or not									 
	public function is_loggedin(){
		if(isset($_SESSION['id'])){
			return true;
		}
	}
	
	//logout									 
	public function logout(){
		session_start();
		session_destroy();
		return true;
	}
}

?>
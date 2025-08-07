<?php

class QueryManager{
	//static $server = "103.212.120.23";
  	//static $database = "shop_cart_session";
  	//static $user = "root";
  	//static $password = "";
	
	static $server = "103.212.120.23";
  	static $database = "onlineiot";
  	static $user = "root";
  	static $password = "BeagleBone99";
    private static $connection = null;
   
   static public function getSqlConnection(){
  		//   		echo $qry;
  		if (self::$connection === null || !self::$connection->ping()) {
  			self::$connection = mysqli_connect(self::$server,self::$user,self::$password,self::$database);
  			
  			if (!self::$connection) {
  				throw new Exception("Failed to connect to MySQL: " . mysqli_connect_error());
  			}
  			
  			// Set wait_timeout and interactive_timeout
  			self::$connection->query("SET SESSION wait_timeout=28800");
  			self::$connection->query("SET SESSION interactive_timeout=28800");
  		}
  		return self::$connection;
 	}
  	
	
  	static public function executeQuerySqli($qry){
//   		echo $qry;
  		$con = self::getSqlConnection();
  		$result = $con->query($qry);
  		
  		if($result){
  			return true;
  		}else{
  			// Retry once if server has gone away
  			if (strpos($con->error, 'server has gone away') !== false || strpos($con->error, 'Lost connection to MySQL server') !== false) {
  				self::$connection = null; // Force reconnect
  				$con = self::getSqlConnection();
  				$result = $con->query($qry);
  				if($result){
  					return true;
  				}
  			}
  			echo $con->error;
  			return false;
  		}
  		
  	}
  	
  	static public function getMultipleRow($qry)
  	{
  		$con = self::getSqlConnection();
  		$result = $con->query($qry);
  		if (!$result) {
  			// Retry once if server has gone away
  			if (strpos($con->error, 'server has gone away') !== false || strpos($con->error, 'Lost connection to MySQL server') !== false) {
  				self::$connection = null;
  				$con = self::getSqlConnection();
  				$result = $con->query($qry);
  			}
  			if (!$result) {
  				echo $con->error;
  			}
  		}
  		return $result;
  			
  	}
  	
  	static public function getonerow($qry)
  	{
  		$con = self::getSqlConnection();
  		$result = mysqli_query($con,$qry);
  		if ($result)
  		{
  			$row=mysqli_fetch_row($result);
  			mysqli_free_result($result);
  			return $row;
  		}else{
  			// Retry once if server has gone away
  			if (strpos(mysqli_error($con), 'server has gone away') !== false || strpos(mysqli_error($con), 'Lost connection to MySQL server') !== false) {
  				self::$connection = null;
  				$con = self::getSqlConnection();
  				$result = mysqli_query($con,$qry);
  				if ($result) {
  					$row=mysqli_fetch_row($result);
  					mysqli_free_result($result);
  					return $row;
  				}
  			}
  			return null;
  		}
  			
  	}

    // Alias for getonerow to maintain consistency
    static public function getSingleRow($qry)
    {
        return self::getonerow($qry);
    }

    // Alias for executeQuerySqli to maintain consistency
    static public function executeQuery($qry)
    {
        return self::executeQuerySqli($qry);
    }

    static public function prepareStatement($query) {
        $con = self::getSqlConnection();
        
        // Try to prepare the statement
        $stmt = $con->prepare($query);
        
        if (!$stmt) {
            // If prepare fails, try to reconnect once and prepare again
            self::$connection = null;
            $con = self::getSqlConnection();
            $stmt = $con->prepare($query);
            
            if (!$stmt) {
                throw new Exception("Failed to prepare statement: " . $con->error);
            }
        }
        
        return $stmt;
    }
}
?>

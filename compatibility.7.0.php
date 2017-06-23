<?php
// funzioni di retro compatibilità con le versioni 5.x per la versione 7.0 di php
if(
	!function_exists("mysql_pconnect") && 
	!function_exists("mysql_select_db") && 
	!function_exists("mysql_query") &&
	!function_exists("mysql_fetch_row") &&
	!function_exists("mysql_fetch_array") &&
	!function_exists("mysql_fetch_assoc") &&
	!function_exists("mysql_escape_string") &&
	!function_exists("mysql_num_rows") &&
    !function_exists("mysql_data_seek") &&
    !function_exists("mysql_free_result") &&
    !function_exists("mysql_close") &&
    !function_exists("mysql_real_escape_string") &&
    !function_exists("mysql_error") &&
    !function_exists("mysql_result")
    )
{
	$_host 	= "";
	$_user 	= "";
	$_pass 	= "";
    $_db = "";
    
	function mysql_pconnect($host, $user, $pass, $db){
		global $con, $_host, $_user, $_pass, $_db;
		$_host = $host;
		$_user = $user;
		$_pass = $pass;
        $_db    = $db;
        $con = mysqli_connect("p:" . $_host,$_user,$_pass,$_db);
        if(mysqli_connect_errno()){
            echo "Error: Unable to connect to MySQL." . PHP_EOL;
            echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
            echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
            exit;              
			die(mysqli_error());
        }
		return $con;
	}
	function mysql_select_db($db, $con){
		global $con, $_host, $_user, $_pass, $_db;
		mysqli_select_db($con, $db);        
	}
	function mysql_query($query, $con){
		return mysqli_query($con, $query);
	}
	function mysql_fetch_row($result){
		return mysqli_fetch_row($result);
	}
	function mysql_fetch_array($result){
		return mysqli_fetch_array($result);
	}
	function mysql_fetch_assoc($result){
		return mysqli_fetch_assoc($result);
    }
	function mysql_num_rows($result){
		return mysqli_num_rows($result);
    }
    function mysql_data_seek($result, $num){
        return mysqli_data_seek($result, $num);
    }
    function mysql_free_result($result){
        mysqli_free_result($result);
    }
    function mysql_close($con){
        mysqli_close($con);
    }
    function mysql_real_escape_string($string){
        global $con;
        return mysqli_real_escape_string($con, $string);
    }
	function mysql_error(){
        global $con;
		return mysqli_error($con);
    }
    function mysql_result($result, $row, $col){
        return mysqli_result($result, $row, $col);    
    }
	function mysql_escape_string($string){ // alias di mysql_real_escape_string
        return mysql_real_escape_string($string);
	}
}

if(!function_exists("mysqli_result")){
    function mysqli_result($res,$row=0,$col=0){ 
        $numrows = mysqli_num_rows($res); 
        if ($numrows && $row <= ($numrows-1) && $row >=0){
            mysqli_data_seek($res,$row);
            $resrow = (is_numeric($col)) ? mysqli_fetch_row($res) : mysqli_fetch_assoc($res);
            if (isset($resrow[$col])){
                return $resrow[$col];
            }
        }
        return false;
    }
}
?>
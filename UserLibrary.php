<?php
	// If you installed via composer, just use this code to require autoloader on the top of your projects.
	require 'vendor/autoload.php';
	 
	// Using Medoo namespace
	use Medoo\Medoo;
	if(!empty($databasename) && !empty($ipaddress) && !empty($username) && !empty($password) && !empty($usertablename)){
		  
		$database = new Medoo([
				// required
				'database_type' => 'mysql',
			    'database_name' => $databasename, 			           
				'server' => $ipaddress, 					
				'username' => $username, 	
				'password' => $password
				]);
		
	    function CreateUserDataTable() {
	        global $database;
	        global $usertablename;
	        $database->query("CREATE TABLE IF NOT EXISTS " . $usertablename . " (
						id MEDIUMINT NOT NULL AUTO_INCREMENT,
						username text NOT NULL,
						password text NOT NULL,
						PRIMARY KEY (id)
						) ENGINE NDB;");
	    }
	    
	    function addUserTableColumn($newcolumnname, $type, $nullvalue = "") {
	        global $database;
	        global $usertablename;
	        $database->query("ALTER TABLE " . $usertablename ." ADD column_name " . $type . $nullvalue . ";");
	    }

		function checkSignUp($user, $password) {
		    global $usertablename;
			global $database;
			$query = $database->select($usertablename, "*", [
				"username" => $user
			]);
			if (!$query) {
	    		createUser($user, $password);
	    		var_dump( $database->error() );
				} else {
	    			echo "Sorry. Username already exists.";
				}
		};
		
		function setUserSession($user, $password) {
		    global $database;
			global $usertablename;
			$query = $database->select($usertablename, ["id", "password"], [
					"username" => $user
				]);
				if (!$query) {
					echo "Uh oh. Something big broke.";
				} else {
					$id = $query[0]["id"];
					$hash = $query[0]["password"];
				}
		};

		function createUser($user, $password) {
			global $database;
			global $usertablename;
			$newpassword = hashpassword($password); 
			$query = $database->insert($usertablename, array(
					"username" => $user,
					"password" => $newpassword
				));
			if (!$query) {
				echo "Uh oh. Something broke.";
			} else {
				    setUserSession($user, $password);
					
					$getid = $database->select($usertablename, "id", [
					"username" => $user
					]);
					if ($getid){
						login($getid, $newpassword);
						if (verifyLogin()) {
							echo "It worked!";
						}
					}
					else {"There's been a slight problem.";}
				}
		};

		function hashpassword($oldpassword) {
			$oldpassword = password_hash($oldpassword, PASSWORD_BCRYPT);
			return $oldpassword;
		};

		function SanitizeString($string) {
			$new_string = preg_replace('~[^a-zA-Z0-9]+~', '', $string);
			return $new_string;
		};

		function login($id, $hash) {
			$_SESSION['id'] = $id;
			$_SESSION['hash'] = $hash;
		}

		function verifyLogin() {
		    global $usertablename;
			global $database;
			if (isset($_SESSION["id"]) && isset($_SESSION["hash"])) {
				$query = $database->select($usertablename, "*", [
				"id" => $_SESSION['id'],
				"password" => $_SESSION["hash"]
				]);
				if (!$query) {
					return false;
				} else {
					return true;
				}
			} else {
				return false;
			}
		}
	};

?>
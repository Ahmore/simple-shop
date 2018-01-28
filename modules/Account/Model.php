<?php
	class Account_Model {
		public function login($options) {
			global $_POST;
			
			if (Login::isLogged()) {
				return array("result" => true);
			}
			
			if (isset($_POST["username"]) && isset($_POST["password"]) && $_POST["username"] != "" && $_POST["password"] != "") {
				$username = $_POST["username"];
				$password = $_POST["password"];
				
				try	{
					$login = new Login();
					$logged = $login->log($username, $password);
					
					return array("result" => true);
				}
				catch (Exception $e) {
					return array("result" => false, "message" => $e->getMessage());
				}
			}
			else {
				return array("result" => false, "message" => "");
			}
		}
		
		public function logout($options) {
			if (Login::logOut()) {
				return array();
			}
			
			throw new Exception(1);
		}
		
		public function info($options) {	
			global $cfg;
			
			// Sprawdzanie stanu sesji i praw dostępu
			Session::normal();
			
			$results = DataManager::getAllUserInformations(Login::getUserId());
			
			if ($results) {
				return array("results" => $results);
			}
			
			throw new Exception();
		}
		
		public function edit($options) {	
			global $cfg;
			
			// Sprawdzanie stanu sesji i praw dostępu
			Session::normal();
			
			$results = DataManager::getAllUserInformations(Login::getUserId());
			
			if ($results->num_rows == 0) {
				throw new Exception();
			}
			
			return array("results" => $results);
		}
		
		public function save($options) {	
			global $_POST;
			global $cfg;
			
			// Sprawdzanie stanu sesji i praw dostępu
			Session::normal();
			
			$results = DataManager::saveUserInformations($_POST, Login::getUserId());
			
			if ($results) {
				return array("results" => $results);
			}
			
			throw new Exception();
		}
		
		public function orders($options) {
			global $_POST;
			global $_cfg;
			
			// Sprawdzanie stanu sesji i praw dostępu
			Session::normal();
			
			// Pobiera zamówienia uzytkownika
			$results = DataManager::getUsersOrders(Login::getUserId());
			
			if ($results->num_rows == 0) {
				throw new Exception();
			}
			
			return array("results" => $results);
		}
		
		public function order($options) {
			global $_POST;
			global $_cfg;
			
			// Sprawdzanie stanu sesji i praw dostępu
			Session::normal();
			
			$order_id = $options["id"];
			$results = DataManager::getOrderById($order_id, Login::getUserId());
			
			if ($results->num_rows == 0) {
				throw new Exception();
			}
			
			return array("results" => $results);
		}
		
		public function register($options) {
			global $_POST;
			
			// Zapamiętuje dane wpisane przez użytkownika
			$_SESSION["register-try-username"] = @$_POST["username"];
			$_SESSION["register-try-password"] = @$_POST["password"];
			$_SESSION["register-try-repeat_password"] = @$_POST["repeat_password"];
			$_SESSION["register-try-email"] = @$_POST["email"];
			
			if (isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["repeat_password"]) && isset($_POST["email"])) {
				$register = new Login();
				
				try {
					$result = $register->register($_POST["username"], $_POST["password"], $_POST["repeat_password"], $_POST["email"]);
					return array("results" => $result, "message" => "");
				}
				catch (Exception $e) {
					return array("results" => false, "message" => $e->getMessage());
				}
			}
			else {
				return array("results" => false, "message" => "");
			}
		}
	}
?>
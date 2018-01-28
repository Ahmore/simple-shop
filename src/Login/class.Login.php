<?php
	@session_start();
	
	class Login {
		private $_mMysqli;
		private $_loginError;
		static public $session_length = 3600;		// Czas zwłoki lub czas sesji w zależności od oprawy
		
		public function __construct($session_length = false) {
			global $cfg;
			
			if ($session_length) {
				Login::$session_length = $session_length;
			}
			
			$this->_mMysqli = new mysqli($cfg["db"]["host"], $cfg["db"]["user"], $cfg["db"]["password"], $cfg["db"]["database"]) or die("Błąd połączenia z bazą danych!");
			$this->_mMysqli->query ('SET NAMES utf8');
			$this->_mMysqli->query ('SET CHARACTER_SET utf8_unicode_ci');
		}
		
		// Próba zalogowania
		public function log($username, $password) {
			$username = $this->_mMysqli->real_escape_string($username);			$username = trim($username);
			$password = $this->_mMysqli->real_escape_string($password);			$password = trim($password);
		
			if (!empty($username) && !empty($password)) {
				$sol = $this->_getSol($username);
				
				$query = "SELECT * FROM users WHERE username = '$username' AND password = '" . md5($password . $sol) . "' AND blockade = '0'";
				$results = $this->_mMysqli->query($query);
				
				
				if ($results->num_rows == 1) {
					$row = $results->fetch_array(MYSQLI_ASSOC);
					$id = $row["id"];
					$type = $row["type"];
					$email = $row["email"];
					
					
					// Dodaje do tablicy sesji potrzebne dane
					$_SESSION['Login-username'] = $username;
					$_SESSION['Login-iduser'] = $id;
					$_SESSION['Login-usertype'] = $type;
					$_SESSION['Login-email'] = $email;
					$_SESSION['Login-ip'] = $_SERVER['REMOTE_ADDR'];
					$_SESSION['Login-logintime'] = time();
					$_SESSION['Login-login'] = true;
					
					$this->_setAllInformations($results);
					
					return true;
				}
				
				$_SESSION['Login-login'] = false;
				throw new Exception("Błędne dane");
				return false;
				
			}
			
			$_SESSION['Login-login'] = false;
			throw new Exception("Podaj dane");
			return false;
		}
		
		private function _getSol($user) {
			$query = "SELECT sol FROM users WHERE username = '$user'";
			$result = $this->_mMysqli->query($query);
			$row = $result->fetch_array(MYSQLI_ASSOC);
			
			return $row["sol"];
		}
		
		private function _generateStr($length = 5) {
			$str = '';
			$letters = "abcdefghijklmnopqrstuxyvwzABCDEFGHIJKLMNOPQRSTUXYVWZ1234567890+-*#&@!?";
			$amount_of_letters = strlen($letters);

			for ($i = 0; $i < $length; $i++) {
				$index = mt_rand(0, $amount_of_letters - 1);
				$str .= $letters[$index];
			}
			return $str;
		}
		
		public function register($username, $password, $repeat_password, $email) {
			$username = $this->_mMysqli->real_escape_string($username);							$username = trim($username);
			$password = $this->_mMysqli->real_escape_string($password);							$password = trim($password);
			$repeat_password = $this->_mMysqli->real_escape_string($repeat_password);			$repeat_password = trim($repeat_password);
			$email = $this->_mMysqli->real_escape_string($email);								$email = trim($email);
			$errors = array(
				"username" => false,
				"password" => false,
				"email" => false
			);
			
			if (!empty($username) && !empty($password) && !empty($repeat_password) && !empty($email)) {
			
				// Sprawdza czy użytkownik o tej nazwie już nie istnieje
				if (!$this->_checkUserName($username)) {
					$errors["username"] = true; //"Podana nazwa jest już zajęta";
				}
				
				// Sprawdza poporawność wpisanych haseł
				if ($password != $repeat_password) {
					$errors["password"] = true; //"Hasła nie pasują do siebie.";
				}
				
				if (!preg_match('/^[^\W][a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\@[a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\.[a-zA-Z]{2,4}$/', $email)) {
					$errors["email"] = true; //"Podany adres email jest niepoprawny.";
				}
				
				if (in_array(true, $errors)) {
					$_SESSION["register-try-errors"] = $errors;
					return false;
				}
				
				
				// Generuje sol
				$sol = $this->_generateStr();
				
				// Generuje token do aktywacji konta
				$token = $this->_generateStr(15);
				
				// Dodaje użytkownika do bazy
				$query = "INSERT INTO users (username, password, sol, email, token) VALUES ('$username', '" . md5($password . $sol) . "', '$sol', '$email', '$token')";
				$result = $this->_mMysqli->query($query);
				
				if ($result) {
					// Dodaje jeszcze linię do tabeli users_informations
					$user_id = $this->_mMysqli->insert_id;
					$query_info = "INSERT INTO users_informations (user_id) VALUES ('$user_id')";
					$result_info = $this->_mMysqli->query($query_info);
					
					// Wysyła email do aktywacji konta
					// $this->_sendEmailToActiveAccount($user_id, $email, $token);
					
					
					if ($result_info) {
						unset($_SESSION["register-try-username"]);
						unset($_SESSION["register-try-password"]);
						unset($_SESSION["register-try-repeat_password"]);
						unset($_SESSION["register-try-email"]);
						unset($_SESSION["register-try-errors"]);
						
						return true;
					}
					// Jeśli dodawanie linii nie powiodło się usuwa użytkownika z tabeli users
					else {
						$this->_deleteUser($user_id);
						throw new Exception("Wystąpił błąd podczas rejestracji, spróbuj ponownie później.");
					}
				}
				throw new Exception("Wystąpił błąd podczas rejestracji, spróbuj ponownie później.");
			}
			throw new Exception("Musisz wypełnić wszystkie pola.");
		}
		
		private function _sendEmailToActiveAccount($id, $email, $token) {
			global $cfg;
			
			$content = "<a href='{$cfg["path"]}/account/active/$id/$token'>Aby aktywować swoje konto wejdź w ten link</a>";
			
			echo $content;
			echo $id;
			echo $email;
			mail($email, "Apteka internetowa", $content);
		}
		
		public function activeAccount($id, $token) {
			
		}
		
		private function _deleteUser($id) {
			$query = "DELETE FROM users WHERE id = '$id'";
			$result = $this->_mMysqli->query($query);
			
			return;
		}
		
		private function _checkUserName($username) {
			$query = "SELECT id FROM users WHERE username = '$username'";
			$result = $this->_mMysqli->query($query);
			
			if ($result->num_rows > 0) {
				return false;
			}
			return true;
		}
		
		private function _setAllInformations($results) {
			mysqli_data_seek($results, 0);
			
			$info = array();
			
			$row = $results->fetch_array(MYSQLI_ASSOC);
			foreach ($row as $key => $value) {
				$info[$key] = $value;
			}
			
			//$_SESSION["Login-allResults"] = $info;
		}
		
		static public function logOut() {
			$logout = session_destroy();
			
			if ($logout) {
				return true;
			}
			else {
				return false;
			}
		}
		
		static public function checkSession() {
			$result = false;
			
			if (!empty($_SESSION["Login-login"])) {
				if ($_SESSION["Login-ip"] === $_SERVER['REMOTE_ADDR']) {
					if ($_SESSION["Login-logintime"] + Login::$session_length > time()) {
						return true;
					}
					else {
						Login::logOut();
						return false;
					}
				}
				else {
					Login::logOut();
					return false;
				}
			}
			else {
				return false;
			}			
		}
		
		static public function renewLoginTime() {
			if (isset($_SESSION['Login-login'])) {
				$_SESSION['Login-logintime'] = time();
			}
		}
		
		static public function isLogged() {
			return (isset($_SESSION['Login-login']) && $_SESSION['Login-login']);
		}
		
		static public function getUserName() {
			return isset($_SESSION['Login-login']) ? $_SESSION['Login-username'] : false;
		}
		
		static public function getUserId() {
			return isset($_SESSION['Login-login']) ? $_SESSION['Login-iduser'] : false;
		}
		
		static public function getUserType() {
			return isset($_SESSION['Login-login']) ? $_SESSION['Login-usertype'] : false;
		}
		
		static public function getEmail() {
			return isset($_SESSION['Login-login']) ? $_SESSION['Login-email'] : false;
		}
		
		static public function getIp() {
			return isset($_SESSION['Login-login']) ? $_SESSION['Login-ip'] : false;
		}
		
		static public function getLoginTime() {
			return isset($_SESSION['Login-login']) ? $_SESSION['Login-logintime'] : false;
		}
		
		static public function getAllUserInformations() {
			return isset($_SESSION['Login-login']) ? $_SESSION['Login-allResults'] : false;
		}
		
		public function __destruct() {
			$this->_mMysqli->close();
		}
	}
?>
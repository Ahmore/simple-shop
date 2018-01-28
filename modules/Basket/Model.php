<?php
	class Basket_Model {
		public function add($options) {
			global $_POST;
			
			$id = $options["id"];
			$amount = @$_POST["amount"];
			
			if (!isset($_SESSION["basket"])) {
				$_SESSION["basket"] = array();
			}
			
			if (empty($_SESSION["basket"][$id])) {
				if (isset($amount)) {
					$_SESSION["basket"][$id] = $amount;
				}
				else {
					$_SESSION["basket"][$id] = 1;
				}
			}
				
			// Sprawdzenie czy dodawanie powiodło się
			if (empty($_SESSION["basket"][$id])) {
				throw new Exception(1);
			}
			
			return array();
		}
		
		public function remove($options) {
			$id = $options["id"];
			$result = false;
			
			if (isset($_SESSION["basket"][$id])) {
				unset($_SESSION["basket"][$id]);
			}
			
			// Sprzadza czy usunięcie się powiodło
			if (isset($_SESSION["basket"][$id])) {
				throw new Exception();
			}
			return array();
		}
		
		public function info($options) {
			if (!isset($_SESSION["basket"])) {
				$_SESSION["basket"] = array();
			}
			
			$basket = array_keys($_SESSION["basket"]);
			
			$products = DataManager::getProductsByIds($basket);
			
			if (!$products || $products->num_rows == 0) {
				if (!isset($options["demand"])) {
					throw new Exception(2);
				}
			}
			
			return array("products" => $products);
		}
		
		public function update($options) {
			global $_POST;
			
			$id = $options["id"];
			$amount = @(int)($_POST["amount"]);
			
			if (!isset($amount)) {
				return array("result" => false);
			}
			
			if (empty($_SESSION["basket"][$id])) {
				$this->add(array("id" => $id, "amount" => 1));
			}
			
			$_SESSION["basket"][$id] = $amount;
			
			// Jeszcze sprawdzenie poprawności updatu
			if ($_SESSION["basket"][$id] != $amount) {
				throw new Exception(1);
			}
			return array();
		}
		
		public function addressData($options) {
			global $_POST;
			global $cfg;
			
			// Sprawdzanie stanu sesji i praw dostępu
			Session::normal();
			
			// Sprawdza czy ktoś nie pominął żadnego z kroków zakupów
			if (!isset($_POST["verifying"]) || $_POST["verifying"] != "1") {
				throw new Exception();
			}
			
			return array("results" => DataManager::getAllUserInformations(Login::getUserId()));
		}
		
		public function sendForm($options) {
			global $_POST;
			global $cfg;
			
			
			// Sprawdzanie stanu sesji i praw dostępu
			Session::normal();
			
			// Sprawdza czy ktoś nie pominął żadnego z kroków zakupów
			if (!isset($_POST["verifying2"]) || $_POST["verifying2"] != "1") {
				throw new Exception();
			}
			
			
			return array("results" => DataManager::getPaymentSendMethods());
		}
		
		public function finish($options) {
			global $_POST;
			global $cfg;
			
			// Sprawdzanie stanu sesji i praw dostępu
			Session::normal();
			
			// Sprawdza czy ktoś nie pominął żadnego z kroków zakupów
			if (!isset($_POST["verifying3"]) || $_POST["verifying3"] != "1") {
				throw new Exception();
			}
			
			// Zapamiętuje formę płatności/wysyłki wybranąprzez klienta
			if (!isset($_POST["payment_send_method"]) || $_POST["payment_send_method"] == "") {
				throw new Exception();
			}
			
			$_SESSION["payment_send_method"] = $_POST["payment_send_method"];
			$basket = array_keys($_SESSION["basket"]);
			
			
			$payment_send_method = DataManager::getPaymentSendMethods($_SESSION["payment_send_method"]);
			$results = DataManager::getProductsByIds($basket);
			
			if ($results->num_rows == 0 || $payment_send_method->num_rows == 0) {
				throw new Exception(1);
			}
			
			return array("results" => $results, "payment_send_method" => $payment_send_method);
		}
		
		public function order($options) {
			global $_POST;
			global $cfg;
			
			
			// Sprawdzanie stanu sesji i praw dostępu
			Session::normal();
			
			// Sprawdza czy ktoś nie pominął żadnego z kroków zakupów
			if (!isset($_POST["verifying4"]) || $_POST["verifying4"] != "1") {
				throw new Exception();
			}
			
			if (!isset($_SESSION["payment_send_method"]) || $_SESSION["payment_send_method"] == "") {
				throw new Exception();
			}
			
			$basket = $_SESSION["basket"];
			
			if (count($basket) == 0) {
				throw new Exception(1);
			}
			
			$payment_send_method = $_SESSION["payment_send_method"];
			$id = Login::getUserId();
			
			if (DataManager::order($basket, $payment_send_method, $id)) {
				$_SESSION["basket"] = array();
				return array();
			}
			else {
				throw new Exception(1);
			}
		}
	}
?>
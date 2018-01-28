<?php
	class Admin_Model {
	
	
	
		/*
		 *
		 *** Kategorie ***
		 *
		 */
		public function categories($options) {
			// Sprawdzanie stanu sesji i praw dostępu
			Session::high();
			
			$results = DataManager::getCategories();
			
			if ($results->num_rows == 0) {
				throw new Exception(1);
			}
			
			return array("results" => $results);
		}
		
		public function categoryEdit($options) {
			global $_POST;
			
			// Sprawdzanie stanu sesji i praw dostępu
			Session::high();
			
			$id = $options["id"];
			$name = DataManager::getCategories($id)->fetch_array(MYSQLI_ASSOC);
			
			// Edycja
			if (isset($_POST["name"]) && trim($_POST["name"]) != "") {
				try {
					DataManager::editCategory($id, $_POST["name"]);
					return array("results" => true, "name" => "");
				}
				catch (Exception $e) {
					return array("results" => false, "name" => $name["name"], "message" => $e->getMessage());
				}
			}
			// Formularz
			else {
				if (isset($_POST["name"])) {
					return array("results" => false, "name" => $name["name"], "message" => "Musisz podać nazwę kategorii.");
				}
				return array("results" => false, "name" => $name["name"]);
			}
		}
		
		public function categoryAdd($options) {
			global $_POST;
			
			// Sprawdzanie stanu sesji i praw dostępu
			Session::high();
			
			// Dodawanie
			if (isset($_POST["name"]) && trim($_POST["name"]) != "") {
				$_SESSION["categoryAdd-try-name"] = $_POST["name"];
				
				try {
					DataManager::addCategory($_POST["name"]);
					unset($_SESSION["categoryAdd-try-name"]);
					return array("results" => true);
				}
				catch (Exception $e) {
					return array("results" => false, "message" => $e->getMessage());
				}
			}
			// Formularz
			else {
				if (isset($_POST["name"])) {
					return array("results" => false, "message" => "Musisz podać nazwę kategorii.");
				}
				return array("results" => false); 
			}
		}
		
		public function categoryDelete($options) {
			global $_POST;
			
			// Sprawdzanie stanu sesji i praw dostępu
			Session::high();
			
			// Sprawdza czy ktoś nie pominął żadnego z kroków zakupów
			if (!isset($_POST["verifying"]) || $_POST["verifying"] != "1") {
				throw new Exception();
			}
			
			if (DataManager::deleteCategory($options["id"])) {
				return array();
			}
			throw new Exception(1);
		}
		
		
		
		/*
		 *
		 *** Produkty ***
		 *
		 */
		
		public function productAdd($options) {
			global $_POST;
			
			// Sprawdzanie stanu sesji i praw dostępu
			Session::high();
			
			$_SESSION["productAdd-try-name"] = @$_POST["name"];
			$_SESSION["productAdd-try-price"] = @$_POST["price"];
			$_SESSION["productAdd-try-description"] = @$_POST["description"];
			$_SESSION["productAdd-try-category"] = @$_POST["category"];
			
			if (isset($_POST["name"]) && isset($_POST["price"]) && isset($_POST["description"]) && isset($_POST["category"])) {
				try {
					Admin_Model::productValidate($_POST);
					DataManager::addProduct($_POST["name"], $_POST["price"], $_POST["description"], $_POST["category"]);
					
					return array("results" => true, "message" => "");
				}
				catch (Exception $e) {
					return array("results" => false, "message" => $e->getMessage(), "categories" => $categories = DataManager::getCategories());
				}
			}
			else {
				return array("results" => false, "message" => "", "categories" => $categories = DataManager::getCategories());
			}
		}
		
		public function productEdit($options) {
			global $_POST;
			
			// Sprawdzanie stanu sesji i praw dostępu
			Session::high();
			
			$id = $options["id"];
			$product = DataManager::getProductsByIds(array($id));
			
			if (isset($_POST["name"]) && isset($_POST["price"]) && isset($_POST["description"]) && isset($_POST["category"])) {
				try {
					Admin_Model::productValidate($_POST);
					DataManager::editProduct($id, $_POST["name"], $_POST["price"], $_POST["description"], $_POST["category"]);
					
					return array("results" => true, "message" => "");
				}
				catch (Exception $e) {
					return array("results" => false, "message" => $e->getMessage(), "product" => $product, "categories" => $categories = DataManager::getCategories());
				}
			}
			else {
				return array("results" => false, "message" => "", "product" => $product, "categories" => $categories = DataManager::getCategories());
			}
		}
		
		static public function productValidate($post) {
			$name = trim($post["name"]);
			$price = trim($post["price"]);
			
			if (empty($name) || empty($price)) {
				throw new Exception("Musisz uzupełnić wszystkie wymagane pola");
			}
			
			if (!is_numeric($price)) {
				throw new Exception("Podana cena jest nieprawidłowa.");
			}
			return true;
		}
		
		
		
		/*
		 *
		 *** Zamówienia ***
		 *
		 */
		
		public function order($options) {
			global $_POST;
			
			// Sprawdzanie stanu sesji i praw dostępu
			Session::high();
			
			$order_id = @$options["id"];
			$results = DataManager::getOrderById($order_id);
			
			if ($results->num_rows == 0) {
				throw new Exception(1);
			}
			
			return array("results" => $results);
			
		}
		
		public function newOrders($options) {
			global $_POST;
			
			// Sprawdzanie stanu sesji i praw dostępu
			Session::high();
			
			$page = @$options["id"];
			$sort = @$options["sort"];
			
			// Jeśli numer strony jest niepodany wybiera 1
			if (!isset($page)) {
				$page = 1;
			}
			
			$results = DataManager::getOrderPage(0, $page, $sort);
			$ordersAmount = DataManager::getOrdersAmount(0);
			
			if ($results->num_rows == 0 || !$ordersAmount) {
				//throw new Exception(1);
			}
			
			return array("results" => $results, "ordersAmount" => $ordersAmount, "page" => $page, "sort" => $sort);
		}
		
		public function acceptOrder($options) {
			global $_POST;
			
			// Sprawdzanie stanu sesji i praw dostępu
			Session::high();
			
			$id = $options["id"];
			
			if (!isset($id)) {
				throw new Exception();
			}
			
			if (DataManager::acceptOrder($id)) {
				return array();
			}
			throw new Exception();
		}
		
		public function oldOrders($options) {
			global $_POST;
			
			// Sprawdzanie stanu sesji i praw dostępu
			Session::high();
			
			$page = @$options["id"];
			$sort = @$options["sort"];
			
			// Jeśli numer strony jest niepodany wybiera 1
			if (!isset($page)) {
				$page = 1;
			}
			
			$results = DataManager::getOrderPage(1, $page, $sort);
			$ordersAmount = DataManager::getOrdersAmount(1);
			
			if ($results->num_rows == 0 || !$ordersAmount) {
				throw new Exception(1);
			}
			
			return array("results" => $results, "ordersAmount" => $ordersAmount, "page" => $page, "sort" => $sort);
		}
		
		public function ordersStats($options) {
			global $_POST;
			
			// Sprawdzanie stanu sesji i praw dostępu
			Session::high();
			
			return array();
		}
	}
?>
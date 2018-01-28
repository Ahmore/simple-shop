<?php
	class DataManager {
		public static function _getConnection() {
			static $mMysqli;

			if (isset($mMysqli)) {
				return $mMysqli;
			}
		
			try {
				global $cfg;
				
				$mMysqli = new mysqli($cfg["db"]["host"], $cfg["db"]["user"], $cfg["db"]["password"], $cfg["db"]["database"]) or die("Błąd połączenia z bazą danych!");
				$mMysqli->query('SET NAMES utf8');
				$mMysqli->query('SET CHARACTER_SET utf8_unicode_ci');
				
				return $mMysqli;
			}
			catch (Exception $e) {
				echo $e->getMessage();
			}
		}
		
		
		
		/*
		 *
		 *** Produkty ***
		 *
		 */
		public static function getProductsByIds($ids) {
			$mMysqli = DataManager::_getConnection();
			$ids = "(" . join(", ", $ids) . ")";
			
			$query = "SELECT p.id, p.name, p.description, p.price, p.img, c.name as category 
						FROM products AS p LEFT JOIN categories AS c ON p.category = c.id 
						WHERE p.id IN $ids ORDER BY p.id";
			$results = $mMysqli->query($query);
			
			return $results;
		}
		
		public static function getProducts($page, $category, $string) {
			global $cfg;
			
			$mMysqli = DataManager::_getConnection();
			
			$limit = $cfg["data"]["products_per_page"];
			$page = (DataManager::escape($page) - 1) * $limit;
			$string = DataManager::escape($string);
			$category = DataManager::escape($category);
			
			// Jeśli wybrane były wszystkie kategorie
			if ($category === "*") {
				$query_category = "";
			}
			else {
				$query_category = " AND encodeString(c.name) = '$category'";
			}
			
			$query = "SELECT p.id, p.name, p.description, p.price, p.img, c.name as category 
						FROM products AS p LEFT JOIN categories AS c ON p.category = c.id 
						WHERE p.name LIKE '%$string%' $query_category
						LIMIT $page, $limit";
			$results = $mMysqli->query($query);
			
			return $results;
		}
		
		public static function getProductsAmount($category, $string) {
			$mMysqli = DataManager::_getConnection();
			
			// Jeśli wybrane były wszystkie kategorie
			if ($category === "*") {
				$query_category = "";
			}
			else {
				$query_category = " AND encodeString(c.name) = '$category'";
			}
			
			$query = "SELECT COUNT(p.id) as amount 
						FROM products AS p LEFT JOIN categories AS c ON p.category = c.id 
						WHERE p.name LIKE '%$string%' $query_category";
			$result = $mMysqli->query($query)->fetch_array(MYSQLI_ASSOC);
			
			return $result["amount"];
		}
		
		public static function addProduct($name, $price, $description, $category) {
			$mMysqli = DataManager::_getConnection();
			
			$name = DataManager::escape($name);
			$price = DataManager::escape($price);
			$description = DataManager::escape($description);
			$category = DataManager::escape($category);
			
			if (DataManager::checkValueExists("products", array("name" => $name))) {
				throw new Exception("Lek o tej nazwie już istnieje.");
			}
			
			$query = "INSERT INTO products (name, price, description, category) VALUES ('$name', '$price', '$description', '$category')";
			$result = $mMysqli->query($query);
			
			if ($result) {
				return true;
			}
			return false;
		}
		
		public static function editProduct($id, $name, $price, $description) {
			$mMysqli = DataManager::_getConnection();
			
			$name = DataManager::escape($name);
			$price = DataManager::escape($price);
			$description = DataManager::escape($description);
			
			if (DataManager::checkValueExists("products", array("name" => $name), "AND id != '$id'")) {
				throw new Exception("Lek o tej nazwie już istnieje.");
			}
			
			$query = "UPDATE products SET name = '$name', price = '$price', description = '$description' WHERE id = '$id'";
			$result = $mMysqli->query($query);
			
			if ($result) {
				return true;
			}
			return false;
		}
		
		
		
		/*
		 *
		 *** Kategorie ***
		 *
		 */
		public static function getCategories($id = false) {
			$mMysqli = DataManager::_getConnection();
			
			$rest = "";
			if ($id) {
				$rest = "WHERE id = '$id'";
			}
			
			$query = "SELECT id, encodeString(name) as encodedName, name 
						FROM categories $rest 
						ORDER BY name ASC";
			$results = $mMysqli->query($query);
			
			return $results;
		}
		
		public static function editCategory($id, $name) {
			$mMysqli = DataManager::_getConnection();
			
			$name = DataManager::escape($name);
			
			// Sprawdza czy nazwa już nie istnieje
			if (DataManager::checkValueExists("categories", array("name" => $name), "AND id != '$id'")) {
				throw new Exception("Ta kategoria już istnieje.");
			}
			
			$query = "UPDATE categories SET name = '$name' WHERE id = '$id'";
			$result = $mMysqli->query($query);
			
			return $result;
		}
		
		public static function addCategory($name) {
			$mMysqli = DataManager::_getConnection();
			
			$name = DataManager::escape($name);
			
			// Sprawdza czy nazwa już nie istnieje
			if (DataManager::checkValueExists("categories", array("name" => $name))) {
				throw new Exception("Ta kategoria już istnieje.");
			}
			
			$query = "INSERT INTO categories (name) VALUES ('$name')";
			$result = $mMysqli->query($query);
			
			if ($result) {
				return true;
			}
			return false;
		}
		
		public function deleteCategory($id) {
			$mMysqli = DataManager::_getConnection();
			
			$query = "DELETE FROM categories WHERE id = '$id'";
			$result = $mMysqli->query($query);
			
			if ($result) {
				return true;
			}
			return false;
		}
		
		
		/*
		 *
		 *** Użytkownicy ***
		 *
		 */
		public static function getAllUserInformations($id) {
			$mMysqli = DataManager::_getConnection();
			
			$query = "SELECT * FROM users_informations WHERE user_id = '$id'";
			$results = $mMysqli->query($query);
			
			return $results;
		}
		
		public static function saveUserInformations($post, $id) {
			$mMysqli = DataManager::_getConnection();
			
			$fields = DataManager::getFields("users_informations");
			
			$query = "UPDATE users_informations SET ";
			$update = array();
			
			foreach ($post as $field => $value) {
				if ($fields[$field]) {
					$update[] = DataManager::escape($field) . " = '" . DataManager::escape($value) . "'";
				}
			}
			
			$query .= implode(", ", $update);
			$query .= " WHERE user_id = '$id'";
			
			$results = $mMysqli->query($query);
			
			return $results;
		}
		
		
		
		/*
		 *
		 *** Zamówienia ***
		 *
		 */
		public static function getPaymentSendMethods($id = false) {
			$mMysqli = DataManager::_getConnection();
			
			$rest = "";
			if ($id) {
				$rest = "WHERE id = '$id'";
			}
			
			$query = "SELECT * FROM payment_send_methods $rest";
			$results = $mMysqli->query($query);
			
			return $results;
		}
		
		public static function getPaymentSendMethodValue($id) {
			$row = DataManager::getPaymentSendMethods($id)->fetch_array(MYSQLI_ASSOC);
			return $row["price"];
		}
		
		public static function getPaymentSendMethodName($id) {
			$row = DataManager::getPaymentSendMethods($id)->fetch_array(MYSQLI_ASSOC);
			return $row["name"];
		}
		
		public static function order($basket, $payment_send_method, $id) {
			$mMysqli = DataManager::_getConnection();
			
			$payment_send_method = DataManager::escape($payment_send_method);
			
			// Sprawdza poprawność i istnienie płatności
			if (!DataManager::checkValueExists("payment_send_methods", array("id" => $payment_send_method))) {
				return false;
			}
			
			// Tworzy zamówienie w tabeli orders
			$query_order = "INSERT INTO orders (user_id, date, payment_send_method) VALUES ('$id', NOW(), '$payment_send_method')";
			$result_order = $mMysqli->query($query_order);
			
			// Jeśli dodawanie zamówienia się nie powiodło
			if (!$result_order) {
				return false;
			}
			
			$order_id = $mMysqli->insert_id;
			
			// Podłącza do zamówienia porodukty w tabeli orders_products
			$query_products = "INSERT INTO orders_products (product_id, order_id, amount, price) VALUES ";
			
			$values = array();
			
			foreach ($basket as $product_id => $amount) {
				// Pobiera cene aktualną cenę produktu
				$results = DataManager::getProductsByIds(array($product_id))->fetch_array(MYSQLI_ASSOC);
				$price = $results["price"];
				
				$values[] = "('$product_id', '$order_id', '$amount', '$price')"; 
			}
			
			$values = implode(", ", $values);
			$query_products .= $values;
			$result_products = $mMysqli->query($query_products);
			
			if ($result_products) {
				return true;
			}
			// Jeśli dodawanie leków nie powiodło się usuwa zamówienie z tabeli orders
			else {
				DataManager::deleteOrder($order_id);
				return false;
			}
		}
		
		public static function getUsersOrders($user_id) {
			return DataManager::getOrders($user_id);
		}
		
		public static function getOrderPage($state, $page, $sort = false) {
			return DataManager::getOrders(false, $state, $page, $sort);
		}
		
		public static function getOrders($user_id = false, $state = false, $page = false, $sort = false) {
			global $cfg;
			
			$mMysqli = DataManager::_getConnection();
			
			$user_id = DataManager::escape($user_id);
			$page = DataManager::escape($page);
			$sort = DataManager::escape($sort);
			
			$rest = "";
			if ($user_id) {
				$rest .= " AND o.user_id = '$user_id'";
			}
			if ($state !== false) {
				$rest .= " AND o.state = '$state'";
			}
			if ($page) {
				$limit = $cfg["data"]["orders_per_page"];
				$page = ($page - 1) * $limit;
				
				$rest .= " LIMIT $page, $limit";
			}
			if ($sort) {
				//
			}
			
			
			$query = "SELECT o.id as order_id, psm.name as payment_send_method, psm.price as payment_send_method_price, o.date, o.state, o.user_id
						FROM orders as o, payment_send_methods as psm
						WHERE o.payment_send_method = psm.id $rest";
			
			$results = $mMysqli->query($query);
			return $results;
		}
		
		public static function getOrderById($order_id, $user_id = false) {
			$mMysqli = DataManager::_getConnection();
			
			$rest = "";
			if ($user_id) {
				$rest = "AND o.user_id = '$user_id'";
			}
			
			$query = "SELECT p.name, op.amount, op.price, psm.name as payment_send_method, psm.price as payment_send_method_price
						FROM orders as o, orders_products as op, products as p, payment_send_methods as psm 
						WHERE o.id = op.order_id AND op.product_id = p.id AND o.payment_send_method = psm.id AND op.order_id = '$order_id' $rest";
			$results = $mMysqli->query($query);
			
			return $results;
		}
		
		public static function getOrderValue($id) {
			$mMysqli = DataManager::_getConnection();
			
			$query = "SELECT amount, price FROM orders as o, orders_products as op WHERE o.id = op.order_id AND o.id = '$id'";
			$result = $mMysqli->query($query);
			
			$value = 0;
			
			while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
				$amount = $row["amount"];
				$price = $row["price"];
				
				$value += $amount * $price;
			}
			
			return $value;
		}
		
		public function acceptOrder($id) {
			$mMysqli = DataManager::_getConnection();
			
			$query = "UPDATE orders SET state = '1' WHERE id = '$id'";
			$result = $mMysqli->query($query);
			
			if ($result) {
				return true;
			}
			return false;
		}
		
		public static function getOrdersAmount($state = false) {
			$mMysqli = DataManager::_getConnection();
			
			$rest = "";
			if ($state !== false) {
				$rest = "WHERE state = '$state'";
			}
			
			$query = "SELECT COUNT(id) as amount FROM orders $rest";
			$result = $mMysqli->query($query);
			$row = $result->fetch_array(MYSQLI_ASSOC);
			
			return $row["amount"];
		}
		
		public static function deleteOrder($id) {
			$mMysqli = DataManager::_getConnection();
			
			$query = "DELETE FROM orders WHERE id = '$id'";
			$result = $mMysqli->query($query);
			
			return $result;
		}
		
		
		
		/*
		 *
		 *** Pozostałe ***
		 *
		 */
		public static function checkValueExists($table, $conditionals, $other = "") {
			$mMysqli = DataManager::_getConnection();
			
			$conditional = array();
			foreach ($conditionals as $field => $value) {
				$conditional[] = $field . " = '" . $value . "'";
			}
			$conditional = implode(" AND ", $conditional);
			
			$query = "SELECT * FROM $table WHERE $conditional $other";
			$result = $mMysqli->query($query);
			
			if ($result->num_rows > 0) {
				return true;
			}
			return false;
		}
		
		public static function getFields($table) {
			$mMysqli = DataManager::_getConnection();
			
			$table = DataManager::escape($table);
			
			$query = "SHOW COLUMNS FROM $table";
			$results = $mMysqli->query($query);
			
			$fields = array();
			
			while ($row = $results->fetch_array(MYSQLI_ASSOC)) {
				$fields[$row["Field"]] = $row["Field"];
			}
			
			return $fields;
		}
		
		public static function escape($string) {
			$mMysqli = DataManager::_getConnection();
			$string = $mMysqli->real_escape_string($string);
			$string = trim($string);
			
			return $string;
		}
	}
?>
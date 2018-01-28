<?php
	class Products_Model {
		public function search($options){
			// Pobiera parametry wyszukiwania
			$page = @$options["page"];
			$category = urldecode(@$options["category"]);
			$string = urldecode(@$options["string"]);
			
			if (!isset($page)) {
				$page = 1;
			}
			
			if (!isset($category) || $category == "") {
				$category = "*";
			}
			
			// Zapisue w zmiennej sesyjnej
			$_SESSION["page"] = $page;
			$_SESSION["category"] = $category;
			$_SESSION["string"] = $string;
			
			$products = DataManager::getProducts($page, $category, $string);
			$productsAmount = DataManager::getProductsAmount($category, $string);
			
			if ($products->num_rows == 0 || !$productsAmount)  {
				throw new Exception(1);
			}
			
			return array("products" => $products, "productsAmount" => $productsAmount, "page" => $page, "category" => $category);
		}
		
		public function info($options) {
			$id = $options["id"];
			
			$product = DataManager::getProductsByIds(array("id" => $id));
			
			if ($product->num_rows == 0)  {
				throw new Exception(3);
			}
			
			return array("product" => $product);
		}
	}
?>
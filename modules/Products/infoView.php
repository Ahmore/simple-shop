<?php
	class Products_infoView {
		public function render($data) {	
			global $cfg;
			
			$product = $data["product"];
			$category_encoded = @$_SESSION["category"];
			$page = @$_SESSION["page"];
			$string = @$_SESSION["string"];
			$html = "";
			
			
			$product = $product->fetch_array(MYSQLI_ASSOC);
				
			$id = htmlspecialchars($product["id"]);
			$name = htmlspecialchars($product["name"]);
			$price = htmlspecialchars($product["price"]);
			$description = htmlspecialchars($product["description"]);
			$img = htmlspecialchars($product["img"]);
			$category = htmlspecialchars($product["category"]);
			
			if ($category != "") {
				$html .= "<div id='product-info-category'>Wyszukiwanie -> <a href='{$cfg["path"]}/products/search/1/$category_encoded/' class='link'>$category</a></div>";
			}
			
			$html .= "<div id='product-info-name' class='page-name'>$name</div>";
			$html .= "<div id='product-info-img'><img></div>";
			$html .= "<div id='product-info-buyLine'>";
				$html .= "<div id='product-info-price'>Cena: <span>$price</span> zł</div>";
				$html .= "<form method='post' action='{$cfg["path"]}/basket/update/$id' enctype='multipart/form-data'>";
					$html .= "<input type='text' value='1' name='amount' class='input-text' />";
					$html .= "<input type='submit' value='Dodaj do koszyka' class='btn-submit' />";
				$html .= "</form>";
			$html .= "</div>";
			
			$html .= "<div id='product-info-description'>$description</div>";
			
			
			if (isset($_SESSION["page"])) {
				$html .= "<a href='{$cfg["path"]}/products/search/$page/$category_encoded/$string' class='btn-return link'>Powrót do wyszukiwania</a>";
			}
			
			// Jeśli to admin dodaje link do edycji leku
			if (Login::getUserType() == 1) {
				$html .= "<a href='{$cfg["path"]}/admin/productEdit/$id' class='link' id='btn-productEdit'>Edytuj</a>";
			}
			
			return $html;
		}
	}
?>
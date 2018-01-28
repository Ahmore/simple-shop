<?php
	class Products_searchView {
		private $_data;
		
		public function render($data) {
			global $cfg;
			
			$this->_data = $data;
			
			$products = $data["products"];
			$page = $data["page"];
			
			$i = $page * $cfg["data"]["products_per_page"];
			$limit = $cfg["data"]["products_per_page"];
			$i = 1 + ($limit * ($page - 1));
			
			$productsAmount = $data["productsAmount"];
			$html = "<h1 id='page-name'>Wyszukiwanie</h1>";
			
			// Jeśli nie ma żadnych wyników
			if ($products->num_rows == 0) {
				return "<div class='info'>Brak produktów spełniających podane kryteria.</div>";
			}
			
			while ($row = $products->fetch_array(MYSQLI_ASSOC)) {
				$id = htmlspecialchars($row["id"]);
				$name = htmlspecialchars($row["name"]);
				$description = htmlspecialchars($row["description"]);
				$price = htmlspecialchars($row["price"]);
				$img = htmlspecialchars($row["img"]);
				
				$html .= "<div class='search-product-position'>";
					$html .= "<div class='search-product-lp'>$i.</div>";
					$html .= "<div class='search-product-img'><img></div>";
					$html .= "<div class='search-product-name'><a href='{$cfg["path"]}/products/info/$id' class='link'>$name</a></div>";
					$html .= "<div class='search-product-price'>Cena: <span>$price</span> zł</div>";
					$html .= "<div class='search-product-description'>$description</div>";
					$html .= "<a href='{$cfg["path"]}/basket/add/$id' class='link search-product-add'>Dodaj do koszyka</a>";
				$html .= "</div>";
				
				$i += 1;
			}
			
			$html .= $this->_makeNavigation();
			$html .= "<script type='text/javascript' src='{$cfg["path"]}/js/searchChangePage.js'></script>";
			
			return $html;
		}
		
		private function _makeNavigation() {
			global $cfg;
			
			$html = "";
			$limit = $cfg["data"]["products_per_page"];
			$products = $this->_data["products"];
			$productsAmount = $this->_data["productsAmount"];
			$page = $this->_data["page"];
			$category = $this->_data["category"];
			$string = @$this->_data["string"];
			
			$pages = ceil($productsAmount / $limit);
			
			// Jeśli jest wyświetlana 1 strona nie umożliwia zmiany w dół
			if ($page > 1) {
				$html .= "<a href='{$cfg["path"]}/products/search/" . ($page - 1) . "/$category/$string' class='link'>Prev</a>";
			}
			
			$html .= "<form action='' method='post' enctype='multipart/form-data' id='products-search-pageChange-form'>";
				$html .= "<input type='hidden' name='products-search-category' value='$category'/>";
				$html .= "<input type='hidden' name='products-search-string' value='$string'/>";
				$html .= "<input type='text' name='products-search-page' value='$page'/>";
			$html .= "</form>";
			$html .= "<div>z</div>";
			$html .= "<a href='{$cfg["path"]}/products/search/$pages/$category/$string' class='link'>$pages</a>";
			
			// Jeśli jest wyświetlana ostatnia strona nie umożliwia zmiany w górę
			if ($page < $pages) {
				$html .= "<a href='{$cfg["path"]}/products/search/" . ($page + 1) . "/$category/$string' class='link'>Next</a>";
			}
			
			return "<div id='products-search-navigation'><div>" . $html . "</div></div>";
		}
	}
?>
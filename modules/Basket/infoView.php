<?php
	class Basket_infoView {
		public function render($data) {
			global $cfg;
			
			$products = $data["products"];
			$totalValue = 0;
			$html = "<h1 id='page-name'>Twój koszyk</h1>";
			$i = 1;
			
			$html .= "<script type='text/javascript' src='{$cfg["path"]}/js/basket.js'></script>";
			$html .= "<table id='basket-products-table' border=1>";
				// Nagłówki
				$html .= "<thead>";
					$html .= "<tr>";
						$html .= "<th class='basket-products-lp'>Lp.</th>";
						$html .= "<th class='basket-products-name'>Nazwa</th>";
						$html .= "<th class='basket-products-price'>Cena</th>";
						$html .= "<th class='basket-products-amount'>Liczba</th>";
						$html .= "<th class='basket-products-value'>Wartość</th>";
						$html .= "<th class='basket-products-delete'>Skasuj</th>";
					$html .= "</tr>";
				$html .= "</thead>";
				
				$html .= "<tbody>";
				while ($row = $products->fetch_array(MYSQLI_ASSOC)) {
					$id = htmlspecialchars($row["id"]);
					$name = htmlspecialchars($row["name"]);
					$price = htmlspecialchars($row["price"]);
					$description = htmlspecialchars($row["description"]);
					$img = htmlspecialchars($row["img"]);
					$amount = htmlspecialchars($_SESSION["basket"][$id]);
					$value = $price*$amount;
					
					$html .= "<tr>";
						$html .= "<td class='basket-products-lp'>$i.</td>";
						$html .= "<td class='basket-products-name'><a href='{$cfg["path"]}/products/info/$id' class='link'>$name</a></td>";
						$html .= "<td class='basket-products-price'>$price zł</td>";
						$html .= "<td class='basket-products-amount'><form method='post' action='{$cfg["path"]}/basket/update/$id' enctype='multipart/form-data'><input type='text' value='$amount' name='amount'/></form></td>";
						$html .= "<td class='basket-products-value'>$value zł</td>";
						$html .= "<td class='basket-products-delete'><a href='{$cfg["path"]}/basket/remove/$id' class='link'>Usuń z koszyka</a></td>";
					$html .= "</tr>";
					
					$totalValue += $value;
					$i += 1;
				}
				$html .= "</tbody>";
				
				$html .= "<tfoot>";
					$html .= "<tr>";
						$html .= "<td colspan='6' class='basket-totalValue'>Razem: $totalValue zł</td>";
					$html .= "</tr>";
				$html .= "</tfoot>";
			$html .= "</table>";
			
			$html .= "<form method='post' action='{$cfg["path"]}/basket/addressData' enctype='multipart/form-data'>";
				$html .= "<input type='hidden' name='verifying' value='1' />";
				$html .= "<input type='submit' value='Dalej' class='btn-submit right btn-submit-basket' />";
			$html .= "</form>";
			
			return $html; //"<div id='content-column-right'>" . $html . "</div>";
		}
	}
?>

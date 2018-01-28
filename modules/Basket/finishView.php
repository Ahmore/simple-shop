<?php
	class Basket_finishView {
		public function render($data) {
			global $cfg;
			
			$results = $data["results"];
			$basket = $_SESSION["basket"];
			$payment_send_method = $data["payment_send_method"];
			
			$html = "<div id='page-name'>Podsumowanie</div>";
			$totalValue = 0;
			$i = 1;
			
			$html .= "<table id='basket-products-table' border=1>";
			// Nagłówki
				$html .= "<thead>";
					$html .= "<tr>";
						$html .= "<th class='basket-products-lp'>Lp.</th>";
						$html .= "<th class='basket-products-name'>Nazwa</th>";
						$html .= "<th class='basket-products-price'>Cena</th>";
						$html .= "<th class='basket-products-amount'>Liczba</th>";
						$html .= "<th class='basket-products-value'>Wartość</th>";
				$html .= "</tr>";
				$html .= "</thead>";
				
				$html .= "<tbody>";
				while ($row = $results->fetch_array(MYSQLI_ASSOC)) {
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
						$html .= "<td class='basket-products-amount'>$amount</td>";
						$html .= "<td class='basket-products-value'>$value zł</td>";
					$html .= "</tr>";
					
					$totalValue += $value;
					$i += 1;
				}
				
					// Dodaje koszty wysyłki
					$payment_send_method = $payment_send_method->fetch_array(MYSQLI_ASSOC);
					$name = $payment_send_method["name"];
					$price = $payment_send_method["price"];
					
					$html .= "<tr>";
						$html .= "<td class='basket-products-lp'>$i.</td>";
						$html .= "<td class='basket-products-name'>$name</td>";
						$html .= "<td class='basket-products-price'>$price zł</td>";
						$html .= "<td class='basket-products-amount'>1</td>";
						$html .= "<td class='basket-products-value'>$price zł</td>";
					$html .= "</tr>";
					
					$totalValue += $price;
				
				
				$html .= "</tbody>";
				
				$html .= "<tfoot>";
					$html .= "<tr>";
						$html .= "<td colspan='6' class='basket-totalValue'>Razem: $totalValue zł</td>";
					$html .= "</tr>";
				$html .= "</tfoot>";
			$html .= "</table>";
			
			
			
			$html .= "<form method='post' action='{$cfg["path"]}/basket/order' enctype='multipart/form-data'>";
				$html .= "<input type='hidden' name='verifying4' value='1' />";
				$html .= "<input type='submit' value='Zamów' class='btn-submit right btn-submit-basket' />";
			$html .= "</form>";
			
			return $html; //"<div id='content-column-right'>" . $html . "</div>";
		}
	}
?>

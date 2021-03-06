<?php
	class Admin_orderView {
		public function render($data) {
			global $cfg;
			
			// Pobiera menu
			ob_start();
				include('inc/adminMenu.inc.php');
				$menu = ob_get_contents();
			ob_end_clean();
			
			$results = $data["results"];
			
			$html = "";
			$i = 1;
			$totalValue = 0;
			
			$html .= "<table id='account-order-table'>";
				$html .= "<thead>";
					$html .= "<tr>";
						$html .= "<td>Lp.</td>";
						$html .= "<td>Produkt</td>";
						$html .= "<td>Cena</td>";
						$html .= "<td>Liczba</td>";
						$html .= "<td>Wartość</td>";
					$html .= "</tr>";
				$html .= "</thead>";
				
				$html .= "<tbody>";
				while ($row = $results->fetch_array(MYSQLI_ASSOC)) {
					$name = htmlspecialchars($row["name"]);
					$price = htmlspecialchars($row["price"]);
					$amount = htmlspecialchars($row["amount"]);
					$payment_send_method = htmlspecialchars($row["payment_send_method"]);
					$payment_send_method_price = htmlspecialchars($row["payment_send_method_price"]);
					$value = $price * $amount;
					
					$html .= "<tr>";
						$html .= "<td class='account-order-lp'>$i.</td>";
						$html .= "<td class='account-order-name'>$name</td>";
						$html .= "<td class='account-order-price'>$price zł</td>";
						$html .= "<td class='account-order-amount'>$amount</td>";
						$html .= "<td class='account-order-value'>$value zł</td>";
					$html .= "</tr>";
						
					$totalValue += $value;
					$i += 1;
				}
					
					// Dodaje koszty wysyłki
					$html .= "<tr>";
						$html .= "<td class='account-order-lp'>$i.</td>";
						$html .= "<td class='account-order-name'>$payment_send_method</td>";
						$html .= "<td class='account-order-price'>$payment_send_method_price zł</td>";
						$html .= "<td class='account-order-amount'>1</td>";
						$html .= "<td class='account-order-value'>$payment_send_method_price zł</td>";
					$html .= "</tr>";
					$totalValue += $payment_send_method_price;
				
					
				$html .= "</tbody>";
				
				$html .= "<tfoot>";
					$html .= "<tr>";
						$html .= "<td colspan='6' class='basket-totalValue'>Razem: $totalValue zł</td>";
					$html .= "</tr>";
				$html .= "</tfoot>";
			$html .= "</table>";
			
			// Wrap w prawą kolumnę
			$html = "<div id='content-column-right'>" . $html . "</div>";
			
			// Wrap w lewą kolumną - menu
			$html .= "<div id='content-column-left'>" . $menu . "</div>";
			
			return $html;
		}
	}
?>

<?php
	class Account_ordersView {
		public function render($data) {
			global $cfg;
			
			// Pobiera menu
			ob_start();
				include('inc/userMenu.inc.php');
				$menu = ob_get_contents();
			ob_end_clean();
			
			$results = $data["results"];
			$i = 1;
			
			$html = "<table id='account-order-table'>";
				$html .= "<thead>";
					$html .= "<tr>";
						$html .= "<td>Lp.</td>";
						$html .= "<td>Data</td>";
						$html .= "<td>Metoda płatności</td>";
						$html .= "<td>Stan zamówienia</td>";
					$html .= "</tr>";
				$html .= "</thead>";
				
				$html .= "<tbody>";
				while ($row = $results->fetch_array(MYSQLI_ASSOC)) {
					// $user_id = htmlspecialchars($row["user_id"]);
					$order_id = htmlspecialchars($row["order_id"]);
					$date = htmlspecialchars($row["date"]);
					$payment_send_method = htmlspecialchars($row["payment_send_method"]);
					$state = htmlspecialchars($row["state"]);
					
					$html .= "<tr>";
						$html .= "<td>$i.</td>";
						$html .= "<td><a href='{$cfg["path"]}/account/order/$order_id' class='link'>$date</a></td>";
						$html .= "<td>$payment_send_method</td>";
						$html .= "<td>{$cfg["orderStates"][$state]}</td>";
					$html .= "</tr>";
					
					$i += 1;
				}
				$html .= "</tbody>";
			$html .= "</table>";
			
			// Wrap w prawą kolumnę
			$html = "<div id='content-column-right'>" . $html . "</div>";
			
			// Wrap w lewą kolumną - menu
			$html .= "<div id='content-column-left'>" . $menu . "</div>";
			
			return $html;
		}
	}
?>

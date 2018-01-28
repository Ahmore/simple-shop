<?php
	class Admin_oldOrdersView {
		public function render($data) {
			global $cfg;
			
			// Pobiera menu
			ob_start();
				include('inc/adminMenu.inc.php');
				$menu = ob_get_contents();
			ob_end_clean();
			
			$html = "";
			$results = $data["results"];
			$page = $data["page"];
			$sort = $data["sort"];
			$ordersAmount = $data["ordersAmount"];
			
			$limit = $cfg["data"]["orders_per_page"];
			$i = 1 + ($limit * ($page - 1));
			if ($results->num_rows == 0 || !$ordersAmount) {
				$html = "<div class='info'>Aktualnie nie ma starych zamówień.</div>";
			}
			else {
				$html .= "<table id='admin-newOrders-table'>";
					$html .= "<thead>";
						$html .= "<tr>";
							$html .= "<td>Lp.</td>";
							$html .= "<td>Data</td>";
							$html .= "<td>Wartość</td>";
							$html .= "<td>Stan</td>";
						$html .= "</tr>";
					$html .= "</thead>";
					
					$html .= "<tbody>";
						while ($row = $results->fetch_array(MYSQLI_ASSOC)) {
							$order_id = htmlspecialchars($row["order_id"]);
							$user_id = htmlspecialchars($row["user_id"]);
							$date = htmlspecialchars($row["date"]);
							$payment_send_method = htmlspecialchars($row["payment_send_method"]);
							$payment_send_method_price = htmlspecialchars($row["payment_send_method_price"]);
							$state = htmlspecialchars($row["state"]);
							$value = DataManager::getOrderValue($order_id);
							
							$html .= "<tr>";
								$html .= "<td class='admin-newOrder-lp'>$i.</td>";
								$html .= "<td class='admin-newOrder-date'><a href='{$cfg["path"]}/admin/order/$order_id' class='link'>$date</a></td>";
								$html .= "<td class='admin-newOrder-value'>" . ($value + $payment_send_method_price) . " zł</td>";
								$html .= "<td class='admin-newOrder-state'>{$cfg["orderStates"][$state]}</td>";
							$html .= "</tr>";
							
							$i += 1;
						}
					
					$html .= "</tbody>";
				$html .= "</table>";
				
				$html .= $this->_makeNavigation($ordersAmount, $page, $sort);
				$html .= "<script type='text/javascript' src='{$cfg["path"]}/js/ordersChangePage.js'></script>";
			}
			
			// Wrap w prawą kolumnę
			$html = "<div id='content-column-right'>" . $html . "</div>";
			
			// Wrap w lewą kolumną - menu
			$html .= "<div id='content-column-left'>" . $menu . "</div>";
			
			return $html;
		}
		
		private function _makeNavigation($ordersAmount, $page, $sort) {
			global $cfg;
			
			$html = "";
			$limit = $cfg["data"]["orders_per_page"];
			$pages = ceil($ordersAmount / $limit);
			
			// Jeśli jest wyświetlana 1 strona nie umożliwia zmiany w dół
			if ($page > 1) {
				$html .= "<a href='{$cfg["path"]}/admin/newOrders/" . ($page - 1) . "/$sort' class='link'>Prev</a>";
			}
			
			$html .= "<form action='' method='post' enctype='multipart/form-data' id='admin-newOrders-pageChange-form'>";
				$html .= "<input type='hidden' name='admin-newOrders-sort' value='$sort'/>";
				$html .= "<input type='text' name='admin-newOrders-page' value='$page'/>";
			$html .= "</form>";
			$html .= "<div>z</div>";
			$html .= "<a href='{$cfg["path"]}/admin/newOrders/$pages/$sort' class='link'>$pages</a>";
			
			// Jeśli jest wyświetlana ostatnia strona nie umożliwia zmiany w górę
			if ($page < $pages) {
				$html .= "<a href='{$cfg["path"]}/admin/newOrders/" . ($page + 1) . "/$sort' class='link'>Next</a>";
			}
			
			return "<div id='products-search-navigation'><div>" . $html . "</div></div>";
		}
	}
?>

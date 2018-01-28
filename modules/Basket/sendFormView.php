<?php
	class Basket_sendFormView {
		public function render($data) {
			global $cfg;
			
			$results = $data["results"];
			
			$html = "<div id='page-name'>Wybierz formę płatności</div>";
			$html .= "<form method='post' action='{$cfg["path"]}/basket/finish' enctype='multipart/form-data' id='basket-send-form' >";
				while ($row = $results->fetch_array(MYSQLI_ASSOC)) {
					$id = $row["id"];
					$name = htmlspecialchars($row["name"]);
					$price = htmlspecialchars($row["price"]);
					
					$html .= "<input type='radio' name='payment_send_method' value='$id' checked />$name, cena: $price zł<br/>";
				}
				$html .= "<input type='hidden' name='verifying3' value='1' />";
				$html .= "<input type='submit' value='Dalej' class='btn-submit right btn-submit-basket' />";
			$html .= "</form>";
			
			return $html;
		}
	}
?>

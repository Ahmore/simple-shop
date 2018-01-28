<?php
	class Account_registerView {
		public function render($data) {
			global $cfg;
			
			$results = $data["results"];
			$message = $data["message"];
			$html = "";
			
			// Pomyślna rejestracja
			if ($results) {
				$html .= "<div class='info'>Rejestracja powiodła się. <a href='{$cfg["path"]}/account/login' class='link'>Zaloguj się</a>.</div>";
			}
			else {
				// Pobiera formularz rejestracji
				ob_start();
					include('inc/registerForm.inc.php');
					$html .= ob_get_contents();
				ob_end_clean();
				
				if ($message != "") {
					$html .= "<div class='info'>$message</div>";
				}
			}
			
			return $html;
		}
	}
?>

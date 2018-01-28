<?php
	class Account_loginView {
		public function render($data) {
			global $cfg;
			
			$result = $data["result"];
			$html = "";
			
			// Zalogowany
			if ($result) {
				header("location: {$cfg["path"]}/account/info");
			}
			else {
				ob_start();
					include('inc/loginForm.inc.php');
					$html .= ob_get_contents();
				ob_end_clean();
				
				if ($data["message"] != "") {
					$html .= "<div class='info'>" . htmlspecialchars($data["message"]) . "</div>";
				}
			}
			
			return $html;
		}
	}
?>

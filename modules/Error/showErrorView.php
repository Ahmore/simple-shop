<?php
	class Error_ShowErrorView {
		public function render($data) {
			global $cfg;
			
			$html = "";
			$message = $data["message"];
			$header = "Wystąpił błąd";
			
			if (empty($message)) {
				$message = 0;
			}
			
			if (is_numeric($message)) {
				$header = $cfg["errors"][$message][0];
				$message = $cfg["errors"][$message][1];
			}
			
			$html .= "<div class='error'>";
				$html .= "<div class='error-header'>$header</div>";
				$html .= "<div class='error-message'>$message</div>";
				$html .= "<ul><li><a href='{$cfg["path"]}' class='link'>Wróć do strony głównej</a></li></ul>";
			$html .= "</div>";
			
			
			return $html;
		}
	}
?>
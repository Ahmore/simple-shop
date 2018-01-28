<?php
	class Admin_categoryAddView {
		public function render($data) {
			global $cfg;
			
			$results = $data["results"];
			$html = "";
			
			if ($results) {
				$html .= "<div class='info'>Dodawanie kategorii powiodło się.</div>";
			}
			else {
				$html .= "<div id='page-name'>Dodawanie kategorii</div>";
				
				$html .= "<form method='POST' action='{$cfg["path"]}/admin/categoryAdd' enctype='multipart/form-data' id='admin-categoryAdd-form'>";
					$html .= "<label for='admin-categoryAdd-name'>Nazwa: </label>";
					$html .= "<input type='text' name='name' id='admin-categoryAdd-name' class='input-text' value='" . @$_SESSION["categoryAdd-try-name"] . "' required />";
					$html .= "<input type='submit' value='Dodaj' class='btn-submit' />";
				$html .= "</form>";
				
				if (isset($data["message"]) && $data["message"] != "") {
					$html .= "<div class='info'>{$data["message"]}</div>";
				}
			}
			return $html;
		}
	}
?>

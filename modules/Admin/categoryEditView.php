<?php
	class Admin_categoryEditView {
		public function render($data) {
			global $cfg;
			global $_POST;
			
			$results = $data["results"];
			$name = htmlspecialchars($data["name"]);
			$id = $data["id"];
			$html = "";
			
			if ($results) {
				$html .= "<div class='info'>Dane zostały zachowane.</div>";
			}
			else {
				$html .= "<div id='page-name'>Edytowanie kategorii</div>";
				
				$html .= "<form method='POST' action='{$cfg["path"]}/admin/categoryEdit/$id' enctype='multipart/form-data' id='admin-categoryEdit-form'>";
					$html .= "<label for='admin-categoryEdit-name'>Nazwa: </label>";
					$html .= "<input type='text' name='name' id='admin-categoryEdit-name' class='input-text' value='$name' />";
					$html .= "<input type='submit' value='Zapisz' class='btn-submit' />";
				$html .= "</form>";
				
				if (isset($data["message"]) && $data["message"] != "") {
					$html .= "<div class='info'>{$data["message"]}</div>";
				}
			}
			
			return $html;
		}
	}
?>

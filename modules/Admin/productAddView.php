<?php
	class Admin_ProductAddView {
		public function render($data) {
			global $cfg;
			global $_POST;
			
			$html = "";
			$results = $data["results"];
			
			if ($results) {
				$html .= "<div class='info'>Dodawanie leku powiodło się.</div>";
			}
			else {
				$categories = $data["categories"];
					
				$html .= "<div id='page-name'>Dodawanie produktu</div>";
				
				$html .= "<form method='POST' action='{$cfg["path"]}/admin/productAdd' enctype='multipart/form-data' id='admin-productAdd-form'>";
					$html .= "<label for='admin-productAdd-name'>Nazwa* </label>";
					$html .= "<input type='text' name='name' id='admin-productAdd-name' class='input-text' value='" . @$_SESSION["productAdd-try-name"] . "' required />";
					
					$html .= "<label for='admin-productAdd-price'>Cena* </label>";
					$html .= "<input type='text' name='price' id='admin-productAdd-price' class='input-text' value='" . @$_SESSION["productAdd-try-price"] . "' required />";
					
					$html .= "<label for='admin-productAdd-description'>Opis </label>";
					$html .= "<textarea name='description' id='admin-productAdd-description' class='textarea'>" . @$_SESSION["productAdd-try-description"] . "</textarea>";
					
					
					
					$html .= "<label for='admin-productAdd-category'>Kategoria </label>";
					
					
					$html .= "<select name='category'>";
						$html .= "<option value='' selected>Bez kategorii</option>";
						while ($row = $categories->fetch_array(MYSQLI_ASSOC)) {
							$id = $row["id"];
							$name = $row["name"];
							$selected = "";
							
							if ($id == $_SESSION["productAdd-try-category"]) {
								$selected = "selected";
							}
							
							$html .= "<option value='$id' id='admin-productAdd-category' $selected>$name</option>";
						}
					$html .= "</select>";
					

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

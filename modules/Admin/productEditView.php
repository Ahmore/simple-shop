<?php
	class Admin_ProductEditView {
		public function render($data) {
			global $cfg;
			global $_POST;
			
			$html = "";
			$results = $data["results"];
			
			if ($results) {
				$html .= "<div class='info'>Edytowanie leku powiodło się.</div>";
			}
			else {
				$categories = $data["categories"];
				
				$product = $data["product"]->fetch_array(MYSQLI_ASSOC);
				$id = $data["id"];
				$name = htmlspecialchars($product["name"]);
				$price = htmlspecialchars($product["price"]);
				$description = htmlspecialchars($product["description"]);
				$category = htmlspecialchars($product["category"]);
					
				$html .= "<div id='page-name'>Edytowanie produktu</div>";
				
				$html .= "<form method='POST' action='{$cfg["path"]}/admin/productEdit/$id' enctype='multipart/form-data' id='admin-productEdit-form'>";
					$html .= "<label for='admin-productEdit-name'>Nazwa* </label>";
					$html .= "<input type='text' name='name' id='admin-productEdit-name' class='input-text' value='$name' required />";
					
					$html .= "<label for='admin-productEdit-price'>Cena* </label>";
					$html .= "<input type='text' name='price' id='admin-productEdit-price' class='input-text' value='$price' required />";
					
					$html .= "<label for='admin-productEdit-description'>Opis </label>";
					$html .= "<textarea name='description' id='admin-productEdit-description' class='textarea'>$description</textarea>";
					
					
					
					$html .= "<label for='admin-productEdit-category'>Kategoria </label>";
					
					
					$html .= "<select name='category'>";
						$html .= "<option value='' selected>Bez kategorii</option>";
						while ($row = $categories->fetch_array(MYSQLI_ASSOC)) {
							$id = $row["id"];
							$name = $row["name"];
							$selected = "";
							
							if ($id == $category) {
								$selected = "selected";
							}
							
							$html .= "<option value='$id' id='admin-productEdit-category' $selected>$name</option>";
						}
					$html .= "</select>";
					

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

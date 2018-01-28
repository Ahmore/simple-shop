<?php
	class Admin_categoriesView {
		public function render($data) {
			global $cfg;
			
			$results = $data["results"];
			$i = 1;
			$html = "<div id='page-name'>Kategorie</div>";
			
			$html .= "<div id='account-admin-categories'>";
				while ($row = $results->fetch_array(MYSQLI_ASSOC)) {
					$id = $row["id"];
					$encodedName = $row["encodedName"];
					$name = htmlspecialchars($row["name"]);
					
					$html .= "<div class='account-admin-category-line'>";
						$html .= "<div class='account-admin-lp'>$i.</div>";
						$html .= "<div class='account-admin-name'>$name</div>";
						$html .= "<div class='account-admin-edit'><a href='{$cfg["path"]}/admin/categoryEdit/$id' class='link'>Edytuj</a></div>";
						$html .= "<div class='account-admin-delete'>";
							$html .= "<form method='post' action='{$cfg["path"]}/admin/categoryDelete/$id' enctype='multipart/form-data'>";
								$html .= "<input type='hidden' name='verifying' value='1' />";
								$html .= "<input type='submit' value='Usuń' />";
							$html .= "</form>";
						$html .= "</div>";
					$html .= "</div>";
					
					$i += 1;
				}
			$html .= "</div>";
			
			$html .= "<a href='{$cfg["path"]}/admin/categoryAdd' class='link' id='btn-categoryAdd'>Dodaj kategorię</a>";
			
			return $html;
		}
	}
?>

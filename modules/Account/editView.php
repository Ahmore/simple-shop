<?php
	class Account_editView {
		public function render($data) {
			global $cfg;
			
			// Pobiera menu
			ob_start();
				include('inc/userMenu.inc.php');
				$menu = ob_get_contents();
			ob_end_clean();
			
			$results = $data["results"];
			$html = "";
			
			while ($row = $results->fetch_array(MYSQLI_ASSOC)) {
				$name = htmlspecialchars($row["name"]);
				$surname = htmlspecialchars($row["surname"]);
				$street = htmlspecialchars($row["street"]);
				$home_nr = htmlspecialchars($row["home_nr"]);
				$flat_nr = htmlspecialchars($row["flat_nr"]);
				$zip_code = htmlspecialchars($row["zip_code"]);
				$city = htmlspecialchars($row["city"]);
				$country = htmlspecialchars($row["country"]);
				$nip = htmlspecialchars($row["nip"]);
				$regon = htmlspecialchars($row["regon"]);
				$phone_number = htmlspecialchars($row["phone_number"]);
				
				$html .= "<div id='account-edit'>";
					$html .= "<form method='POST' action='{$cfg["path"]}/account/save' enctype='multipart/form-data'>";
						$html .= "<div class='account-edit-name account-edit-line'><div>Imię:</div> <input type='text' value=\"$name\" name='name' class='input-text' /></div>";
						$html .= "<div class='account-edit-surname account-edit-line'><div>Nazwisko:</div> <input type='text' value=\"$surname\" name='surname' class='input-text' /></div>";
						$html .= "<div class='account-edit-street account-edit-line'><div>Ulica:</div> <input type='text' value=\"$street\" name='street' class='input-text' /></div>";
						$html .= "<div class='account-edit-homeNr account-edit-line'><div>Numer domu:</div> <input type='text' value=\"$home_nr\" name='home_nr' class='input-text' /></div>";
						$html .= "<div class='account-edit-flatNr account-edit-line'><div>Numer mieszkania:</div> <input type='text' value=\"$flat_nr\" name='flat_nr' class='input-text' /></div>";
						$html .= "<div class='account-edit-zipCode account-edit-line'><div>Kod pocztowy:</div> <input type='text' value=\"$zip_code\" name='zip_code' class='input-text' /></div>";
						$html .= "<div class='account-edit-city account-edit-line'><div>Miasto:</div> <input type='text' value=\"$city\" name='city' class='input-text' /></div>";
						$html .= "<div class='account-edit-country account-edit-line'><div>Państwo:</div> <input type='text' value=\"$country\" name='country' class='input-text' /></div>";
						$html .= "<div class='account-edit-nip account-edit-line'><div>Nip:</div> <input type='text' value=\"$nip\" name='nip' class='input-text' /></div>";
						$html .= "<div class='account-edit-regon account-edit-line'><div>Regon:</div> <input type='text' value=\"$regon\" name='regon' class='input-text' /></div>";
						$html .= "<div class='account-edit-phone_number account-edit-line'><div>Numer telefonu:</div> <input type='text' value='$phone_number' name='phone_number' class='input-text' /></div>";
						$html .= "<input type='submit' value='Zapisz' class='btn-submit'/>";
					$html .= "</form>";
				$html .= "</div>";
			}
			
			// Wrap w prawą kolumnę
			$html = "<div id='content-column-right'>" . $html . "</div>";
			
			// Wrap w lewą kolumną - menu
			$html .= "<div id='content-column-left'>" . $menu . "</div>";
			
			return $html;
		}
	}
?>

<?php
	class Account_infoView {
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
				
				$html .= "<div id='account-info'>";
					$html .= "<div class='account-info-name account-info-line'><div>Imię:</div> $name</div>";
					$html .= "<div class='account-info-surname account-info-line'><div>Nazwisko:</div> $surname</div>";
					$html .= "<div class='account-info-street account-info-line'><div>Ulica:</div> $street</div>";
					$html .= "<div class='account-info-homeNr account-info-line'><div>Numer domu:</div> $home_nr</div>";
					$html .= "<div class='account-info-flatNr account-info-line'><div>Numer mieszkania:</div> $flat_nr</div>";
					$html .= "<div class='account-info-zipCode account-info-line'><div>Kod pocztowy:</div> $zip_code</div>";
					$html .= "<div class='account-info-city account-info-line'><div>Miasto:</div> $city</div>";
					$html .= "<div class='account-info-country account-info-line'><div>Państwo:</div> $country</div>";
					$html .= "<div class='account-info-nip account-info-line'><div>Nip:</div> $nip</div>";
					$html .= "<div class='account-info-regon account-info-line'><div>Regon:</div> $regon</div>";
					$html .= "<div class='account-info-phone_number account-info-line'><div>Numer telefonu:</div> $phone_number</div>";
				
					$html .= "<a href='{$cfg["path"]}/account/edit' class='link btn-acount-edit'>Edytuj</a>";
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

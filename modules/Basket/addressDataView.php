<?php
	class Basket_addressDataView {
		public function render($data) {
			global $cfg;
			
			require_once "modules/Account/Model.php";
			require_once "/modules/Account/infoView.php";
			
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
				
				$html .= "<div id='basket-info'>";
					$html .= "<div class='basket-info-name basket-info-line'><div>Imię:</div> $name</div>";
					$html .= "<div class='basket-info-surname basket-info-line'><div>Nazwisko:</div> $surname</div>";
					$html .= "<div class='basket-info-street basket-info-line'><div>Ulica:</div> $street</div>";
					$html .= "<div class='basket-info-homeNr basket-info-line'><div>Numer domu:</div> $home_nr</div>";
					$html .= "<div class='basket-info-flatNr basket-info-line'><div>Numer mieszkania:</div> $flat_nr</div>";
					$html .= "<div class='basket-info-zipCode basket-info-line'><div>Kod pocztowy:</div> $zip_code</div>";
					$html .= "<div class='basket-info-city basket-info-line'><div>Miasto:</div> $city</div>";
					$html .= "<div class='basket-info-country basket-info-line'><div>Państwo:</div> $country</div>";
					$html .= "<div class='basket-info-nip basket-info-line'><div>Nip:</div> $nip</div>";
					$html .= "<div class='basket-info-regon basket-info-line'><div>Regon:</div> $regon</div>";
					$html .= "<div class='basket-info-phone_number basket-info-line'><div>Numer telefonu:</div> $phone_number</div>";
				
					$html .= "<a href='{$cfg["path"]}/account/edit' class='link btn-acount-edit'>Edytuj</a>";
				$html .= "</div>";
			}
				
				$html .= "<form method='post' action='{$cfg["path"]}/basket/sendForm' enctype='multipart/form-data'>";
					$html .= "<input type='hidden' name='verifying2' value='1' />";
					$html .= "<input type='submit' value='Dalej' class='btn-submit right btn-submit-basket' />";
				$html .= "</form>";
				
				// $html = "<div id='content-column-right'>" . $html . "</div>";
			
			return $html;
		}
	}
?>

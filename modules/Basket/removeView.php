<?php
	class Basket_removeView {
		public function render($data) {
			global $cfg;
			
			$html = "<div class='info'>Produkt został pomyślnie usunięty z koszyka.</div>";
			$html .= "<script type='text/javascript' src='{$cfg["path"]}/js/requestReturn.js'></script>";
			
			return $html; //"<div id='content-column-right'>" . $html . "</div>";
		}
	}
?>

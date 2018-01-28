<?php
	class Basket_updateView {
		public function render($data) {
			global $cfg;
			
			$html = "<div class='info'>Dane zostały zachowane.</div>";
			$html .= "<script type='text/javascript' src='{$cfg["path"]}/js/requestReturn.js'></script>";
			return $html; //"<div id='content-column-right'>" . $html . "</div>";
		}
	}
?>

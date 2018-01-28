<?php
	class Account_saveView {
		public function render($data) {
			global $cfg;
			
			$html = "<div class='info'>Dane zostały zachowane.</div>";
			$html .= "<script type='text/javascript' src='{$cfg["path"]}/js/accountReturn.js'></script>";
			
			return $html;
		}
	}
?>

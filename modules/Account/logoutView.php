<?php
	class Account_logoutView {
		public function render($data) {
			global $cfg;
			
			$html = "<div class='info'>Wylogowywanie..</div>";
			$html .= "<script type='text/javascript' src='{$cfg["path"]}/js/indexReturn.js'></script>";
			
			return $html;
		}
	}
?>

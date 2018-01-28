<?php
	class Admin_ordersView {
		public function render($data) {
			global $cfg;
			
			// Pobiera menu
			ob_start();
				include('inc/adminMenu.inc.php');
				$menu = ob_get_contents();
			ob_end_clean();
			
			$html = "<div id='page-name'>Ostatnie zamówienia</div>";
			
			
			
			
			// Wrap w prawą kolumnę
			$html = "<div id='content-column-right'>" . $html . "</div>";
			
			// Wrap w lewą kolumną - menu
			$html .= "<div id='content-column-left'>" . $menu . "</div>";
			
			return $html;
		}
	}
?>

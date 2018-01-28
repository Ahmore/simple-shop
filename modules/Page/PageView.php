<?php
	class Page_pageView {
		public function render($data) {
			$html = "";
			
			ob_start();
				include('inc/content.inc.php');
				$html .= ob_get_contents();
			ob_end_clean();
			
			return $html;
			// return file_get_contents("inc/content.inc.php");// "<div id='content-column-right'>" . file_get_contents("inc/content.inc.php") . "</div>";
		}
	}
?>
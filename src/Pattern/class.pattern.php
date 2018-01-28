<?php
	if (!defined("APP") || APP !== TRUE) {
		header('Location: index.php');
		exit;
	}
	
	
	class Pattern {
		public function __construct($header, $content, $footer, $title = "Pattern") {
			global $cfg;
			?>
				<!DOCTYPE html>
				<html>
					<head>
						<meta charset="UTF-8">
						<title><?php echo $title; ?></title>
						<link rel="stylesheet" href="<?php echo $cfg["path"]; ?>/css/resetDefaults.css">
						<link rel="stylesheet" href="<?php echo $cfg["path"]; ?>/css/style.css">
						<script type="text/javascript" src="<?php echo $cfg["path"]; ?>/js/jquery.js"></script>
						<script type="text/javascript" src="<?php echo $cfg["path"]; ?>/js/config.js"></script>
					</head>
					<body>
						<div id="header">
							<?php
								if (!file_exists($header)) {
									echo $header;
								}
								else {
									require_once $header; 
								}
							?>
						</div>
						<div id="content">
							<?php
								if (!file_exists($content)) {
									echo $content;
								}
								else {
									require_once $content; 
								}
							?>
						</div>
						<div id="footer">
							<?php
								if (!file_exists($footer)) {
									echo $footer;
								}
								else {
									require_once $footer; 
								}
							?>
						</div>
					</body>
				</html>
			<?php
		}
	}
?>
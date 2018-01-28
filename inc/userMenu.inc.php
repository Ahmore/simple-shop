<?php
	if (!defined("APP") || APP !== TRUE) {
		header('Location: index.php');
		exit;
	}
?>
<ul id="account-user-menu">
	<?php
		$html = "";
		$page = substr($_SERVER["REQUEST_URI"], 6);
		
		foreach ($cfg["userMenu"] as $link => $name) {
			$name = ucfirst($name);
			$class = "";
			
			if ($page == "/account/$link") {
				$class = "class='link-active'";
			}
			$html .= "<li><a href='{$cfg["path"]}/account/$link' $class>$name</a></li>";
		}
		echo $html;
	?>
</ul>
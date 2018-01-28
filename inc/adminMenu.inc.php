<?php
	if (!defined("APP") || APP !== TRUE) {
		header('Location: index.php');
		exit;
	}
?>
<ul id="admin-user-menu">
	<?php
		$html = "";
		$page = substr($_SERVER["REQUEST_URI"], 6);
		$page = explode("/", $page);
		$page = $page[2];
		
		foreach ($cfg["adminMenu"] as $link => $name) {
			$name = ucfirst($name);
			$class = "";
			
			if ($page == "$link") {
				$class = "class='link-active'";
			}
			$html .= "<li><a href='{$cfg["path"]}/admin/$link' $class>$name</a></li>";
		}
		echo $html;
	?>
</ul>
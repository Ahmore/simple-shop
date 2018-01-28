<?php
	define("APP", TRUE);
	
	session_start();
	
	// require_once 'src/Logger/class.Logger.php';
	require_once 'src/Login/class.Login.php';
	require_once 'src/class.Session.php';
	require_once 'src/config.php';
	require_once 'src/Pattern/class.pattern.php';
	require_once 'src/class.DataManager.php';
	require_once 'src/Collection/class.Collection.php';
	require_once 'src/Controller.php';
	require_once 'src/RouterAbstract.php';
	require_once 'src/RouterRegex.php';
	

	$router = new RouterRegex;
	
	// Główna konfiguracja
	$router->addRoute("/:controller/:action");
	$router->addRoute("/error", array('controller' => 'error', 'action' => 'showError'));
	$router->addRoute("/", array("controller" => "page", "action" => "page"));
	$router->addRoute("/index.php", array("controller" => "page", "action" => "page"));
	
	// Panel admina
	$router->addRoute("/admin/:action/int:id", array('controller' => 'admin'));
	$router->addRoute("/admin/:action/int:id/", array('controller' => 'admin'));
	$router->addRoute("/admin/:action/int:id/:sort", array('controller' => 'admin'));
	
	// Koszyk i produkty
	$router->addRoute("/:controller/:action/int:id");
	
	// Wyszukiwanie
	$router->addRoute("/:controller/:action/int:page/:category");
	$router->addRoute("/:controller/:action/int:page/:category/");
	$router->addRoute("/:controller/:action/int:page/:category/:string");
	
	// Konto i ustawienia
	$router->addRoute("/:controller/settings/:action");

	$controller = new Controller();
	$controller->setRouter($router);

	try {
		// Obcina ścieżkę do folderu aplikacji, gdyż nie jest w głównych folderze serwera
		$uri = substr($_SERVER["REQUEST_URI"], 6);
		if ($uri == "") {
			$uri = "/";
		}
		
		$content = $controller->dispatch($uri);
		
		// Tworzy strukturę strony
		new Pattern("inc/header.inc.php", $content, "inc/footer.inc.php", "Apteka Internetowa");
		
		// Zapamiętuje w sesji aktualną stronę
		$_SESSION["lastPage"] = $_SERVER['PHP_SELF'];
	}
	catch (Exception $e) {
		echo $e->getMessage();
	}
?>
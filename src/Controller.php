<?php
class Controller {
	/**
	* @var RouterAbstract
	*/
	protected $router = false;
	
	/**
	* Wykonuje nasze żądanie
	* 
	* @param string $url 
	*/
	public function dispatch($url, $default_data = array()) {
		try {
			if (!$this->router) {
				throw new Exception();
				// throw new Exception("Router not set");
			}	
			
			$route = $this->router->getRoute($url);
			$route += $default_data;
			
			$controller = ucfirst($route['controller']);
			$action = ucfirst($route['action']);
			unset($route['controller']);
			unset($route['action']);
			
			// Pobranie modelu
			$model = $this->getModel($controller);
			
			// Sprawdza istnienie akcji
			if (!method_exists($model, $action)) {
				throw new Exception();
				// throw new Exception("Niepoprawne żądanie");
			}
			
			$data = $model->{$action}($route);
			$data = $data + $route;
			
			// Pobranie widoku i wyświetlenie odpowiedzi
			$view = $this->getView($controller, $action);
			return $view->render($data);
		} 
		catch (Exception $e) {
			// Logger::logMessage('app', $e->getMessage(), LOGGER_DEBUG, 'try...catch w dispatch');
			
			try {
				if ($url != '/error') {
					$data = array('message' => $e->getMessage());
					return $this->dispatch("/error", $data);
				} 
				else {
					throw new Exception();
					// throw new Exception("Błąd niezdefiniowanej trasy");
				}
			} 
			catch (Exception $e) {
				return "<h1>Wystąpił nieznany błąd.</h1>";
			}
		}
	}
	
	/**
	* Ustawia router
	* 
	* @param RouterAbstract $router 
	*/
	public function setRouter(RouterAbstract $router) {
		$this->router = $router;
	}	
	
	/**
	* Pobiera obiekt klasę modelu
	* 
	* @param string $name
	* @return mixed
	*/
	protected function getModel($name) {
		$name .= '_Model';
		$this->includeClass($name);
		return new $name;
	}	
	
	/**
	* Pobiera obiekt widoku
	* 
	* @param string $name
	* @param string $action
	* @return mixed
	*/
	protected function getView($name, $action) {
		$name .= '_' .$action. 'View';
		$this->includeClass($name);
		return new $name;
	}
	
	/**
	* Dołącza klasę korzystając z nazewnictwa w stylu PEAR
	* 
	* @param string $name 
	* @return void
	* @throws Exception
	*/
	protected function includeClass($name) {
		global $cfg;
		
		$file = $cfg["modules_dir"] . str_replace('_', '/', $name) . '.php';
		
		if (!file_exists($file)) {
			throw new Exception();
			// throw new Exception("Nie znaleziono klasy!");
		}
		
		require_once $file;
	}
}
?>
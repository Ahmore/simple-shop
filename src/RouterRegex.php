<?php

class RouterRegex extends RouterAbstract {
	/**
	 * Dodaje nową trasę
	 * 
	 * @param string $route Wzorzec trasy
	 */
	public function addRoute($route, array $options = array())
	{
		$this->routes[] = array('pattern' => $this->_parseRoute($route), 'options' => $options);
	}

	/**
	 * Pobiera dane trasy
	 * 
	 * @param string $request URI żądania
	 * @return array 
	 */
	public function getRoute($request)
	{
		$matches = array();
		foreach ($this->routes as $route) {
			// Próba dopasowania żądania do zdefiniowanych tras
			if (preg_match($route['pattern'], $request, $matches)) {
				// Jeśli pasuje, następuje usunięcie niepotrzebnych indeksów
				foreach ($matches as $key => $value) {
					if (is_int($key)) {
						unset($matches[$key]);
					}
				}

				// Połączenie dopasowań z podanymi opcjami
				$result = $matches + $route['options'];
				return $result;
			}
		}

		throw new Exception();
    // throw new Exception("Trasa nie została znaleziona");
	}

	/**
	 * Parsuje wzorzec trasy
	 * 
	 * @param string $route Wzorzec
	 * @return string 
	 */
	protected function _parseRoute($route)
	{
		$baseUrl = $this->baseUrl;
		// Skrót dla trasy /
		if ($route == '/') {
			return "@^$baseUrl/$@";
		}

		// Rozbicie łańcucha wg znaków /
		$parts = explode("/", $route);

		// Początek wyrażenia regularnego, używamy @ zamiast /, aby uniknąć problemów ze ścieżką URL
        // Rozpoczęcie od bazowego adresu URL

		$regex = "@^$baseUrl";

		// Sprawdzenie czy zaczyna się od znaku / i odrzucenie pustego argumentu
		if ($route[0] == "/") {
			array_shift($parts);
		}

		// Przejrzenie wszystkich części adresu URL za pomocą pętli foreach
		foreach ($parts as $part) {
			// Dodanie znaku / do wyrażenia regularnego
			$regex .= "/";

			// Rozpoczęcie szukania łańcuchów typ:nazwa
			$args = explode(":", $part);

			if (sizeof($args) == 1) {
				// Jeśli jest tylko jedna wartość, jest to łańcuch statyczny
				$regex .= sprintf(self::REGEX_STATIC, preg_quote(array_shift($args), '@'));
				continue;
			} elseif ($args[0] == '') {
				// Jeśli pierwsza wartość jest pusta, to nie ma określonego typu, odrzucamy to
				array_shift($args);
				$type = false;
			} else {
				// Mamy typ, więc go wyciągamy
				$type = array_shift($args);
			}

			// Pobranie klucza
			$key = array_shift($args);

			// Jeśli to jest wyrażenie regularne, dodajemy je do wyrażenia i przechodzimy dalej
			if ($type == "regex") {
				$regex .= $key;
				continue;
			}

			// Usunięcie wszystkich znaków, które są niedozwolone w nazwach podwzorców
			$this->normalize($key);

			// Rozpoczęcie tworzenia nazwanego podwzorca
			$regex .= '(?P<' . $key . '>';

			// Dodanie rzeczywistego podwzorca
			switch (strtolower($type)) {
				case "int":
				case "integer":
					$regex .= self::REGEX_INT;
					break;
				case "alpha":
					$regex .= self::REGEX_ALPHA;
					break;
				case "alphanumeric":
				case "alphanum":
				case "alnum":
					$regex .= self::REGEX_ALPHANUMERIC;
					break;
				default:
					$regex .= self::REGEX_ANY;
					break;
			}

			// Zakończenie nazwanego podwzorca
			$regex .= ")";
		}

		// Dopasowywanie do końca adresu URL i uświadomienie go o unicode
		$regex .= '$@u';

		return $regex;
	}
}

/*
$router = new RouterRegex;
$router->addRoute("/alpha:page/alpha:action/:id", array('controller' => 'default'));
$router->addRoute("/photos/alnum:user/int:photoId/in/regex:(?P<groupType>([a-z]+?))-(?P<groupId>([0-9]+?))");

var_dump($router);

var_dump($router->getRoute('/user-account/view/123'));
var_dump($router->getRoute('/user-account/edit/123'));
var_dump($router->getRoute('/profile/view/123'));
var_dump($router->getRoute('/photos/dshafik/5584010786/in/set-72157626290864145'));
*/
?>
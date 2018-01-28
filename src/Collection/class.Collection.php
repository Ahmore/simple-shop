<?php
	require_once 'src/Collection/class.CollectionIterator.php';
	require_once 'src/Collection/class.Exceptions.php';
	
	class Collection implements IteratorAggregate{

		private $_members = array();    		// Elementy kolekcji
		private $_onload;               		// Funkcja zwrotna
		private $_isLoaded = false;     		// Flaga określająca, czy funkcja zwrotna została już wywołana
		
		public function addItem($obj, $key = null) {
			$this->_checkCallback();
		
			if ($key) {
				if (isset($this->_members[$key])) {
					throw new KeyInUseException("Klucz \"$key\" jest już zajęty!");
				} 
				else {
					$this->_members[$key] = $obj;
				}
			} 
			else {
				$this->_members[] = $obj;
			}
		}
		
		public function removeItem($key) {
			$this->_checkCallback();
		
			if (isset($this->_members[$key])) {
				unset($this->_members[$key]);
			} 
			else {
				throw new InvalidKeyException("Błędny klucz \"$key\"!");
			}
		}
		
		public function getItem($key) {
			$this->_checkCallback();
		
			if (isset($this->_members[$key])) {
				return $this->_members[$key];
			} 
			else {
				throw new InvalidKeyException("Błędny klucz \"$key\"!");
			}
		}
		
		public function keys() {
			$this->_checkCallback();
			
			return array_keys($this->_members);
		}
		
		public function length() {
			$this->_checkCallback();
			
			return sizeof($this->_members);
		}
		
		public function exists($key) {
			$this->_checkCallback();
			
			return (isset($this->_members[$key]));
		}
		
		/*
		 * Ta metoda pozwala na zdefiniowanie funkcji,
		 * którą należy wywołać, aby wypełnić kolekcję.
		 * Jedynym parametrem tej funkcji powinna być
		 * kolekcja do wypełnienia.
		 */
		public function setLoadCallback($functionName, $objOrClass = null) {
			if ($objOrClass) {
				$callback = array($objOrClass, $functionName);
			}
			else {
				$callback = $functionName;
			}
		
			// sprawdzenie, czy funkcję zwrotną da się wywołać
			if (!is_callable($callback, false, $callableName)) {
				throw new InvalidCallbackException("Funkcja zwrotna $callableName nieprawidłowa!");
				return false;
			}
			
			$this->_onload = $callback;
		}
		
		/*
		 * Sprawdzenie, czy funkcja zwrotna została zdefiniowana,
		 * a jeśli tak, czy została już wywołana. Jeśli nie,
		 *  zostaje ona wywołana.
		 */
		private function _checkCallback() {
			if (isset($this->_onload) && !$this->_isLoaded) {
				$this->_isLoaded = true;
				call_user_func($this->_onload, $this);
			}
		}
		
		// Implementacja iteratora
		public function getIterator() {
			$this->_checkCallback();
			
			return new CollectionIterator(clone $this);
		}
	}
?>		
<?php

	// Poziomy logowania. Im wy¿szy poziom, tym wiêksza waga wiadomo¶ci.
	// Luki w numeracji pozwalaj± na do³±czanie nowych poziomów.
	define('LOGGER_DEBUG', 100);
	define('LOGGER_INFO', 75);
	define('LOGGER_NOTICE', 50);
	define('LOGGER_WARNING', 25);
	define('LOGGER_ERROR', 10);
	define('LOGGER_CRITICAL', 5);

	class Logger {
		private $hLogFile;
		private $logLevel;
	
		// Uwaga: konstruktor prywatny. Klasa opiera siê na wzorcu singleton.
		private function __construct() {
	
		}
	
		public static function register($logName, $connectionString, $logger_level) {
			$urlData = parse_url($connectionString);
	
			if (!isset($urlData['scheme'])) {
				throw new Exception("B³êdny identyfikator po³±czenia $connectionString");
			}

			include_once('class.' . $urlData['scheme'] . 'LoggerBackend.php');
		
			$className = $urlData['scheme'] . 'LoggerBackend';
			
			if (!class_exists($className)) {
				throw new Exception('Brak silnika logowania dla ' . $urlData['scheme']);
			}
	
			$objBack = new $className($urlData, $logger_level);
			Logger::manageBackends($logName, $objBack);
		}
	
		public static function getInstance($name) {
			return Logger::manageBackends($name);
		}
	
		private static function manageBackends($name, LoggerBackend $objBack = null) {
			static $backEnds;
	
			if (!isset($backEnds)) {
				$backEnds = array();
			}
	
			if (!isset($objBack)) {
				// pobieranie
				if (isset($backEnds[$name])) {
					return $backEnds[$name];
				} 
				else {
					throw new Exception("Silnik $name nie zosta³ zarejestrowany.");
				}
			} 
			else {
				// dodawanie
				$backEnds[$name] = $objBack;
			}	
		}	

		public static function levelToString($logLevel) {
			switch ($logLevel) {
				case LOGGER_DEBUG:
					return 'LOGGER_DEBUG';
					break;
				case LOGGER_INFO:
					return 'LOGGER_INFO';
					break;
				case LOGGER_NOTICE:
					return 'LOGGER_NOTICE';
					break;
				case LOGGER_WARNING:
					return 'LOGGER_WARNING';
					break;
				case LOGGER_ERROR:
					return 'LOGGER_ERROR';
					break;
				case LOGGER_CRITICAL:
					return 'LOGGER_CRITICAL';
				default:
					return '[nieznany]';
			}
		}
		
		public static function logMessage($mode, $message, $logLevel, $module) {
			$log = Logger::getInstance($mode);
			$log->logMessage($message, $logLevel, $module);
		}
	}	
?>
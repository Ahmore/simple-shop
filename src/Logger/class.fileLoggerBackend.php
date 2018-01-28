<?php

	require_once('class.LoggerBackend.php');

	class fileLoggerBackend extends LoggerBackend {
		private $hLogFile;
	
		public function __construct($urlData, $loggerLevel) {
			parent::__construct($urlData, $loggerLevel);
	
				
			$logFilePath = $this->urlData['path'];
	
			if (!strlen($logFilePath)) {
				throw new Exception("W identyfikatorze po��czenia nie podano �cie�ki do pliku.");
			}
	
			// Otwiera plik dziennika. Tymczasowo wy��cza komunikaty o b��dach.
			// Z b��dami poradzimy sobie samodzielnie, zg�aszaj�c wyj�tek.
			$this->hLogFile = @fopen($logFilePath, 'a+');
			
			if (!is_resource($this->hLogFile)) {
				throw new Exception("Plik dziennika $logFilePath nie mo�e zosta� otwarty ani utworzony. Sprawd� uprawnienia.");
			}
		}

		public function logMessage($msg, $logLevel = LOGGER_INFO, $module = null) {
			if ($logLevel <= $this->logLevel) {
				$time = strftime('%x %X', time());
				$msg = str_replace("\t", '    ', $msg);
				$msg = str_replace("\n", ' ', $msg);
	
				$strLogLevel = Logger::levelToString($logLevel);
	
				if(isset($module)) {
					$module = str_replace("\t", '    ', $module);
					$module = str_replace("\n", ' ', $module);
				}
	
				// zapisuje: data/czas poziom komunikat modu�
				// oddzielony tabulacjami, zako�czony znakiem nowej linii
				$logLine = "$time\t$strLogLevel\t$msg\t$module\n";
				
				fwrite($this->hLogFile, $logLine);
			}
		}	
	}
?>
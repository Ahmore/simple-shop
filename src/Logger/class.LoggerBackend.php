<?php
abstract class LoggerBackend {
	protected $urlData;
	protected $logLevel;
	
	public function __construct($urlData, $loggerLevel) {
		$this->urlData = $urlData;
		$this->logLevel = $loggerLevel;
	}	
	
	abstract function logMessage($message, $logLevel = LOGGER_INFO, $module);
}
?>
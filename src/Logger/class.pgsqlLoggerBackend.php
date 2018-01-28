<?php

	require_once('class.LoggerBackend.php');

	class pgsqlLoggerBackend extends LoggerBackend {
		private $hConn;
	
		private $table = 'dziennik';
		private $messageField = 'komunikat';
		private $logLevelField = 'poziom_logowania';
		private $timestampField = 'data';
		private $moduleField = 'modul';
	
		public function __construct($urlData, $loggerLevel) {
			parent::__construct($urlData, $loggerLevel);
	
			$host = $urlData['host'];
			$port = $urlData['port'];
			$user = $urlData['user'];
			$password = $urlData['pass'];
			$arPath = explode('/', $urlData['path']);
			$database = $arPath[1];
	
			if(!strlen($database)) {
				throw new Exception('pgsqlLoggerBackend: B³êdny identyfikator po³¹czenia.  Nie podano nazwy bazy danych');
			}	

			$connStr = '';
			if ($host) {
				$connStr .= "host=$host ";
			}	
	
			if ($port) {
				$connStr .= "port=$port ";
			}
	
			if ($user) {
				$connStr .= "user=$user ";
			}	
	
			if ($password) {
				$connStr .= "password=$password ";
			}
	
			$connStr .= "dbname=$database";
	
			// Wstrzymanie wy¶wietlania komunikatów o b³êdach; zajmiemy siê tym w wyj±tku.
			$this->hConn = @pg_connect($connStr);
	
			if(!is_resource($this->hConn)) {
				throw new Exception("B³±d po³±czenia z baz± $connStr");
			}
	
			// Pobiera ci±g postaci var=foo&bar=blah
			// i zamienia go na tablicê, tak± jak ta
			// array('var' => 'foo', 'bar'=> 'blah').
			// Trzeba pamiêtaæ o konwersji zakodowanych warto¶ci (urldecode).
			$queryData = $urlData['query'];
			if(strlen($queryData)) {
				$arTmpQuery = explode('&',$queryData);
	
				$arQuery = array();
				foreach($arTmpQuery as $queryItem) {
					$arQueryItem = explode('=', $queryItem);
					$arQuery[urldecode($arQueryItem[0])] = urldecode($arQueryItem[1]);
				}
			}
	
			// ¯adna z poni¿szych zmiennych nie jest obowi±zkowa.
			// Warto¶ci domy¶lne s± zapisane w zmiennych sk³adowych.
			// Te zmienne okre¶laj± nazwê tabeli i jej pól.
			if(isset($arQuery['table'])) {
				$this->table = $arQuery['table'];
			}
	
			if(isset($arQuery['messageField'])) {
				$this->messageField = $arQuery['messageField'];
			}
	
			if(isset($arQuery['logLevelField'])) {
				$this->logLevelField = $arQuery['logLevelField'];
			}
	
			if(isset($arQuery['timestampField'])) {
				$this->timestampField = $arQuery['timestampField'];
			}
	
			if(isset($arQuery['moduleField'])) {
				$this->logLevelField = $arQuery['moduleField'];
			}
		}
	
	
		public function logMessage($msg, $logLevel = LOGGER_INFO, $module = null) {
	
			if($logLevel <= $this->logLevel) {
				$time = strftime('%x %X', time());
	
				$strLogLevel = Logger::levelToString($logLevel);
	
				$msg = pg_escape_string($msg);
	
				if(isset($module)) {
					$module = "'" . pg_escape_string($module) . "'";
				} else {
					$module = 'NULL';
				}
	
				$arFields = array();
				$arFields[$this->messageField] = "'" . $msg . "'";
				$arFields[$this->logLevelField] = $logLevel;
				$arFields[$this->timestampField] = "'". strftime('%x %X', time()) . "'";
				$arFields[$this->moduleField] = $module;

				$sql = 'INSERT INTO ' . $this->table;
				$sql .= ' (' . join(', ', array_keys($arFields)) . ')';
				$sql .= ' VALUES (' . join(', ', array_values($arFields)) . ')';
	
				pg_exec($this->hConn, $sql);
			}
		}
	}
?>
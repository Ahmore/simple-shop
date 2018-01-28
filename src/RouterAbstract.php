<?php
abstract class RouterAbstract {
  /**
	 * Obsługiwane grupy wyrażeń regularnych
	 */
	const REGEX_ANY = "([^/]+?)";
	const REGEX_INT = "([0-9]+?)";
	const REGEX_ALPHA = "([a-zA-Z_-]+?)";
	const REGEX_ALPHANUMERIC = "([0-9a-zA-Z_-]+?)";
	const REGEX_STATIC = "%s";
  
  /**
	 * @var array Skompilowane trasy
	 */
	protected $routes = array();
  
	/**
	 * @var string Bazowy URL 
	 */
	protected $baseUrl = '';
  
  /**
	 * Dodaje nową trasę
	 * 
	 * @param string $route Wzorzec trasy
	 */
  abstract public function addRoute($route, array $options = array());
  
  /**
	 * Pobiera dane trasy
	 * 
	 * @param string $request URI żądania
	 * @return array 
	 */
  abstract public function getRoute($request);
  
  /**
	 * Ustawia bazowy URL, z którego będą dopasowywane wszystkie trasy
   * 
	 * @param string $baseUrl 
	 */
	public function setBaseUrl($baseUrl)
	{
		// Przygotowanie bazowego adresu URL, z zastosowaniem symbolu @ jako znaku oddzielającego
		$this->baseUrl = preg_quote($baseUrl, '@');
	}
  
  /**
	 * Normalizuje łańcuch dla nazywania podwzorców
	 * 
	 * @param string &$param 
	 */
	public function normalize(&$param)
	{
		$param = preg_replace("/[^a-zA-Z0-9]/", "", $param);
	}
}
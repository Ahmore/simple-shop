<?php
	class Session {
		// Sprawdzenie zalogowania - administrator i użytkownik
		public static function normal() {
			global $cfg;
			
			/**
			  * Przeładowuje stronę w momencie:
			  * 	- niezalogowania,
			  * 	- wygaśnięcia sesji.
			  **/
			  
			if (!Login::isLogged() || !Login::checkSession()) {
				header("Location: {$cfg["path"]}/account/login");
			}
			Login::renewLoginTime();
		}
		
		// Sprawdzenie zalogowania - administrator
		public static function high() {
			global $cfg;
				
			/**
			  * Przeładowuje stronę w momencie:
			  * 	- niezalogowania,
			  * 	- nie bycia administratorem,
			  * 	- wygaśnięcia sesji.
			  **/
			  
			if (!Login::isLogged() || (Login::isLogged() && Login::getUserType() == 0) || !Login::checkSession()) {
				header("Location: {$cfg["path"]}/account/login");
			}
			Login::renewLoginTime();
		}
	}
?>
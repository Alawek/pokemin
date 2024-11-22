<?php

	require_once(ROOT . "/utils/IController.php");
	require_once(ROOT . "/utils/AbstractController.php");
	require_once(ROOT . "/utils/functions.php");
	require_once(ROOT . "/utils/session.php");

	class LogoutGetController extends AbstractController implements IController {

		public function __construct($form, $controllerName) {
			// Appel du constructeur de la classe mère AbstractController
			parent::__construct($form, $controllerName);
		}

		function checkForm() { /* */ }

        function checkCybersec() { /* */ }

        function checkRights() { 
			
		}

        function processRequest() {
			if ( !isLogged() ) {
				
				$this->response="Deconnection : KO ! Vous etes deja deconnecte(e).";

			}else{
			reinitSession();
			$this->response="Deconnection : Ok !";
		}
	}

	}

?>
<?php
define("ROOT", dirname(__FILE__)); // on veut une constante pour chemin de chargement on pourrait rajoute . "/" pour initialiser un slash et ne pas etre obligè de l'ecrire a chaque require.
require_once(ROOT ."/_config.php");
require_once(ROOT . '/utils/functions.php'); // chargement de la caisse à outils
require_once(ROOT . '/utils/session.php');
manageSession();
$FORM = extractForm();
// Dans TOUS les formulaires, je veux un paramètre route
$ROUTE = extractRoute($FORM);
// J'ai maintenant une route potable, je créer le controlleur et je l'exécute
$CONTROLLER = createController($FORM, $ROUTE);
$CONTROLLER->execute();



	 // chargement de la caisse à outils
	



<?php

require_once(ROOT . "/utils/IController.php");
require_once(ROOT . "/utils/AbstractController.php");
require_once(ROOT . "/utils/functions.php");
require_once(ROOT . "/utils/session.php");
require_once(ROOT . "/services/CompteService.php");
require_once(ROOT . "/exceptions/ConstraintUniqueException.php");

class LoginPostController extends AbstractController implements IController
{

    private CompteService $service;
    private string $email;
    private string $password;

    public function __construct($form, $controllerName)
    {
        // Appel du constructeur de la classe mère AbstractController
        parent::__construct($form, $controllerName);
        $this->service = new CompteService();
    }

    function checkForm()
    {
        error_log("Form data: " . print_r($this->form, true)); // Log de la structure du formulaire
        if (!isset($this->form['email'], $this->form['pwd'])) {
            error_log("CYBERSEC Receive bad request");
            _400_Bad_Request();
        }
    }


    function checkCybersec()
    {
        if ($this->service->emailIsAlready($this->form['email'])) {
            error_log("CYBERSEC Votre adresse n'existe pas ou est incorect");
            _400_Bad_Request();
        }
        $this->email = $this->form['email'];
        $this->password = $this->form['pwd'];
    }

    function checkRights()
    {
        error_log($this->controllerName . "->" . __FUNCTION__);
        if (isLogged()) {
            headerCustom(499, "Already Authenticated");
        }
    }

    function processRequest()
    {
        $compte = $this->service->getDao()->create($this->email, "", $this->password);
        $result = $this->service->login($compte);
        if (!is_null($result)) {
            login($result);
            $this->response = [
                'success' => true,
                'idCompte' => $result->getIdCompte(),
                'email' => $result->getEmail(),
                'pseudo' => $result->getPseudo(),
                'role' => $result->getRole()
            ];
        } else {
            $this->response = [
                'success' => false,
                'message' => 'Identifiants incorrects.'
            ];
        }
    }
}

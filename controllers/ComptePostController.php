<?php
require_once(ROOT . "/utils/IController.php");
require_once(ROOT . "/utils/AbstractController.php");
require_once(ROOT . "/utils/functions.php");
require_once(ROOT . "/services/CompteService.php");
require_once(ROOT . "/utils/session.php");

class ComptePostController extends AbstractController implements IController
{

    private CompteService $service;
    private string $email;
    private string $pseudo;
    private string $password;

    //construct

    public function __construct($form, $controllerName)
    {
        //Appel du constructeur de la classe mère AbstractController
        parent::__construct($form, $controllerName);
        $this->service = new CompteService();
    }
    //Méthode
    function checkForm()
    {
        //email, pseudo, password, password confirmation 
        if (! isset($this->form['email'], $this->form['pseudo'], $this->form['pwd'], $this->form['pwdc'])) {
            error_log("CYBERSEC Receive bad request");
            _400_Bad_Request();
        }
        //Ok

    }

    function checkCybersec()
    {
        if (! $this->service->emailIsAlready($this->form['email'])) {
            error_log("CYBERSEC Votre Email est deja pris");
            _400_Bad_Request();
        }
        var_dump($this->form['email']);
        if (!isEmailVerify($this->form['email'])) {
            error_log("CYBERSEC Votre Email n'est pas au bon format");
            _400_Bad_Request();
        }
        $this->email = $this->form['email'];

        if (! $this->service->pseudoIsAlready($this->form['pseudo'])) {
            error_log("CYBERSEC Votre Pseudo est deja utilisé");
            _400_Bad_Request();
        }
        $this->pseudo = $this->form['pseudo'];

        if ($this->form['pwd'] != $this->form['pwdc']) {
            error_log("CYBERSEC Vos mot de passe sont différent");
            _400_Bad_Request();
        }
        if (! checkPassword($this->form['pwd'])) {
            error_log("CYBERSEC Votre mot de passe ne contient pas au moins: 1 Majuscule,1 Minuscule, 1 caractère spéciaux, 1 chiffre.");
            _400_Bad_Request();
        }
        $this->password = htmlspecialchars(trim($this->form['pwd']), ENT_NOQUOTES, 'UTF-8');
        $this->pseudo = htmlspecialchars(trim($this->form['pseudo']), ENT_NOQUOTES, 'UTF-8');
        $this->email = htmlspecialchars(trim($this->form['email']), ENT_NOQUOTES, 'UTF-8');

    }

    //TODO:
    function checkRights()
    {
        if (isLogged()) {
            headerCustom(499, "Already Authenficated");
        }
    }


    //rajouter le 04/11 à 9:35:23:45:01:45:32:51
    function processRequest()
    {
        $compte = $this->service->getDao()->create($this->email, $this->pseudo, $this->password);
        try {
            $this->response = $this->service->insert($compte);
        } catch (ConstraintUniqueException $ex) {
            // var_dump($ex);
            headerCustom(498, "Business Error " . $ex->getCode() . " " . $ex->getMessage());
        } catch (Exception $ex) {
            error_log($ex);
        }
        // ???? sur les cas de tests ???
    }
}

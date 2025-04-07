<?php
require_once(ROOT . "/utils/IController.php");
require_once(ROOT . "/utils/AbstractController.php");
require_once(ROOT . "/utils/functions.php");
require_once(ROOT . "/services/CompteService.php");
require_once(ROOT . "/utils/session.php");

class ComptePutController extends AbstractController implements IController
{

    private CompteService $service;
    private Compte $comptes;
    private int $idCompte;
    private ?string $email;
    private ?string $password;
    private ?string $pseudo;
    private ?int $idRole;


    //construct

    public function __construct($form, $controllerName)
    {
        //Appel du constructeur de la classe mère AbstractController
        parent::__construct($form, $controllerName);
        $this->service = new CompteService();
        $this->comptes = new Compte();
        $this->email = null;
        $this->password = null;
        $this->pseudo = null;
        $this->idRole = null;
    }
    //Méthode
    function checkForm()
    {

        if (getCompteIdFromSession() == null) {
            _401_Unauthorized();
        }
        //Titre et contenu et idcompte ne doivent pas etre vide
        if (! isset($this->form['idCompte'])) {
            error_log("pas d'idCompte ");
            headerCustom(486, "Id Compte manquant");
        }
        // $this->compte = $this->service->findById($this->form['idCompte']);


    }

    function checkCybersec()
    {
        // error_log($this->controllerName . "->" . __FUNCTION__);
        if (!ctype_digit($this->form['idCompte'])) {
            headerCustom(841, "mauvais type");
        }
        if (!$this->service->findById($this->form['idCompte'])) {
            headerCustom(480, "Compte inexistant");
        }
        $this->idCompte = intval(trim($this->form['idCompte']));

        if (isset($this->form['email']) && !empty($this->form['email'])) {
            if (!isEmailVerify($this->form['email'])) {
                headerCustom(840, "Votre adresse email n'est pas au bon format");
            }
            if (!$this->service->emailIsAlready($this->form['email'])) {
                error_log("CYBERSEC l'email est déja utilisé");
                headerCustom(839, "Votre adresse email est deja utiliser");
            }
            $this->email = htmlspecialchars(trim($this->form['email']), ENT_NOQUOTES, 'UTF-8');
        }

        if (isset($this->form['password']) && !empty($this->form['password'])) {
            if (!checkPassword($this->form['password'])) {
                error_log("CYBERSEC Le mot de passe n'est pas assez fort");
                headerCustom(837, "Votre mot de passe n'est pas assez fort");
            }
            $this->password = hashedPassword(htmlspecialchars(trim($this->form['password'])), ENT_NOQUOTES, 'UTF-8');

        }

        if (isset($this->form['pseudo']) && !empty($this->form['pseudo'])) {
            if (!$this->service->pseudoIsAlready($this->form['pseudo'])) {
                error_log("CYBERSEC le pseudo est deja utiliser");
                headerCustom(838, "Votre pseudo est deja utiliser");
            }
            $this->pseudo = htmlspecialchars(trim($this->form['pseudo']), ENT_NOQUOTES, 'UTF-8');
        }

        if (isset($this->form['idRole']) && !empty($this->form['idRole'])) {
            if (!ctype_digit($this->form['idRole'])) {
                error_log("CYBERSEC l'id role n'est pas un entier'");
                _400_Bad_Request();
            }
            $this->idRole =  trim($this->form['pseudo']);
        }
    }

    function checkRights()
    {
        if (!isLogged()) {
            error_log("RIGHT l'utilisateur n'est pas connecter");
            _401_Unauthorized();
        }
        if ($this->idCompte != getCompteIdFromSession()) {
            _401_Unauthorized();
        }
    }
    //TODO La creation d'compte , il manque l'insertion de l'auteur
    function processRequest()
    {
        $compte = $this->service->findById($this->idCompte);
        if ($this->email != null) {
            $compte->setEmail($this->email);
        }
        if ($this->password != null) {
            $compte->setPassword($this->password);
        }
        if ($this->pseudo != null) {
            $compte->setPseudo($this->pseudo);
        }
        if($this->idRole != null){
            $compte->setRole($this->role);
        }


        $this->response = $this->service->update($compte);
        $this->response = "compte modifier";
    }
}

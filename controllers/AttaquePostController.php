<?php
require_once(ROOT . "/utils/IController.php");
require_once(ROOT . "/utils/AbstractController.php");
require_once(ROOT . "/utils/functions.php");
require_once(ROOT . "/services/AttaqueService.php");
require_once(ROOT . "/utils/session.php");

class AttaquePostController extends AbstractController implements IController
{

    private AttaqueService $service;
    private string $nom;
    private string $description;
    private int $degat;
    private string $style;
    private int $idType;


    //construct

    public function __construct($form, $controllerName)
    {
        //Appel du constructeur de la classe mère AbstractController
        parent::__construct($form, $controllerName);
        $this->service = new AttaqueService();
    
    }
    //Méthode
    function checkForm()
    {
        //nom, description, cri 
        if (! isset($this->form['nom'], $this->form['description'], $this->form['degat'],$this->form['style'], $this->form['idType'])) {
            error_log("FORM Information manquante [nom||description||degat||style||idType]");
            _400_Bad_Request();
        }
       
    }

    function checkCybersec()
    {
        if (!ctype_digit($this->form['idType'])) {
            error_log("CYBERSEC Mauvais typage pour l'id type ");
            _400_Bad_Request();
        }
        if ((!preg_match('/^[a-zA-ZÀ-ÿ0-9 .,\'!?-]*$/', $this->form['description'])) ||
            (!preg_match('/^[a-zA-ZÀ-ÿ0-9 .,\'!?-]*$/', $this->form['nom'])) ||
            (!preg_match('/^[a-zA-ZÀ-ÿ0-9 .,\'!?-]*$/', $this->form['style']))
        ) {
            headerCustom(840, "Caracteres non autorises detectes.");
        }

        $this->style = htmlspecialchars(trim($this->form['style']), ENT_NOQUOTES, 'UTF-8');
        $this->description = htmlspecialchars(trim($this->form['description']), ENT_NOQUOTES, 'UTF-8');
        $this->nom = htmlspecialchars(trim($this->form['nom']), ENT_NOQUOTES, 'UTF-8');
        $this->idType = trim(intval($this->form['idType']));

        if (isset($this->form['degat'])&& !empty($this->form['degat'])) {
            if (!ctype_digit($this->form['degat'])) {
                error_log("CYBERSEC mauvais typage pour degat");
                _400_Bad_Request();
            }
            $this->degat = trim($this->form['degat']);
        }
       
    }


    //TODO:
    function checkRights()
    {
        
        if (!isLogged() || getRoleIdFromSession()<2) {
            _401_Unauthorized();
        }
    }


    //rajouter le 04/11 à 9:35:23:45:01:45:32:51
    function processRequest()
    {
        $attaque = $this->service->getDao()->create($this->nom, $this->description, $this->degat,$this->style,$this->idType);
        try {
            $this->response = $this->service->insert($attaque);
        } catch (ConstraintUniqueException $ex) {
            // var_dump($ex);
            headerCustom(498, "Business Error " . $ex->getCode() . " " . $ex->getMessage());
        } catch (Exception $ex) {
            var_dump($ex);
        }
        // ???? sur les cas de tests ???
    }
}

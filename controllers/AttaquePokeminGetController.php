<?php
require_once(ROOT . "/utils/IController.php");
require_once(ROOT . "/utils/AbstractController.php");
require_once(ROOT . "/utils/functions.php");
require_once(ROOT . "/services/AttaquePokeminService.php");
require_once(ROOT . "/utils/session.php");


class AttaquePokeminGetController extends AbstractController implements IController
{

    private AttaquePokeminService $attaquePokeminService;
    private int $idAttaque;
    private int $idInstance;

    //construct

    public function __construct($form, $controllerName)
    {
        //Appel du constructeur de la classe mère AbstractController
        parent::__construct($form, $controllerName);
        $this->attaquePokeminService = new AttaquePokeminService();
    }
    //Méthode
    function checkForm()
{
    if (empty($this->form['idInstance'])) {
        error_log("idInstance manquant");
        _404_Not_Found();
    }

    // Vérifie que l'instance existe
    $instance = $this->attaquePokeminService->findAllByInstance($this->form['idInstance']);
    if (!$instance) {
        error_log("Instance introuvable");
        _404_Not_Found();
    }

    // Si idAttaque est fourni, on vérifie qu'elle existe pour cette instance
    if (!empty($this->form['idAttaque'])) {
        $attaque = $this->attaquePokeminService->findById($this->form['idInstance'], $this->form['idAttaque']);
        if (!$attaque) {
            error_log("Attaque introuvable pour cette instance");
            _404_Not_Found();
        }
    }

    
}

    
    function checkCybersec()
    {
            if (! ctype_digit($this->form['idInstance'])) {
                error_log("CYBERSEC recoit un mauvais typage d'id instance");
                _400_Bad_Request();
            }
            $this->idInstance = intval(trim($this->form['idInstance']));
            if(!empty($this->form['idAttaque'])){
                if(!ctype_digit($this->form['idAttaque'])){
                    error_log("CYBERSEC Recoit un mauvais typage d'id Attaque");
                    _400_Bad_Request();
                }
                $this->idAttaque=intval(trim($this->form['idAttaque']));
            }

        }
    

    function checkRights()
    {
        if (!isLogged()) {
            _401_Unauthorized();
        }

    }

    function processRequest()
    {
        if (!empty($this->idAttaque)) {
            error_log("Requête ciblée sur attaque {$this->idAttaque} pour l'instance {$this->idInstance}");
            $this->response = $this->attaquePokeminService->findAttaque($this->idAttaque, $this->idInstance);
        } else {
            error_log("Requête sur toutes les attaques de l'instance {$this->idInstance}");
            $this->response = $this->attaquePokeminService->findAllByInstance($this->idInstance);
        }
    }
    
}

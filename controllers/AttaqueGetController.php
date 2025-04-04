<?php
require_once(ROOT . "/utils/IController.php");
require_once(ROOT . "/utils/AbstractController.php");
require_once(ROOT . "/utils/functions.php");
require_once(ROOT . "/services/AttaqueService.php");

class AttaqueGetController extends AbstractController implements IController
{

    private AttaqueService $service;
    private int $id;

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
        if (isset($this->form['id']) && !empty($this->form['id'])) {
            if(!$this->service->findById($this->form['id'])){
                error_log("FORM Attaque introuvable");
                _404_Not_Found();
            }
        }
        //L'id doit être présent 
        // if( ! isset($this->form['id'])){
        //     error_log("CYBERSEC Receive bad request");
        //     _400_Bad_Request();
        // }
        //Ok

    }

    function checkCybersec()
    {
        if (isset($this->form['id']) && !empty($this->form['id'])) {
            if (! ctype_digit($this->form['id'])) {
                error_log("CYBERSEC Receive bad request");
                _400_Bad_Request();
            }
            $this->id = intval(trim($this->form['id']));

        }
    }

    //TODO:
    function checkRights()
    {
        

    }

    //TODO:
    function processRequest()
    {
        if (isset($this->form['id']) && !empty($this->form['id'])) {
            $this->response = $this->service->findById($this->id);
        }
        $this->response = $this->service->findAll();
    }
}

<?php
require_once(ROOT . "/utils/IController.php");
require_once(ROOT . "/utils/AbstractController.php");
require_once(ROOT . "/utils/functions.php");
require_once(ROOT . "/services/CompteService.php");

class CompteDeleteController extends AbstractController implements IController{

    private CompteService $service;
    private int $id;

    //construct

    public function __construct($form, $controllerName){
        //Appel du constructeur de la classe mère AbstractController
        parent::__construct($form,$controllerName);
        $this->service = new CompteService();
    }
    //Méthode
    function checkForm(){
        //L'id doit être présent 
        if( ! isset($this->form['id'])){
            error_log("CYBERSEC l'id n'est pas présent");
            _400_Bad_Request();
        }
        

    }

    function checkCybersec(){
        if( ! ctype_digit($this->form['id'])){
            error_log("CYBERSEC l'id n'est pas de type INT");
            _400_Bad_Request();
        }
        if(!$this->service->findById($this->form['id'])){
            error_log("CYBERSEC L'id n'est pas présent dans la base de donnée");
            _404_Not_Found();
        }
        $this->id = intval(trim($this->form['id']));

    }
    

    function checkRights(){
        error_log($this->controllerName . "->" . __FUNCTION__);
        if(!isLogged() || getRoleIdFromSession()<2){
            _401_Unauthorized();
        }
        if($this->id != getCompteIdFromSession() && getRoleIdFromSession()<2){
            _401_Unauthorized();
        }


    }


    function processRequest(){
        $this->response = $this->service->delete($this->id);
        

    }
  

}




<?php
require_once(ROOT . "/utils/IController.php");
require_once(ROOT . "/utils/AbstractController.php");
require_once(ROOT . "/utils/functions.php");
require_once(ROOT . "/services/AttaquePokeminService.php");
require_once(ROOT . "/utils/session.php");

class AttaquePokeminPostController extends AbstractController implements IController
{

    private AttaquePokeminService $attaquePokeminService;
    private int $idAttaque;
    private int $idInstance;
    private int $mana;
    private bool $active;
    private int $niveau;


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
        //idAttaque, idInstance, cri 
        if (! isset($this->form['idAttaque'], $this->form['idInstance'], $this->form['mana'],$this->form['active'], $this->form['niveau'])) {
            error_log("FORM Information manquante [idAttaque||idInstance||mana||active||niveau]");
            _400_Bad_Request();
        }
       
    }

    function checkCybersec()
{
    foreach (['idAttaque', 'idInstance', 'mana', 'niveau'] as $key) {
        if (!ctype_digit($this->form[$key])) {
            error_log("CYBERSEC mauvais typage pour $key");
            _400_Bad_Request();
        }
    }

    if (!isset($this->form['active']) || !in_array($this->form['active'], ['0', '1'], true)) {
        error_log("CYBERSEC mauvais typage booléen pour active");
        _400_Bad_Request();
    }

    $this->idAttaque = intval($this->form['idAttaque']);
    $this->idInstance = intval($this->form['idInstance']);
    $this->mana = intval($this->form['mana']);
    $this->niveau = intval($this->form['niveau']);
    $this->active = $this->form['active'] === '1';
}



    //TODO:
    function checkRights()
    {
        
       
    }


    //rajouter le 04/11 à 9:35:23:45:01:45:32:51
    function processRequest()
    {
        $attaque = $this->attaquePokeminService->getDao()->create($this->idAttaque, $this->idInstance, $this->mana,$this->active,$this->niveau);
        try {
            $this->response = $this->attaquePokeminService->insert($attaque);
        } catch (ConstraintUniqueException $ex) {
            // var_dump($ex);
            headerCustom(498, "Business Error " . $ex->getCode() . " " . $ex->getMessage());
        } catch (Exception $ex) {
            var_dump($ex);
        }
        // ???? sur les cas de tests ???
    }
}

<?php
require_once(ROOT . "/utils/IController.php");
require_once(ROOT . "/utils/AbstractController.php");
require_once(ROOT . "/utils/functions.php");
require_once(ROOT . "/services/CombatService.php");

class CombatPostController extends AbstractController implements IController
{
    private CombatService $combatService;
    private int $idAttaque;
    private int $idLanceur;
    private int $idCible;

    public function __construct($form, $controllerName)
    {
        parent::__construct($form, $controllerName);
        $this->combatService = new CombatService();
    }

    function checkForm()
    {
        if (!isset($this->form['idAttaque'], $this->form['idLanceur'], $this->form['idCible'])) {
            error_log("FORM champs manquants [idAttaque, idLanceur, idCible]");
            _400_Bad_Request();
        }
    }

    function checkCybersec()
    {
        foreach (['idAttaque', 'idLanceur', 'idCible'] as $key) {
            if (!ctype_digit($this->form[$key])) {
                error_log("CYBERSEC mauvais typage pour $key");
                _400_Bad_Request();
            }
        }

        $this->idAttaque = intval($this->form['idAttaque']);
        $this->idLanceur = intval($this->form['idLanceur']);
        $this->idCible = intval($this->form['idCible']);
    }

    function checkRights()
    {
        if (!isLogged()) {
            _401_Unauthorized();
        }    
    }

    function processRequest()
    {
        try {
            $resultat = $this->combatService->executerAttaque($this->idAttaque, $this->idLanceur, $this->idCible);
            $this->response = ["success" => true, "resultat" => $resultat];
        } catch (Exception $ex) {
            error_log("CombatPostController : " . $ex->getMessage());
            $this->response = ["success" => false, "message" => "Erreur lors de l'attaque"];
        }
    }
}

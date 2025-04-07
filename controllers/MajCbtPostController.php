<?php
require_once(ROOT . "/utils/IController.php");
require_once(ROOT . "/utils/AbstractController.php");
require_once(ROOT . "/utils/functions.php");
require_once(ROOT . "/services/PokeminInstanceService.php");
require_once(ROOT . "/utils/session.php");

class MajCbtPostController extends AbstractController implements IController
{
    private PokeminInstanceService $pokeminInstanceService;
    private int $idLanceur;
    private int $manaRestant;
    private int $idCible;
    private int $pvRestant;

    public function __construct($form, $controllerName)
    {
        parent::__construct($form, $controllerName);
        $this->pokeminInstanceService = new PokeminInstanceService();
    }

    function checkForm()
    {
        if (!isset($this->form['idLanceur'], $this->form['manaRestant'], $this->form['idCible'], $this->form['pvRestant'])) {
            error_log("FORM champs manquants [idLanceur, manaRestant, idCible, pvRestant]");
            _400_Bad_Request();
        }
    }

    function checkCybersec()
    {
        foreach (['idLanceur', 'manaRestant', 'idCible', 'pvRestant'] as $key) {
            if (!ctype_digit($this->form[$key])) {
                error_log("CYBERSEC mauvais typage pour $key");
                _400_Bad_Request();
            }
        }

        $this->idLanceur = intval($this->form['idLanceur']);
        $this->manaRestant = intval($this->form['manaRestant']);
        $this->idCible = intval($this->form['idCible']);
        $this->pvRestant = intval($this->form['pvRestant']);
    }

    function checkRights()
    {
    }

    function processRequest()
    {
        try {
            $lanceur = $this->pokeminInstanceService->findById($this->idLanceur);
            $cible = $this->pokeminInstanceService->findById($this->idCible);
    
            if ($this->idLanceur === $this->idCible) {
                // Soin : on met à jour mana ET pv pour le même Pokemin
                $lanceur->setMana($this->manaRestant);
                $lanceur->setPv($this->pvRestant);
                $this->pokeminInstanceService->update($lanceur);
            } else {
                $lanceur->setMana($this->manaRestant);
                $cible->setPv($this->pvRestant);
                $this->pokeminInstanceService->update($lanceur);
                $this->pokeminInstanceService->update($cible);
            }
    
            $this->response = ["success" => true, "message" => "Combat mis à jour"];
        } catch (Exception $ex) {
            error_log(" Erreur MajCbtPostController : " . $ex->getMessage());
            $this->response = ["success" => false, "message" => "Erreur lors de la mise à jour du combat"];
        }
    }
    
}

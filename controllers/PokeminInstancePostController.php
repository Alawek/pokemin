<?php
require_once(ROOT . "/utils/IController.php");
require_once(ROOT . "/utils/AbstractController.php");
require_once(ROOT . "/utils/functions.php");
require_once(ROOT . "/services/PokeminInstanceService.php");
require_once(ROOT . "/utils/session.php");

class PokeminInstancePostController extends AbstractController implements IController
{

    private PokeminInstanceService $service;
    private string $nom;
    private int $niveau;
    private int $experience;
    private int $experienceMax;
    private int $pv;
    private int $pvMax;
    private int $mana;
    private int $manaMax;
    private int $agilite;
    private int $chance;
    private int $endurance;
    private int $esprit;
    private int $puissance;
    private int $intelligence;
    private bool $sauvage;
    private ?bool $actif;
    private int $idPokemin;
    private ?int $idDresseur;
    private ?int $idPersonnage;


    //construct

    public function __construct($form, $controllerName)
    {
        //Appel du constructeur de la classe mère AbstractController
        parent::__construct($form, $controllerName);
        $this->service = new PokeminInstanceService();
        $this->actif = null;
        $this->idDresseur = null;
        $this->idPersonnage = null;
        
    }
    //Méthode
    function checkForm()
    {
        //nom, description, cri 
        if (! isset($this->form['nom'],$this->form['niveau'], $this->form['experience'], $this->form['experienceMax'], $this->form['pv'],$this->form['pvMax'],$this->form['mana'],$this->form['manaMax'],$this->form['agilite'],$this->form['chance'],$this->form['endurance'],$this->form['esprit'],$this->form['puissance'],$this->form['intelligence'],$this->form['sauvage'],$this->form['idPokemin'])) {
            error_log("FORM Information manquante obligatoire.");
            _400_Bad_Request();
        }
       
    }

    function checkCybersec()
    {
        // Champs numériques obligatoires déjà présents (isset dans checkForm)
        $intFields = [
            'niveau', 'experience', 'experienceMax',
            'pv', 'pvMax', 'mana', 'manaMax',
            'agilite', 'chance', 'endurance',
            'esprit', 'puissance', 'intelligence', 'idPokemin'
        ];
    
        foreach ($intFields as $key) {
            if (!ctype_digit($this->form[$key])) {
                error_log("CYBERSEC Mauvais typage pour '$key'");
                _400_Bad_Request();
            }
            $this->$key = intval($this->form[$key]);
        }
    
        // Champ 'nom' : validation de caractères autorisés
        if (!preg_match('/^[a-zA-ZÀ-ÿ0-9 .,\'!?-]*$/u', $this->form['nom'])) {
            error_log("CYBERSEC caractères non autorisés dans le nom");
            headerCustom(840, "Caractères non autorisés dans le champ 'nom'");
        }
    
        $this->nom = htmlspecialchars(trim($this->form['nom']), ENT_NOQUOTES, 'UTF-8');
    
        // Champ booléen obligatoire : 'sauvage'
        $this->sauvage = $this->form['sauvage'] === '1';
    
        // Champ facultatif : actif
        if (isset($this->form['actif'])) {
            $this->actif = $this->form['actif'] === '1';
        }
    
        // Champ facultatif : idDresseur
        if (isset($this->form['idDresseur'])) {
            if (!ctype_digit($this->form['idDresseur'])) {
                error_log("CYBERSEC Mauvais typage pour idDresseur");
                _400_Bad_Request();
            }
            $this->idDresseur = intval($this->form['idDresseur']);
        }
    
        // Champ facultatif : idPersonnage
        if (isset($this->form['idPersonnage']) && !empty($this->form['idPersonnage'])) {
            if (!ctype_digit($this->form['idPersonnage'])) {
                error_log("CYBERSEC Mauvais typage pour idPersonnage");
                _400_Bad_Request();
            }
            $this->idPersonnage = intval($this->form['idPersonnage']);
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
        $pokeminInstance = $this->service->getDao()->create($this->nom, $this->niveau, $this->experience,$this->experienceMax,$this->pv,$this->pvMax,$this->mana,$this->manaMax,$this->agilite,$this->chance,$this->endurance,$this->esprit,$this->puissance,$this->intelligence,$this->sauvage,$this->actif,$this->idPokemin,$this->idDresseur,$this->idPersonnage);
        try {
            $this->response = $this->service->insert($pokeminInstance);
        } catch (ConstraintUniqueException $ex) {
            // var_dump($ex);
            headerCustom(498, "Business Error " . $ex->getCode() . " " . $ex->getMessage());
        } catch (Exception $ex) {
            var_dump($ex);
        }
        // ???? sur les cas de tests ???
    }
}

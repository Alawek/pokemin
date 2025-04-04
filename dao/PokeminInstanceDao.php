<?php
//______REQUIRE_______________________________________________________________________________________________________________________________________________________________

require_once(ROOT . "/utils/IDao.php");
require_once(ROOT . "/utils/AbstractDao.php");
require_once(ROOT . "/utils/BddSingleton.php");
require_once(ROOT . "/model/PokeminInstance.php");
require_once(ROOT . "/utils/exceptions.php");
require_once(ROOT . "/utils/functions.php");
require_once(ROOT . "/exceptions/ConstraintUniqueException.php");
require_once(ROOT . "/dao/PokeminDao.php");
//______REQUIRE_______________________________________________________________________________________________________________________________________________________________



class PokeminInstanceDao extends AbstractDao implements IDao
{

    //______ATTRIBUT_______________________________________________________________________________________________________________________________________________________________
    private $pdo;
    private PokeminDao $pokeminDao;
    //______ATTRIBUT_______________________________________________________________________________________________________________________________________________________________


    //______CONSTRUCTEUR_______________________________________________________________________________________________________________________________________________________________
    function __construct()
    {
        $this->pdo = BddSingleton::getInstance()->getPDO();
        $this->pokeminDao = new PokeminDao();
    }
    //______CONSTRUCTEUR_______________________________________________________________________________________________________________________________________________________________
    //______METHODE/REQUETE SQL_______________________________________________________________________________________________________________________________________________________________

    public function createFromRow($row)
    {
        $pokeminInstance = new PokeminInstance();

        $pokeminInstance->setIdInstance(intval($row->id_instance));
        $pokeminInstance->setNom($row->nom);
        $pokeminInstance->setNiveau(intval($row->niveau));
        $pokeminInstance->setExperience(intval($row->experience));
        $pokeminInstance->setExperienceMax(intval($row->experiencemax));
        $pokeminInstance->setPv(intval($row->pv));
        $pokeminInstance->setPvMax(intval($row->pvmax));
        $pokeminInstance->setMana(intval($row->mana));
        $pokeminInstance->setManaMax(intval($row->manamax));
        $pokeminInstance->setAgilite(intval($row->agilite));
        $pokeminInstance->setChance(intval($row->chance));
        $pokeminInstance->setEndurance(intval($row->endurance));
        $pokeminInstance->setEsprit(intval($row->esprit));
        $pokeminInstance->setPuissance(intval($row->puissance));
        $pokeminInstance->setIntelligence(intval($row->intelligence));
        $pokeminInstance->setSauvage(boolval($row->sauvage));
        $pokeminInstance->setActif($row->actif === null ? null : boolval($row->actif));
        $pokeminInstance->setIdPokemin(intval($row->id_pokemin));
        $pokeminInstance->setIdDresseur($row->id_dresseur === null ? null : intval($row->id_dresseur));
        $pokeminInstance->setIdPersonnage($row->id_personnage === null ? null : intval($row->id_personnage));
        $pokeminInstance->setPokeminBase($this->pokeminDao->findById($row->id_pokemin));


        return $pokeminInstance;
    }

    public function create($nom, $niveau, $experience, $experienceMax, $pv, $pvMax, $mana, $manaMax, $agilite, $chance, $endurance, $esprit, $puissance, $intelligence, $sauvage, $actif = NULL, $idPokemin, $idDresseur = NULL, $idPersonnage = NULL)
    {
        // Création de l'instance de PokeminInstance
        $pokeminInstance = new PokeminInstance();

        // Assignation des valeurs aux attributs de PokeminInstance
        $pokeminInstance->setNom($nom);
        $pokeminInstance->setNiveau(intval($niveau));
        $pokeminInstance->setExperience(intval($experience));
        $pokeminInstance->setExperienceMax(intval($experienceMax));
        $pokeminInstance->setPv(intval($pv));
        $pokeminInstance->setPvMax(intval($pvMax));
        $pokeminInstance->setMana(intval($mana));
        $pokeminInstance->setManaMax(intval($manaMax));
        $pokeminInstance->setAgilite(intval($agilite));
        $pokeminInstance->setChance(intval($chance));
        $pokeminInstance->setEndurance(intval($endurance));
        $pokeminInstance->setEsprit(intval($esprit));
        $pokeminInstance->setPuissance(intval($puissance));
        $pokeminInstance->setIntelligence(intval($intelligence));
        $pokeminInstance->setSauvage(boolval($sauvage));
        $pokeminInstance->setActif($actif === NULL ? NULL : boolval($actif));
        $pokeminInstance->setIdPokemin(intval($idPokemin));
        $pokeminInstance->setPokeminBase($this->pokeminDao->findById($idPokemin));
        $pokeminInstance->setIdDresseur($idDresseur === NULL ? NULL : intval($idDresseur));
        $pokeminInstance->setIdPersonnage($idPersonnage === NULL ? NULL : intval($idPersonnage));

        return $pokeminInstance;
    }


    function insert(IEntity $pokeminInstance)
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO instance_pokemin (nom, niveau, experience, experiencemax, pv, pvmax, mana, manamax, agilite, chance, endurance, esprit, puissance, intelligence, sauvage, actif, id_pokemin, id_dresseur, id_personnage)
        VALUES (:nom, :niveau, :experience, :experience_max, :pv, :pv_max, :mana, :mana_max, :agilite, :chance, :endurance, :esprit, :puissance, :intelligence, :sauvage, :actif, :id_pokemin, :id_dresseur, :id_personnage)"
        );
        $stmt->bindValue(':nom', $pokeminInstance->getNom());
        $stmt->bindValue(':niveau', $pokeminInstance->getNiveau());
        $stmt->bindValue(':experience', $pokeminInstance->getExperience());
        $stmt->bindValue(':experience_max', $pokeminInstance->getExperienceMax());
        $stmt->bindValue(':pv', $pokeminInstance->getPv());
        $stmt->bindValue(':pv_max', $pokeminInstance->getPvMax());
        $stmt->bindValue(':mana', $pokeminInstance->getMana());
        $stmt->bindValue(':mana_max', $pokeminInstance->getManaMax());
        $stmt->bindValue(':agilite', $pokeminInstance->getAgilite());
        $stmt->bindValue(':chance', $pokeminInstance->getChance());
        $stmt->bindValue(':endurance', $pokeminInstance->getEndurance());
        $stmt->bindValue(':esprit', $pokeminInstance->getEsprit());
        $stmt->bindValue(':puissance', $pokeminInstance->getPuissance());
        $stmt->bindValue(':intelligence', $pokeminInstance->getIntelligence());
        $stmt->bindValue(':sauvage', $pokeminInstance->estSauvage());
        $stmt->bindValue(':actif', $pokeminInstance->estActif() === null ? null : boolval($pokeminInstance->estActif())); // Gérer le cas nul
        $stmt->bindValue(':id_pokemin', $pokeminInstance->getIdPokemin());
        $stmt->bindValue(':id_dresseur', $pokeminInstance->getIdDresseur() === null ? null : intval($pokeminInstance->getIdDresseur())); // Gérer le cas nul
        $stmt->bindValue(':id_personnage', $pokeminInstance->getIdPersonnage() === null ? null : intval($pokeminInstance->getIdPersonnage())); // Gérer le cas nul

        // Exécution de la requête et gestion des erreurs
        try {
            $stmt->execute();
            // Retourner l'ID de l'instance récemment insérée
            return $this->pdo->lastInsertId();
        } catch (PDOException $ex) {
            // En cas d'erreur, on enveloppe l'exception
            $newEx = wrapPDOException($ex);
            throw $newEx;
        }
    }


    function findById(int $id): ?PokeminInstance
    {
        error_log("📡 DAO: Recherche PokeminInstance ID = " . $id);
        $stmt = $this->pdo->prepare("SELECT * FROM instance_pokemin instance  WHERE instance.id_instance = :id");
        $stmt->bindParam(':id', $id);
        $stmt->setFetchMode(PDO::FETCH_OBJ);
        $stmt->execute();
        $row = $stmt->fetch();
        if (! $row) {
            return NULL;
        }

        error_log("✅ Pokemin trouvé avec id_instance = " . $row->id_instance);
        $pokemin = $this->createFromRow($row);


        return $pokemin;
    }

    function findAll()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM instance_pokemin ");
        $stmt->setFetchMode(PDO::FETCH_OBJ);
        $stmt->execute();
        $row = $stmt->fetch();
        if (! $row) {
            return NULL;
        }

        $pokemin = $this->createFromRow($row);


        return $pokemin;
    }

    function delete(int $id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM instance_pokemin WHERE id_instance = :id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        if ($this->findById($id)) {
            return "Problème de Suppresion";
        }
        return "Suppression ok";
    }
    public function update(IEntity $pokeminInstance)
    {
        // Préparation de la requête SQL pour mettre à jour les informations de l'instance Pokemin
        $stmt = $this->pdo->prepare("UPDATE instance_pokemin SET 
                                        nom = :nom,
                                        niveau = :niveau,
                                        experience = :experience,
                                        experience_max = :experiencemax,
                                        pv = :pv,
                                        pv_max = :pvmax,
                                        mana = :mana,
                                        mana_max = :manamax,
                                        agilite = :agilite,
                                        chance = :chance,
                                        endurance = :endurance,
                                        esprit = :esprit,
                                        puissance = :puissance,
                                        intelligence = :intelligence,
                                        sauvage = :sauvage,
                                        actif = :actif,
                                        id_pokemin = :id_pokemin,
                                        id_dresseur = :id_dresseur,
                                        id_personnage = :id_personnage
                                        WHERE id_instance = :id_instance");

        $stmt->bindValue(':nom', $pokeminInstance->getNom());
        $stmt->bindValue(':niveau', $pokeminInstance->getNiveau(), PDO::PARAM_INT);
        $stmt->bindValue(':experience', $pokeminInstance->getExperience(), PDO::PARAM_INT);
        $stmt->bindValue(':experience_max', $pokeminInstance->getExperienceMax(), PDO::PARAM_INT);
        $stmt->bindValue(':pv', $pokeminInstance->getPv(), PDO::PARAM_INT);
        $stmt->bindValue(':pv_max', $pokeminInstance->getPvMax(), PDO::PARAM_INT);
        $stmt->bindValue(':mana', $pokeminInstance->getMana(), PDO::PARAM_INT);
        $stmt->bindValue(':mana_max', $pokeminInstance->getManaMax(), PDO::PARAM_INT);
        $stmt->bindValue(':agilite', $pokeminInstance->getAgilite(), PDO::PARAM_INT);
        $stmt->bindValue(':chance', $pokeminInstance->getChance(), PDO::PARAM_INT);
        $stmt->bindValue(':endurance', $pokeminInstance->getEndurance(), PDO::PARAM_INT);
        $stmt->bindValue(':esprit', $pokeminInstance->getEsprit(), PDO::PARAM_INT);
        $stmt->bindValue(':puissance', $pokeminInstance->getPuissance(), PDO::PARAM_INT);
        $stmt->bindValue(':intelligence', $pokeminInstance->getIntelligence(), PDO::PARAM_INT);
        $stmt->bindValue(':sauvage', $pokeminInstance->getSauvage(), PDO::PARAM_BOOL);
        $stmt->bindValue(':actif', $pokeminInstance->estActif() === null ? null : boolval($pokeminInstance->getActif()));
        $stmt->bindValue(':id_pokemin', $pokeminInstance->getIdPokemin(), PDO::PARAM_INT);
        $stmt->bindValue(':id_dresseur', $pokeminInstance->getIdDresseur() === null ? null : $pokeminInstance->getIdDresseur(), PDO::PARAM_INT);
        $stmt->bindValue(':id_personnage', $pokeminInstance->getIdPersonnage() === null ? null : $pokeminInstance->getIdPersonnage(), PDO::PARAM_INT);
        $stmt->bindValue(':id_instance', $pokeminInstance->getIdInstance(), PDO::PARAM_INT);

        // Exécution de la requête
        try {
            $stmt->execute();
        } catch (PDOException $ex) {
            $newEx = wrapPDOException($ex);
            throw $newEx;
        }
    }






    //______METHODE/REQUETE_SQL_______________________________________________________________________________________________________________________________________________________________  

}

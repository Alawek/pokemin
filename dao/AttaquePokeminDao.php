<?php
//______REQUIRE_______________________________________________________________________________________________________________________________________________________________

require_once(ROOT . "/utils/IDao.php");
require_once(ROOT . "/utils/AbstractDao.php");
require_once(ROOT . "/utils/BddSingleton.php");
require_once(ROOT . "/model/AttaquePokemin.php");
require_once(ROOT . "/utils/exceptions.php");
require_once(ROOT . "/utils/functions.php");
require_once(ROOT . "/exceptions/ConstraintUniqueException.php");
require_once(ROOT . "/dao/PokeminInstanceDao.php");
require_once(ROOT . "/dao/AttaqueDao.php");
//______REQUIRE_______________________________________________________________________________________________________________________________________________________________



class AttaquePokeminDao extends AbstractDao implements IDao
{

    //______ATTRIBUT_______________________________________________________________________________________________________________________________________________________________
    private $pdo;
    private PokeminInstanceDao $pokeminInstanceDao;
    private AttaqueDao $attaqueDao;
    //______ATTRIBUT_______________________________________________________________________________________________________________________________________________________________


    //______CONSTRUCTEUR_______________________________________________________________________________________________________________________________________________________________
    function __construct()
    {
        $this->pdo = BddSingleton::getInstance()->getPDO();
        $this->pokeminInstanceDao = new PokeminInstanceDao();
        $this->attaqueDao = new AttaqueDao();
    }
    //______CONSTRUCTEUR_______________________________________________________________________________________________________________________________________________________________
    //______METHODE/REQUETE SQL_______________________________________________________________________________________________________________________________________________________________

    public function createFromRow($row)
    {
        $attaquePokemin = new AttaquePokemin();

        $attaquePokemin->setIdAttaque(intval($row->id_attaque));
        $attaquePokemin->setIdPokemin($row->id_instance);
        $attaquePokemin->setMana(intval($row->mana));
        $attaquePokemin->setActive(boolval($row->active));
        $attaquePokemin->setNiveau(intval($row->niveau_obtention));
        $attaquePokemin->setPokeminInstance($this->pokeminInstanceDao->findById($row->id_instance));
        $attaquePokemin->setAttaque($this->attaqueDao->findById($row->id_attaque));


        return $attaquePokemin;
    }

    public function create($idAttaque, $idPokeminInstance, $mana, $active, $niveau)
    {
        // Création de l'instance de AttaquePokemin
        $attaquePokemin = new AttaquePokemin();

        // Assignation des valeurs aux attributs de AttaquePokemin
        $attaquePokemin->setIdAttaque(intval($idAttaque));
        $attaquePokemin->setIdPokemin(intval($idPokeminInstance));
        $attaquePokemin->setMana(intval($mana));
        $attaquePokemin->setActive(boolval($active));
        $attaquePokemin->setNiveau(intval($niveau));
        $attaquePokemin->setPokeminInstance($this->pokeminInstanceDao->findById($idPokeminInstance));
        $attaquePokemin->setAttaque($this->attaqueDao->findById($idAttaque));


        return $attaquePokemin;
    }


    function insert(IEntity $attaquePokemin)
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO attaque_pokemin (id_attaque,id_instance,mana,active,niveau_obtention)
        VALUES (:idAttaque,:idInstance,:mana,:active,:niveau)"
        );
        $stmt->bindValue(':idAttaque', $attaquePokemin->getIdAttaque());
        $stmt->bindValue(':idInstance', $attaquePokemin->getIdPokemin());
        $stmt->bindValue(':mana', $attaquePokemin->getMana());
        $stmt->bindValue(':active', $attaquePokemin->isActive());
        $stmt->bindValue(':niveau', $attaquePokemin->getNiveau());

        try {
            $stmt->execute();

            return $this->findAttaque($attaquePokemin->getIdAttaque(), $attaquePokemin->getIdPokemin());
        } catch (PDOException $ex) {
            throw wrapPDOException($ex);
        }
    }



    function findAttaque(int $idAttaque, int $idInstance): ?AttaquePokemin
    {

        $stmt = $this->pdo->prepare("SELECT * FROM attaque_pokemin  WHERE id_attaque = :idAttaque AND id_instance = :idInstance");
        $stmt->bindParam(':idInstance', $idInstance);
        $stmt->bindParam(':idAttaque', $idAttaque);

        $stmt->setFetchMode(PDO::FETCH_OBJ);
        $stmt->execute();
        $row = $stmt->fetch();
        if (! $row) {
            return NULL;
        }


        $attaque = $this->createFromRow($row);


        return $attaque;
    }



    function findAllByInstance(int $idInstance): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM attaque_pokemin WHERE id_instance = :idInstance");
        $stmt->bindParam(':idInstance', $idInstance);
        $stmt->execute();

        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            $result[] = $this->createFromRow($row);
        }

        return $result;
    }


    function findAll(): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM attaque_pokemin");
        $stmt->setFetchMode(PDO::FETCH_OBJ);
        $stmt->execute();

        $result = [];
        while ($row = $stmt->fetch()) {
            $result[] = $this->createFromRow($row);
        }

        return $result;
    }

    function deleteAttaque(int $idAttaque, int $idInstance)
    {
        $stmt = $this->pdo->prepare("DELETE FROM attaque_pokemin WHERE id_attaque = :idAttaque AND id_instance = :idInstance");
        $stmt->bindParam(":idAttaque", $idAttaque);
        $stmt->bindParam(":idInstance", $idInstance);
        $stmt->execute();
        if ($this->findById($idInstance, $idAttaque)) {
            return "Problème de Suppresion";
        }
        return "Suppression ok";
    }



    public function update(IEntity $attaquePokemin)
    {
        // Préparation de la requête SQL pour mettre à jour les informations de l'instance Pokemin
        $stmt = $this->pdo->prepare("UPDATE attaque_pokemin SET 
                                        id_attaque = :idAttaque,
                                        id_instance = :idInstance,
                                        mana = :mana,
                                        active = :active,
                                        niveau_obtention = :niveau
                                        WHERE id_instance = :idInstance AND id_attaque = :idAttaque");

        $stmt->bindValue(':idAttaque', $attaquePokemin->getIdAttaque());
        $stmt->bindValue(':idInstance', $attaquePokemin->getIdPokemin(), PDO::PARAM_INT);
        $stmt->bindValue(':mana', $attaquePokemin->getMana(), PDO::PARAM_INT);
        $stmt->bindValue(':active', $attaquePokemin->isActive(), PDO::PARAM_BOOL);
        $stmt->bindValue(':niveau', $attaquePokemin->getNiveau(), PDO::PARAM_INT);


        // Exécution de la requête
        try {
            $stmt->execute();
        } catch (PDOException $ex) {
            $newEx = wrapPDOException($ex);
            throw $newEx;
        }
    }

    function delete($id) {}

    public function findById(int $idAttaque): ?AttaquePokemin
    {
        return null;
    }


    //______METHODE/REQUETE_SQL_______________________________________________________________________________________________________________________________________________________________  

}

<?php
//______REQUIRE_______________________________________________________________________________________________________________________________________________________________

require_once(ROOT . "/utils/IDao.php");
require_once(ROOT . "/utils/AbstractDao.php");
require_once(ROOT . "/utils/BddSingleton.php");
require_once(ROOT . "/model/Attaque.php");
require_once(ROOT . "/utils/exceptions.php");
require_once(ROOT . "/utils/functions.php");
require_once(ROOT . "/exceptions/ConstraintUniqueException.php");
//______REQUIRE_______________________________________________________________________________________________________________________________________________________________



class AttaqueDao extends AbstractDao implements IDao
{

    //______ATTRIBUT_______________________________________________________________________________________________________________________________________________________________
    private $pdo;

    //______ATTRIBUT_______________________________________________________________________________________________________________________________________________________________


    //______CONSTRUCTEUR_______________________________________________________________________________________________________________________________________________________________
    function __construct()
    {
        $this->pdo = BddSingleton::getInstance()->getPDO();
    }
    //______CONSTRUCTEUR_______________________________________________________________________________________________________________________________________________________________
    //______METHODE/REQUETE SQL_______________________________________________________________________________________________________________________________________________________________

    public function createFromRow($row)
    {
        $Attaque = new Attaque();
        $Attaque->setId(intval($row->id_attaque));
        $Attaque->setNom($row->nom);
        $Attaque->setDescription($row->description); // ICI
        $Attaque->setDegat($row->degat);
        $Attaque->setStyle($row->style);
        $Attaque->setIdType($row->id_type);

        return $Attaque;
    }

    public function create($nom, $description, $degat, $style ,$type)
    {
        $Attaque = new Attaque();
        $Attaque->setNom($nom);
        $Attaque->setDescription($description); // ICI
        $Attaque->setDegat($degat);
        $Attaque->setStyle($style);
        $Attaque->setIdType($type);
        return $Attaque;
    }

    function insert(IEntity $Attaque)
    {
        var_dump($Attaque);
        $stmt = $this->pdo->prepare("INSERT INTO attaque (nom,description,degat,style,id_type) VALUES " .
            "(:nom,:description,:dgt,:stl,:type  )");
        $stmt->bindValue(':nom', $Attaque->getNom());
        $stmt->bindValue(':description', $Attaque->getDescription());
        $stmt->bindValue(':dgt', $Attaque->getDegat());
        $stmt->bindValue('stl', $Attaque->getStyle());
        $stmt->bindValue(':type', $Attaque->getIdType());
        try {
            $stmt->execute();
            return $this->pdo->lastInsertId();
        } catch (PDOException $ex) {
            $newEx = wrapPDOException($ex);
            // var_dump($newEx);
            throw $newEx;
        }
    }

    function findById(int $id): ?Attaque
    {
        $stmt = $this->pdo->prepare("SELECT * FROM attaque  WHERE attaque.id_attaque = :id");
        $stmt->bindParam(':id', $id);
        $stmt->setFetchMode(PDO::FETCH_OBJ);
        $stmt->execute();
        $row = $stmt->fetch();
        if (! $row) {
            return NULL;
        }


        $Attaque = $this->createFromRow($row);


        return $Attaque;
    }

    function findAll()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM attaque ");
        $stmt->setFetchMode(PDO::FETCH_OBJ);
        $stmt->execute();
        $row = $stmt->fetch();
        if (! $row) {
            return NULL;
        }

        $Attaque = $this->createFromRow($row);


        return $Attaque;
    }

    function delete(int $id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM attaque WHERE id_attaque = :id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        if ($this->findById($id)) {
            return "Problème de Suppresion";
        }
        return "Suppression ok";
    }
    function update(IEntity $Attaque)
    {

        $stmt = $this->pdo->prepare("UPDATE attaque SET nom=:nom, description=:description, degat=:dgt, style=:stl,id_type=:type WHERE id_attaque=:idAttaque");
        $stmt->bindValue(':nom', $Attaque->getNom());
        $stmt->bindValue(':description', $Attaque->getDescription());
        $stmt->bindValue(':dgt', $Attaque->getDegat());
        $stmt->bindValue(':stl', $Attaque->getStyle());
        $stmt->bindValue(':type', $Attaque->getIdType());
        $stmt->bindValue(':idAttaque', $Attaque->getId(), PDO::PARAM_INT);
        $stmt->execute();
    }





    //______METHODE/REQUETE_SQL_______________________________________________________________________________________________________________________________________________________________  

}

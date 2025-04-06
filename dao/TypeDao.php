<?php
//______REQUIRE_______________________________________________________________________________________________________________________________________________________________

require_once(ROOT . "/utils/IDao.php");
require_once(ROOT . "/utils/AbstractDao.php");
require_once(ROOT . "/utils/BddSingleton.php");
require_once(ROOT . "/model/Type.php");
require_once(ROOT . "/utils/exceptions.php");
require_once(ROOT . "/utils/functions.php");
require_once(ROOT . "/exceptions/ConstraintUniqueException.php");
//______REQUIRE_______________________________________________________________________________________________________________________________________________________________



class TypeDao extends AbstractDao implements IDao
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
        $Type = new Type();
        $Type->setIdType(intval($row->id_type));
        $Type->setNom($row->nom);
        return $Type;
    }

    public function create($nom)
    {
        $Type = new Type();
        $Type->setNom($nom);
        return $Type;
    }

    function findById(int $id): ?Type
    {
        $stmt = $this->pdo->prepare("SELECT * FROM type t WHERE t.id_type = :id");
        $stmt->bindParam(':id', $id);
        $stmt->setFetchMode(PDO::FETCH_OBJ);
        $stmt->execute();
        $row = $stmt->fetch();
        if (! $row) {
            return NULL;
        }
        
        
        $Type = $this->createFromRow($row);


        return $Type;
    }
    

    function findAll()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM type ");
        $stmt->setFetchMode(PDO::FETCH_OBJ);
        $stmt->execute();
        $row = $stmt->fetch();
        if (! $row) {
            return NULL;
        }
        
        $Type = $this->createFromRow($row);


        return $Type;
    }


    function insert(IEntity $Type){
        var_dump($Type);
        $stmt=$this->pdo->prepare("INSERT INTO type (nom) VALUES ".
                            "(:nom)");
        $stmt->bindValue(':nom', $Type->getNom());
        
        try {
            $stmt->execute();
            return $this->pdo->lastInsertId();
        } catch (PDOException $ex) {
            $newEx = wrapPDOException($ex);
            // var_dump($newEx);
            throw $newEx;
        }
     }
    function delete(int $id) {
        $stmt=$this->pdo->prepare("DELETE FROM type WHERE id_type = :id");
        $stmt->bindParam(":id",$id);
        $stmt->execute();
        if($this->findById($id)){
            return "Problème de Suppresion";
        }
        return "Suppression ok";
    }
    function update(IEntity $Type)
    {

        $stmt = $this->pdo->prepare("UPDATE type SET nom=:nom WHERE id_type=:idtype");
        $stmt->bindValue(':nom', $Type->getNom());
        $stmt->bindValue(':idtype', $Type->getIdType(), PDO::PARAM_INT);
        $stmt->execute();
    }

    

    //______METHODE/REQUETE_SQL_______________________________________________________________________________________________________________________________________________________________  

}

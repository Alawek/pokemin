<?php
//______REQUIRE_______________________________________________________________________________________________________________________________________________________________

require_once(ROOT . "/utils/IDao.php");
require_once(ROOT . "/utils/AbstractDao.php");
require_once(ROOT . "/utils/BddSingleton.php");
require_once(ROOT . "/model/Role.php");
require_once(ROOT . "/utils/exceptions.php");
require_once(ROOT . "/utils/functions.php");
require_once(ROOT . "/exceptions/ConstraintUniqueException.php");
//______REQUIRE_______________________________________________________________________________________________________________________________________________________________



class RoleDao extends AbstractDao implements IDao
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
        $role = new Role();
        $role->setIdRole(intval($row->id_role));
        $role->setNom($row->nom);
        return $role;
    }

    public function create($nom)
    {
        $role = new Role();
        $role->setNom($nom);
        return $role;
    }

    function findById(int $id): ?Role
    {
        $stmt = $this->pdo->prepare("SELECT * FROM role r WHERE r.id_role = :id");
        $stmt->bindParam(':id', $id);
        $stmt->setFetchMode(PDO::FETCH_OBJ);
        $stmt->execute();
        $row = $stmt->fetch();
        if (! $row) {
            return NULL;
        }
        
        
        $role = $this->createFromRow($row);


        return $role;
    }
    

    function findAll()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM role ");
        $stmt->setFetchMode(PDO::FETCH_OBJ);
        $stmt->execute();
        $row = $stmt->fetch();
        if (! $row) {
            return NULL;
        }
        
        $role = $this->createFromRow($row);


        return $role;
    }


    function insert(IEntity $role){
        var_dump($role);
        $stmt=$this->pdo->prepare("INSERT INTO role (nom) VALUES ".
                            "(:nom)");
        $stmt->bindValue(':nom', $role->getNom());
        
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
        $stmt=$this->pdo->prepare("DELETE FROM role WHERE id_role = :id");
        $stmt->bindParam(":id",$id);
        $stmt->execute();
        if($this->findById($id)){
            return "Problème de Suppresion";
        }
        return "Suppression ok";
    }
    function update(IEntity $role)
    {

        $stmt = $this->pdo->prepare("UPDATE role SET nom=:nom WHERE id_role=:idrole");
        $stmt->bindValue(':nom', $role->getNom());
        $stmt->bindValue(':idrole', $role->getIdRole(), PDO::PARAM_INT);
        $stmt->execute();
    }

    //______METHODE/REQUETE_SQL_______________________________________________________________________________________________________________________________________________________________  

}

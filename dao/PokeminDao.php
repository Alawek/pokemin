<?php
//______REQUIRE_______________________________________________________________________________________________________________________________________________________________

require_once(ROOT . "/utils/IDao.php");
require_once(ROOT . "/utils/AbstractDao.php");
require_once(ROOT . "/utils/BddSingleton.php");
require_once(ROOT . "/model/Pokemin.php");
require_once(ROOT . "/utils/exceptions.php");
require_once(ROOT . "/utils/functions.php");
require_once(ROOT . "/exceptions/ConstraintUniqueException.php");
require_once(ROOT . "/dao/TypeDao.php");

//______REQUIRE_______________________________________________________________________________________________________________________________________________________________



class PokeminDao extends AbstractDao implements IDao
{

    //______ATTRIBUT_______________________________________________________________________________________________________________________________________________________________
    private $pdo;
    private TypeDao $typeDao;

    //______ATTRIBUT_______________________________________________________________________________________________________________________________________________________________


    //______CONSTRUCTEUR_______________________________________________________________________________________________________________________________________________________________
    function __construct()
    {
        $this->pdo = BddSingleton::getInstance()->getPDO();
        $this->typeDao=new TypeDao;
    }
    //______CONSTRUCTEUR_______________________________________________________________________________________________________________________________________________________________
    //______METHODE/REQUETE SQL_______________________________________________________________________________________________________________________________________________________________

    public function createFromRow($row)
    {
        $pokemin = new Pokemin();
        $pokemin->setId(intval($row->id_pokemin));
        $pokemin->setNom($row->nom);
        $pokemin->setDescription($row->description); // ICI
        $pokemin->setCri($row->cri);
        $pokemin->setEvolution1($row->evolution1);
        $pokemin->setNiveauEvolution1($row->niveau_evolution1);
        $pokemin->setEvolution2($row->evolution2);
        $pokemin->setNiveauEvolution2($row->niveau_evolution2);
        $pokemin->setTauxApparition($row->taux_apparition);
        $pokemin->setTauxCapture($row->taux_capture);
        $pokemin->setIdDon($row->id_don);
        $pokemin->setIdType1($row->id_type);
        $pokemin->setType1($this->typeDao->findById($row->id_type));
        $pokemin->setIdType2($row->id_type2);
        $pokemin->setType2($this->typeDao->findById($row->id_type));


        return $pokemin;
    }

    public function create($nom, $description, $cri, $evolution1 = NULL, $niveauevolution1 = NULL, $evolution2 = NULL, $niveauevolution2 = NULL, $tauxapparition = NULL, $tauxcapture = NULL,$don=NULL,$type1=NULL,$type2=NULL)
    {
        $pokemin = new Pokemin();
        $pokemin->setNom($nom);
        $pokemin->setDescription($description); // ICI
        $pokemin->setCri($cri);
        $pokemin->setEvolution1($evolution1);
        $pokemin->setNiveauEvolution1($niveauevolution1);
        $pokemin->setEvolution2($evolution2);
        $pokemin->setNiveauEvolution2($niveauevolution2);
        $pokemin->setTauxApparition($tauxapparition);
        $pokemin->setTauxCapture($tauxcapture);
        $pokemin->setIdDon($don);
        $pokemin->setIdType1($type1);
        $pokemin->setType1($this->typeDao->findById($type1));
        $pokemin->setIdType2($type2);
        $pokemin->setType2($this->typeDao->findById($type2));

        return $pokemin;
    }

    function insert(IEntity $pokemin)
    {
        var_dump($pokemin);
        $stmt = $this->pdo->prepare("INSERT INTO pokemin (nom,description,cri,evolution1,niveau_evolution1,evolution2,niveau_evolution2,taux_apparition,taux_capture,id_don,id_type,id_type2) VALUES " .
            "(:nom,:description,:cri,:ev1,:lvl1,:ev2,:lvl2,:txA,:txC,:don,:type,:type2  )");
        $stmt->bindValue(':nom', $pokemin->getNom());
        $stmt->bindValue(':description', $pokemin->getDescription());
        $stmt->bindValue(':cri', $pokemin->getCri());
        $stmt->bindValue(':ev1', $pokemin->getEvolution1());
        $stmt->bindValue(':lvl1', $pokemin->getNiveauEvolution1());
        $stmt->bindValue(':ev2', $pokemin->getEvolution2());
        $stmt->bindValue(':lvl2', $pokemin->getNiveauEvolution2());
        $stmt->bindValue(':txA', $pokemin->getTauxApparition());
        $stmt->bindValue(':txC', $pokemin->getTauxCapture());
        $stmt->bindValue(':don', $pokemin->getIdDon());
        $stmt->bindValue(':type', $pokemin->getIdType1());
        $stmt->bindValue(':type2', $pokemin->getIdType2());
        try {
            $stmt->execute();
            return $this->pdo->lastInsertId();
        } catch (PDOException $ex) {
            $newEx = wrapPDOException($ex);
            // var_dump($newEx);
            throw $newEx;
        }
    }

    function findById(int $id): ?Pokemin
    {
        $stmt = $this->pdo->prepare("SELECT * FROM pokemin  WHERE pokemin.id_pokemin = :id");
        $stmt->bindParam(':id', $id);
        $stmt->setFetchMode(PDO::FETCH_OBJ);
        $stmt->execute();
        $row = $stmt->fetch();
        if (! $row) {
            return NULL;
        }


        $pokemin = $this->createFromRow($row);


        return $pokemin;
    }

    function findAll()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM pokemin ");
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
        $stmt = $this->pdo->prepare("DELETE FROM pokemin WHERE id_pokemin = :id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        if ($this->findById($id)) {
            return "Problème de Suppresion";
        }
        return "Suppression ok";
    }
    function update(IEntity $pokemin)
    {

        $stmt = $this->pdo->prepare("UPDATE pokemin SET nom=:nom, description=:description, cri=:cri, evolution1=:ev1,niveau_evolution1=:lvl1,evolution2=:ev2,niveau_evolution2=:lvl2,taux_apparition=:txA,taux_capture=:txC,id_don=:don,id_type=:type1,id_type2=:type2 WHERE id_pokemin=:idpokemin");
        $stmt->bindValue(':nom', $pokemin->getNom());
        $stmt->bindValue(':description', $pokemin->getDescription());
        $stmt->bindValue(':cri', $pokemin->getCri());
        $stmt->bindValue(':ev1', $pokemin->getEvolution1());
        $stmt->bindValue(':lvl1', $pokemin->getNiveauEvolution1());
        $stmt->bindValue(':ev2', $pokemin->getEvolution2());
        $stmt->bindValue(':lvl2', $pokemin->getNiveauEvolution2());
        $stmt->bindValue(':txA', $pokemin->getTauxApparition());
        $stmt->bindValue(':txC', $pokemin->getTauxCapture());
        $stmt->bindValue(':don', $pokemin->getIdDon());
        $stmt->bindValue(':type1', $pokemin->getIdType1());
        $stmt->bindValue(':type2', $pokemin->getIdType2());
        $stmt->bindValue(':idpokemin', $pokemin->getId(), PDO::PARAM_INT);
        $stmt->execute();
    }





    //______METHODE/REQUETE_SQL_______________________________________________________________________________________________________________________________________________________________  

}

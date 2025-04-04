<?php
//______REQUIRE_______________________________________________________________________________________________________________________________________________________________
require_once(ROOT . "/utils/IService.php");
require_once(ROOT . "/utils/AbstractService.php");
require_once(ROOT . "/utils/IDao.php");
require_once(ROOT . "/dao/AttaqueDao.php");
require_once(ROOT . "/model/Attaque.php");
//______REQUIRE_______________________________________________________________________________________________________________________________________________________________



class AttaqueService extends AbstractService implements IService
{
    //______ATTRIBUT_______________________________________________________________________________________________________________________________________________________________

    private AttaqueDao $dao;

    //______ATTRIBUT_______________________________________________________________________________________________________________________________________________________________
    //______CONSTRUCTEUR_______________________________________________________________________________________________________________________________________________________________

    function __construct()
    {
        $this->dao = new AttaqueDao();
    }
    //______CONSTRUCTEUR_______________________________________________________________________________________________________________________________________________________________

    //______METHODE_______________________________________________________________________________________________________________________________________________________________

    function getDao(): IDao
    { // Définie dans la classe abstraite 
        return $this->dao;
    }

    function insert(IEntity $c)
    {
        //Code métier, un compte est forcément un rédacteur
        return $this->dao->insert($c);
    }


    public function findById(int $id): ?Attaque
    {
        return $this->dao->findById($id);
    }

    public function delete(int $id){
        return $this->dao->delete($id);
    }

    public function update(IEntity $Attaque){
        return $this->dao->update($Attaque);
    }

    
    //______METHODE_______________________________________________________________________________________________________________________________________________________________

}

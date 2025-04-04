<?php
//______REQUIRE_______________________________________________________________________________________________________________________________________________________________
require_once(ROOT . "/utils/IService.php");
require_once(ROOT . "/utils/AbstractService.php");
require_once(ROOT . "/utils/IDao.php");
require_once(ROOT . "/dao/AttaquePokeminDao.php");
require_once(ROOT . "/model/AttaquePokemin.php");
//______REQUIRE_______________________________________________________________________________________________________________________________________________________________



class AttaquePokeminService extends AbstractService implements IService
{
    //______ATTRIBUT_______________________________________________________________________________________________________________________________________________________________

    private AttaquePokeminDao $dao;

    //______ATTRIBUT_______________________________________________________________________________________________________________________________________________________________
    //______CONSTRUCTEUR_______________________________________________________________________________________________________________________________________________________________

    function __construct()
    {
        $this->dao = new AttaquePokeminDao();
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


    public function findById(int $idAttaque): ?AttaquePokemin
    {
        return $this->dao->findById($idAttaque);
    }

    public function findAllByInstance(int $idInstance): array {
        return $this->dao->findAllByInstance($idInstance);
    }
    
    function findAttaque(int $idAttaque, int $idInstance): ?AttaquePokemin
    {
        return $this->dao->findAttaque($idAttaque,$idInstance);
    }
    

    public function delete(int $idAttaque,){
        return $this->dao->delete($idAttaque);
    }

    public function update(IEntity $attaquePokemin){
        return $this->dao->update($attaquePokemin);
    }

    function deleteAttaque(int $idAttaque, int $idInstance)
    {
        return $this->dao->deleteAttaque($idAttaque,$idInstance);
    }

    
    //______METHODE_______________________________________________________________________________________________________________________________________________________________

}

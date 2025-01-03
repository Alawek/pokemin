<?php
require_once(ROOT ."/utils/IDao.php");
require_once(ROOT . "/utils/IEntity.php");
// Définit les opérations de base pour tout service
interface IService{
    function findAll();

    function findById(int $id) : ?IEntity;

    function getDao() : IDao;

    function insert(IEntity $entity);

    function delete(int $id);

    function update(IEntity $entity);
}

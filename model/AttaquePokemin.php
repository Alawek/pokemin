<?php
//______REQUIRE_______________________________________________________________________________________________________________________________________________________________

require_once(ROOT . "/utils/IEntity.php");
require_once(ROOT . "/utils/AbstractEntity.php");
//______REQUIRE_______________________________________________________________________________________________________________________________________________________________

class AttaquePokemin extends AbstractEntity implements IEntity
{
    //______ATTRIBUT_______________________________________________________________________________________________________________________________________________________________
    private int $idAttaque;    
    private int $idPokemin;
    private int $mana;
    private bool $active;
    private int $niveau;
    private ?PokeminInstance $pokeminInstance;
    private ?Attaque $attaque;
    //______ATTRIBUT_______________________________________________________________________________________________________________________________________________________________

    //______CONSTRUCTEUR_______________________________________________________________________________________________________________________________________________________________

    function __construct()
    { /* RAS */
    }
    //______CONSTRUCTEUR_______________________________________________________________________________________________________________________________________________________________

    //______GETTER/SETTER_______________________________________________________________________________________________________________________________________________________________

    function getIdAttaque(): int
    {
        return $this->idAttaque;
    }

    function setIdAttaque(int $id)
    {
        $this->idAttaque = $id;
    }

    function getIdPokemin(): int
    {
        return $this->idPokemin;
    }

    function setIdPokemin(string $idPokemin)
    {
        $this->idPokemin = $idPokemin;
    }

    function getMana(): int
    {
        return $this->mana;
    }

    function setMana(int $mana)
    {
        $this->mana = $mana;
    }

    function isActive(): bool
    {
        return $this->active;
    }

    function setActive( bool $active)
    {
        $this->active = $active;
    }


    function getNiveau(): int
    {
        return $this->niveau;
    }

    function setNiveau(int $niveau)
    {
        $this->niveau = $niveau;
    }

    function getPokeminInstance() : ?PokeminInstance {
        return $this->pokeminInstance;
    }

    function setPokeminInstance(PokeminInstance $PokeminInstance) {
        $this->pokeminInstance = $PokeminInstance;
    }

    function getAttaque() : ?Attaque {
        return $this->attaque;
    }

    function setAttaque(Attaque $Attaque) {
        $this->attaque = $Attaque;
    }
   

   


    //______GETTER/SETTER_______________________________________________________________________________________________________________________________________________________________
    //______METHODE_______________________________________________________________________________________________________________________________________________________________
    //______METHODE_______________________________________________________________________________________________________________________________________________________________
    //______METHODE STATIC_______________________________________________________________________________________________________________________________________________________________	
    // //______METHODE STATIC_______________________________________________________________________________________________________________________________________________________________




}
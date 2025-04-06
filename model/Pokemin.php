<?php
//______REQUIRE_______________________________________________________________________________________________________________________________________________________________

require_once(ROOT . "/utils/IEntity.php");
require_once(ROOT . "/utils/AbstractEntity.php");
//______REQUIRE_______________________________________________________________________________________________________________________________________________________________

class Pokemin extends AbstractEntity implements IEntity
{
    //______ATTRIBUT_______________________________________________________________________________________________________________________________________________________________
    private int $idPokemin;
    private string $nom;
    private string $description;
    private string $cri;
    private ?int $evolution1;
    private ?int $niveauEvolution1;
    private ?int $evolution2;
    private ?int $niveauEvolution2;
    private ?int $tauxApparition;
    private ?int $tauxCapture;
    private ?int $idDon;
    private int $idType1;
    private ?Type $type1;
    private ?int $idType2;
    private ?Type $type2;

    //______ATTRIBUT_______________________________________________________________________________________________________________________________________________________________

    //______CONSTRUCTEUR_______________________________________________________________________________________________________________________________________________________________

    function __construct()
    { /* RAS */
    }
    //______CONSTRUCTEUR_______________________________________________________________________________________________________________________________________________________________

    //______GETTER/SETTER_______________________________________________________________________________________________________________________________________________________________

    function getId(): int
    {
        return $this->idPokemin;
    }

    function setId(int $id)
    {
        $this->idPokemin = $id;
    }

    function getNom(): string
    {
        return $this->nom;
    }

    function setNom(string $nom)
    {
        $this->nom = $nom;
    }

    function getDescription(): string
    {
        return $this->description;
    }

    function setDescription(string $description)
    {
        $this->description = $description;
    }

    function getCri(): string
    {
        return $this->cri;
    }

    function setCri(string $cri)
    {
        $this->cri = $cri;
    }


    function getEvolution1(): ?int
    {
        return $this->evolution1;
    }

    function setEvolution1(?int $evolution1)
    {
        $this->evolution1 = $evolution1;
    }

    function getNiveauEvolution1(): ?int
    {
        return $this->niveauEvolution1;
    }

    function setNiveauEvolution1(?int $niveauEvolution1)
    {
        $this->niveauEvolution1 = $niveauEvolution1;
    }

    function getEvolution2(): ?int
    {
        return $this->evolution2;
    }

    function setEvolution2(?int $evolution2)
    {
        $this->evolution2 = $evolution2;
    }

    function getNiveauEvolution2(): ?int
    {
        return $this->niveauEvolution2;
    }

    function setNiveauEvolution2(?int $niveauEvolution2)
    {
        $this->niveauEvolution2 = $niveauEvolution2;
    }

    function getTauxCapture(): ?int
    {
        return $this->tauxCapture;
    }

    function setTauxCapture(?int $tauxcapture)
    {
        $this->tauxCapture = $tauxcapture;
    }

    function getTauxApparition(): ?int
    {
        return $this->tauxApparition;
    }

    function setTauxApparition(?int $tauxapparition)
    {
        $this->tauxApparition = $tauxapparition;
    }
    function getIdDon(): ?int
    {
        return $this->idDon;
    }

    function setIdDon(?int $don)
    {
        $this->idDon = $don;
    }

    function getIdType1(): ?int
    {
        return $this->idType1;
    }

    function setIdType1(?int $type1)
    {
        $this->idType1 = $type1;
    }

    function getIdType2(): ?int
    {
        return $this->idType2;
    }

    function setIdType2(?int $type2)
    {
        $this->idType2 = $type2;
    }

    function getType1() : ?Type {
        return $this->type1;
    }

    function setType1(Type $Type) {
        $this->type1 = $Type;
    }

    function getType2() : ?Type {
        return $this->type2;
    }

    function setType2(Type $Type) {
        $this->type2 = $Type;
    }
    //______GETTER/SETTER_______________________________________________________________________________________________________________________________________________________________
    //______METHODE_______________________________________________________________________________________________________________________________________________________________
    //______METHODE_______________________________________________________________________________________________________________________________________________________________
    //______METHODE STATIC_______________________________________________________________________________________________________________________________________________________________	
    // //______METHODE STATIC_______________________________________________________________________________________________________________________________________________________________




}

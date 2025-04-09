<?php
require_once(ROOT . "/dao/PokeminInstanceDao.php");
require_once(ROOT . "/dao/AttaqueDao.php");
require_once(ROOT . "/dao/AttaquePokeminDao.php");

class CombatService
{
    private PokeminInstanceDao $pokeminInstanceDao;
    private AttaqueDao $attaqueDao;
    private AttaquePokeminDao $attaquePokeminDao;
    

    public function __construct()
    {
        $this->pokeminInstanceDao = new PokeminInstanceDao();
        $this->attaqueDao = new AttaqueDao();
        $this->attaquePokeminDao = new AttaquePokeminDao();
    }

    public function executerAttaque(int $idAttaque, int $idLanceur, int $idCible): array
    {
        $attaque = $this->attaqueDao->findById($idAttaque);
        $lanceur = $this->pokeminInstanceDao->findById($idLanceur);
        $cible = $this->pokeminInstanceDao->findById($idCible);
        $attaquePokemin = $this->attaquePokeminDao->findAttaque($idAttaque,$idLanceur);

        if ($lanceur->getMana() < $attaquePokemin->getMana()) {
            throw new Exception("Pas assez de mana.");
        }

        $degats = $this->calculDegats($attaque->getDegat(), $lanceur->getIntelligence());
        $nouveauPvCible = max(0, $cible->getPv() - $degats);
        $nouveauManaLanceur = $lanceur->getMana() - $attaquePokemin->getMana();

        $cible->setPv($nouveauPvCible);
        $lanceur->setMana($nouveauManaLanceur);

        $this->pokeminInstanceDao->update($cible);
        $this->pokeminInstanceDao->update($lanceur);

        $resultat = [
            "attaque" => [
                "attaquant" => $lanceur->getNom(),
                "cible" => $cible->getNom(),
                "attaqueNom" => $attaque->getNom(),
                "degatsInfliges" => $degats,
                "manaRestant" => $nouveauManaLanceur,
                "pvRestant" => $nouveauPvCible,
                "cibleKO" => $nouveauPvCible === 0
            ]
        ];

        // 💥 Si la cible est encore en vie, riposte automatique
        if ($nouveauPvCible > 0) {
            $riposte = $this->executerRiposte($cible, $lanceur);
            if ($riposte !== null) {
                $resultat["riposte"] = $riposte;
            }
        }

        return $resultat;
    }

    private function executerRiposte($cible, $lanceur): ?array
    {
        $attaques = $this->attaquePokeminDao->findAllByInstance($cible->getIdInstance());

        if (empty($attaques)) return null;

        // Choix aléatoire d'une attaque parmi celles disponibles
        $attaqueRiposte = $attaques[array_rand($attaques)];

        if ($cible->getMana() < $attaqueRiposte->getMana()) {
            return [
                "message" => "{$cible->getNom()} voulait riposter mais n'avait pas assez de mana."
            ];
        }

        $degatsRiposte = $this->calculDegats($attaqueRiposte->getAttaque()->getDegat(), $cible->getIntelligence());
        $nouveauPvLanceur = max(0, $lanceur->getPv() - $degatsRiposte);
        $manaRestantCible = $cible->getMana() - $attaqueRiposte->getMana();

        $lanceur->setPv($nouveauPvLanceur);
        $cible->setMana($manaRestantCible);

        $this->pokeminInstanceDao->update($lanceur);
        $this->pokeminInstanceDao->update($cible);

        return [
            "attaquant" => $cible->getNom(),
            "cible" => $lanceur->getNom(),
            "attaqueNom" => $attaqueRiposte->getAttaque()->getNom(),
            "degatsInfliges" => $degatsRiposte,
            "manaRestant" => $manaRestantCible,
            "pvRestant" => $nouveauPvLanceur,
            "cibleKO" => $nouveauPvLanceur === 0
        ];
    }

    private function calculDegats(int $degatsDeBase, int $intelligence): int
    {
        return round($degatsDeBase * (1 + $intelligence / 20));
    }
}

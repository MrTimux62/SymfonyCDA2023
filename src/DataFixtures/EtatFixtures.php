<?php

namespace App\DataFixtures;

use App\Entity\Etat;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class EtatFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $libelles = ['Créée', 'Ouverte', 'Clôturée', 'Activité en cours', 'Passée', 'Annulée'];

        for ($i = 0; $i < count($libelles); $i++) {
            $etat = new Etat();
            $etat->setLibelle($libelles[$i]);
            $manager->persist($etat);
        }

        $manager->flush();
    }
}

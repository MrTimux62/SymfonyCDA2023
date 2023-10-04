<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\Participant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class CampusFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        $noms = ['Ecole Centrale de Marseille ',
            'Ecole Centrale de Nantes',
            'Ecole centrales des Arts et Manufactures',
            'Ecole des Mines d’Albi-Carmaux',
            'Ecole de Management Leonard de Vinci',
            'Ecole Nationale d’Aviation Civile',
            'Ecole Supérieur de Commerce de Pau',
            'Ecole d’ingénieurs en agriculture',
            'Ecole des Hautes Etudes Commerciales',
            'Ecole Nationale Supérieure des Mines de Douai'];

        for ($i = 0; $i < count($noms); $i++) {
            $campus = new Campus();
            $campus->setNom($noms[$i]);
            $manager->persist($campus);
            // 20 participants par campus
            for ($i2 = 0; $i2 < 20; $i2++) {
                $participant = new Participant();
                $participant->setNom($faker->lastName);
                $participant->setPrenom($faker->firstName);
                $participant->setCampus($campus);
                $participant->setPseudo($faker->userName);
                $participant->setEmail($faker->email);
                $participant->setIsActif(1);
                $participant->setPassword(password_hash('test123',0));
                $participant->setRoles(['ROLE_USER']);
                $participant->setTelephone($faker->phoneNumber);
                $manager->persist($participant);
            }
        }

        $manager->flush();
    }
}

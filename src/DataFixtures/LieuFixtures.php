<?php

namespace App\DataFixtures;

use App\Entity\Lieu;
use App\Entity\Ville;
use App\Repository\VilleRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class LieuFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $faker = Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 20; $i++) {

            $lieu = new Lieu();
            $ville = new Ville();

            $ville->setNom($faker->city);
            $ville->setCodePostal(intval($faker->postcode));

            $lieu->setVille($ville);
            $lieu->setRue($faker->streetName);
            $lieu->setLatitude($faker->latitude);
            $lieu->setLongitude($faker->longitude);
            $lieu->setNom($faker->streetAddress);

            $manager->persist($ville);
            $manager->persist($lieu);
        }

        $manager->flush();
    }
}

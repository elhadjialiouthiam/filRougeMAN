<?php

namespace App\DataFixtures;

use App\Entity\Profil;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ProfilFixtures extends Fixture
{
    public const PROFIL = 'admin';
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        // configurer la langue
        $tab = ['admin','formateur','CM','apprenant'];
        // $tab = implode(",", $tab);
        for ($p=0; $p < 4; $p++) { 
            $profil = new Profil();
            $this->setReference(self::PROFIL, $profil);
            // profiles
            $profil->setLibelle($tab[$p]);

            // persist
            $manager->persist($profil);
        }

        $manager->flush();
    }
}

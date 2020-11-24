<?php

namespace App\DataFixtures;

use App\Entity\Profil;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ProfilFixtures extends Fixture
{
    public const PROFIL_admin = 'admin';
    public const PROFIL_apprenant = 'apprenant';
    public const PROFIL_formateur = 'formateur';
    public const PROFIL_cm = 'CM';
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        // configurer la langue
        $tab = ['admin','formateur','CM','apprenant'];

        for ($p=0; $p < count($tab); $p++) { 
            $profil = new Profil();
            // profiles
            $profil->setLibelle($tab[$p]);
            if($tab[$p]=='admin'){
                $this->addReference(self::PROFIL_admin,$profil);
            }
            elseif($tab[$p]=='apprenant'){
                $this->addReference(self::PROFIL_apprenant,$profil);

            }
            elseif($tab[$p]=='formateur'){
                $this->addReference(self::PROFIL_formateur,$profil);

            }
            elseif($tab[$p]=='CM'){
                $this->addReference(self::PROFIL_cm,$profil);

            }

            // persist
            $manager->persist($profil);
        }

        $manager->flush();
    }
}

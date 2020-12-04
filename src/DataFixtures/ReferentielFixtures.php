<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Referentiel;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ReferentielFixtures extends Fixture
{
    public static function getReferenceKey($r){
        return sprintf('Referentiel_%s',$r);
    }
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');       
        for ($r=1; $r <13; $r++) { 
            $Referentiel = new Referentiel();
            $Referentiel->setlibelle('libelle_'.$r)
                       ->setPresentation($faker->text)
                       ->setProgramme($faker->text)
                       ->setCritereAdmision($faker->text)
                       ->setCritereEvaluation($faker->text);
            $this->addReference(self::getReferenceKey($r),$Referentiel);
            // persist
            $manager->persist($Referentiel);
        }
        $manager->flush();
    }
}

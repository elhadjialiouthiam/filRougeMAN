<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Niveau;
use App\Entity\Competence;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class CompetencesFixtures extends Fixture
{
    public static function getReferenceKey($p){
        return sprintf('Competence_%s',$p);
    }
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');       
        for ($p=1; $p <13; $p++) { 
            $Competence = new Competence();
            $Competence->setlibelle('libelle_'.$p)
                       ->setDescriptif($faker->text);
            $this->addReference(self::getReferenceKey($p),$Competence);
            // persist
            $manager->persist($Competence);
            for ($n=1; $n <=3; $n++) { 
                $Niveau = new Niveau();
                $Niveau->setlibelle('niveau_'.$n)
                       ->setCritereEvaluation($faker->realtext())
                       ->setGroupeAction($faker->realtext())
                       ->setCompetence($Competence);
                // persist
                $manager->persist($Niveau);
            }

            
        }
        $manager->flush();
    }
    
}

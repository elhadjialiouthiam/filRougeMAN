<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\GroupeDeCompetence;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class GroupeDeCompetencesFixtures extends Fixture implements DependentFixtureInterface
{
    
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');  
        for($g=1;$g<=12;$g++){
            $competence[]=$this->getReference(CompetencesFixtures::getReferenceKey($g));
            $referentiel[]=$this->getReference(ReferentielFixtures::getReferenceKey($g));
        }
        for($c=1;$c<=4;$c++){
            $groupeDeCompetence=new GroupeDeCompetence;
            $groupeDeCompetence->setLibelle($faker->text)
                               ->setDescriptif($faker->realtext());
            for($i=1;$i<=3;$i++){
                $groupeDeCompetence->addCompetence($faker->unique(true)->randomElement($competence));
                $groupeDeCompetence->addReferentiel($faker->unique(true)->randomElement($referentiel));
            }
            $manager->persist($groupeDeCompetence);

        }
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
    public function getDependencies(){
        return array(CompetencesFixtures::class,);
        return array(ReferentielFixtures::class,);
    }
}

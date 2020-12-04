<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Groupe;
use App\Repository\ApprenantRepository;
use App\Repository\FormateurRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Migrations\Exception\DependencyException;

class GroupeFixtures extends Fixture implements DependentFixtureInterface
{
    private $apprenantRepository;
    private $formateurRepository;
    
    public function __construct(ApprenantRepository $apprenantRepository, FormateurRepository $formateurRepository){
        $this->apprenantRepository=$apprenantRepository;
        $this->formateurRepository=$formateurRepository;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        $apprenantAll=$this->apprenantRepository->findAll();
        $formateurAll=$this->formateurRepository->findAll();
           
        for ($g=1; $g <=10; $g++) { 

            $Groupe = new Groupe();
            $Groupe->setNom('Groupe'.$g)
                       ->setDateCreation($faker->datetime)
                       ->setType($faker->randomElement(['binome','fileRouge']));
            for($app=1;$app<=4;$app++){

                foreach ($apprenantAll as $apprenant){

                    $Groupe->addApprenant($apprenant);
            
                        }
            }

            for($form=1;$form<4;$form++){

                foreach($formateurAll as $formateur){

                   $Groupe->addFormateur($formateur);
                }

                
            }
            // persist
            $manager->persist($Groupe);
        }
        $manager->flush();
    }

    public function getDependencies(){
        return array(
            ApprenantFixtures::class,FormateurFixtures::class,
        );
    }
}

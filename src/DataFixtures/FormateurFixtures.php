<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Formateur;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class FormateurFixtures extends Fixture implements DependentFixtureInterface
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager)
    {

        $faker = Factory::create('fr_FR');

        for($a=1;$a<=4;$a++){
            $Formateur = new Formateur();
            $harsh = $this->encoder->encodePassword($Formateur, 'password');
            // Formateur
            $Formateur->setPrenom($faker->firstname);
            $Formateur->setNom($faker->lastname);
            $Formateur->setAvatar($faker->imageUrl());
            $Formateur->setPassword($harsh);
            $Formateur->setEmail($faker->email);
            $Formateur->setProfil($this->getReference(ProfilFixtures::PROFIL_formateur));
            $manager->persist($Formateur);
            
        }
            // persist
            $manager->flush();
               
}
public function getDependencies(){
    return array(
        UserFixtures::class,
    );
}
}

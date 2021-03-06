<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Apprenant;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ApprenantFixtures extends Fixture implements DependentFixtureInterface
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager)
    {

        $faker = Factory::create('fr_FR');

        for($a=1;$a<=100;$a++){
            $apprenant = new Apprenant();
            $harsh = $this->encoder->encodePassword($apprenant, 'password');
            // apprenant
            $apprenant->setPrenom($faker->firstname);
            $apprenant->setNom($faker->lastname);
            $apprenant->setAvatar($faker->imageUrl());
            $apprenant->setPassword($harsh);
            $apprenant->setEmail($faker->email);
            $apprenant->setProfil($this->getReference(ProfilFixtures::PROFIL_apprenant));
            $manager->persist($apprenant);
            
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

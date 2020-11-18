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
        // $product = new Product();
        // $manager->persist($product);
            $faker = Factory::create('fr_FR');
            $formateur = new Formateur();
            $harsh = $this->encoder->encodePassword($formateur, 'password');
            // formateur
            $formateur->setPrenom($faker->firstname);
            $formateur->setNom($faker->lastname);
            $formateur->setPassword($harsh);
            $formateur->setEmail($faker->email);
            $formateur->setProfil($this->getReference(ProfilFixtures::PROFIL_formateur));
            //$formateur->setProfil($this->getReference($p));
            // persist
            $manager->persist($formateur);
            $manager->flush();
}
public function getDependencies(){
    return array(
        UserFixtures::class,
    );
}
}

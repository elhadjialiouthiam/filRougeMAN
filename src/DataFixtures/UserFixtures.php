<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
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
        // configurer la langue
        $faker = Factory::create('fr_FR');
        for ($p=0; $p < 3; $p++) { 
            $users = new User();
            $harsh = $this->encoder->encodePassword($users, 'password');
            // users
            $users->setPrenom($faker->firstname);
            $users->setNom($faker->lastname);
            $users->setPassword($harsh);
            $users->setEmail($faker->email);
            $users->setProfil($this->getReference(ProfilFixtures::PROFIL));

            // persist
            $manager->persist($users);
        }

        $manager->flush();
    }
}

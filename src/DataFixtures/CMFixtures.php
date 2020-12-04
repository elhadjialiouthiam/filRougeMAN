<?php

namespace App\DataFixtures;

use App\Entity\CM;
use Faker\Factory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CMFixtures extends Fixture implements DependentFixtureInterface
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
            $CM = new CM();
            $harsh = $this->encoder->encodePassword($CM, 'password');
            // CM
            $CM->setPrenom($faker->firstname);
            $CM->setNom($faker->lastname);
            $CM->setAvatar($faker->imageUrl());
            $CM->setPassword($harsh);
            $CM->setEmail($faker->email);
            $CM->setProfil($this->getReference(ProfilFixtures::PROFIL_cm));
            //$CM->setProfil($this->getReference($p));
            // persist
            $manager->persist($CM);
            $manager->flush();
}
public function getDependencies(){
    return array(
        UserFixtures::class,
    );
}
}

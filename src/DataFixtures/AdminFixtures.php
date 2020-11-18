<?php

namespace App\DataFixtures;

use App\Entity\Admin;
use Faker\Factory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminFixtures extends Fixture implements DependentFixtureInterface
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
            $admin = new Admin();
            $harsh = $this->encoder->encodePassword($admin, 'password');
            // admin
            $admin->setPrenom($faker->firstname);
            $admin->setNom($faker->lastname);
            $admin->setPassword($harsh);
            $admin->setEmail($faker->email);
            $admin->setProfil($this->getReference(ProfilFixtures::PROFIL_admin));
            //$admin->setProfil($this->getReference($p));
            // persist
            $manager->persist($admin);
            $manager->flush();
    
}
public function getDependencies(){
    return array(
        ProfilFixtures::class,
    );
}

}
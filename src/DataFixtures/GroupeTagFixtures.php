<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\GroupeTag;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class GroupeTagFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');  
        for($g=1;$g<=12;$g++){
            $tag[]=$this->getReference(TagFixtures::getReferenceKey($g));

        }
        for($c=1;$c<=4;$c++){
            $groupetag=new GroupeTag;
            $groupetag->setLibelle($faker->text);
            for($i=1;$i<=3;$i++){
                $groupetag->addTag($faker->unique(true)->randomElement($tag));
            }
            $manager->persist($groupetag);

        }
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
    public function getDependencies(){
        return array(TagFixtures::class,);
    }
}

<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Tag;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class TagFixtures extends Fixture
{
    public static function getReferenceKey($t){
        return sprintf('Tag%s',$t);
    }
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');       
        for ($t=1; $t <13; $t++) { 
            $tag = new Tag();
            $tag->setlibelle('libelle_'.$t)
                       ->setDescriptif($faker->text);
            $this->addReference(self::getReferenceKey($t),$tag);
            // persist
            $manager->persist($tag);
            
        }
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}

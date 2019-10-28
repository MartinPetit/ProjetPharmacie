<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Faker\Factory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr-FR');

        for ($i = 1; $i <= 15; $i++) {
            $product = new Product;

            $name = $faker->word();
            $image = $faker->imageUrl(1000,350);
            $brand = $faker->word();
            $description = $faker->sentence();
            $content = '<p>' . join('</p><p>', $faker->paragraphs(4)) . '</p>';
            

            $product->setName($name)
                ->setImage($image)
                ->setBrand($brand)
                ->setPrice(mt_rand(5,200))
                ->setDescription($description)
                ->setContent($content);
                


            $manager->persist($product);
        }

        $manager->flush();
    }
}

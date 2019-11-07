<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Product;
use App\Entity\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder) {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr-FR');

        $adminRole = new Role();
        $adminRole->setTitle('ROLE_ADMIN');
        $manager->persist($adminRole);

        $adminUser = new User();
        $adminUser->setFirstName('Martin')
                  ->setLastName('Petit')
                  ->setEmail('martinpetit1998@gmail.com')
                  ->setHash($this->encoder->encodePassword($adminUser, 'Martin92'))
                  ->addUserRole($adminRole);

        $manager->persist($adminUser);
        
        //Gestion des utilisateurs

        $users = [];

        for ($i = 1; $i<=10; $i++) {
            $user = new User();

            $hash = $this->encoder->encodePassword($user,'password');

            $user->setFirstName($faker->firstname)
                 ->setLastName($faker->lastname)
                 ->setEmail($faker->email)
                 ->setHash($hash);

            $manager->persist($user);
            $users[] = $user;


        }
        
        
        
        
        //Gestion des produits 

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

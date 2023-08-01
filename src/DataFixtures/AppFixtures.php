<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Item;
use App\Entity\Build;
use Faker\Factory as FakerFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $faker;

    public function __construct()
    {
        $this->faker = FakerFactory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        // Obtenir les noms des images du repertoire public/images
        $images = scandir('public/images');

        // Items
        $items = [];
        for ($i = 1; $i < 31; $i++) {
            $item = new Item();
            // Ajouter des images aléatoire de mon repertoire public/images
            $item->setImage($images[mt_rand(2, count($images) - 1)]);
            $item->setName('Item ' . $i);
            $item->setPrice(mt_rand(1000, 4000));

            $items[] = $item;
            $manager->persist($item);
        }

        // Builds
        for ($j = 1; $j < 10; $j++) {
            $build = new Build();
            $build->setName('Build ' . $j);
            $build->setDifficulty(mt_rand(1, 5));
            $build->setPrice(mt_rand(10000, 40000));
            $build->setIsFavorite(mt_rand(0, 1) == 1 ? true : false);

            // Melanger les items
            shuffle($items);
            
            // Ajouter les 5 premiers éléments mélangés à la panoplie
            for ($k = 0; $k < 5; $k++) {
                $item = $items[$k];
                $build->addItem($item);
            }
            $manager->persist($build);
        }

        // Users
        for ($l = 1; $l < 10; $l++) {
            $user = new User();
            $user->setFullName($this->faker->name())
                 ->setPseudo(mt_rand(0, 1) == 1 ? $this->faker->firstName() : null)
                 ->setEmail($this->faker->email())
                 ->setRoles(['ROLE_USER'])
                 ->setPlainPassword('password');

            $manager->persist($user);
        }

        $manager->flush();
    }
}

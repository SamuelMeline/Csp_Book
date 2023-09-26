<?php

namespace App\DataFixtures;

use App\Entity\Item;
use App\Entity\Mark;
use App\Entity\User;
use App\Entity\Build;
use Faker\Factory as FakerFactory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
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
        // Users
        $users = [];

        // Admin
        $admin = new User();
        $admin->setFullName('Admin')
            ->setEmail('admin@cspbook.com')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER'])
            ->setPlainPassword('passwordAdmin');

        $users[] = $admin;
        $manager->persist($admin);

        for ($l = 1; $l < 10; $l++) {
            $user = new User();
            $user->setFullName($this->faker->name())
                ->setPseudo(mt_rand(0, 1) == 1 ? $this->faker->firstName() : null)
                ->setEmail($this->faker->email())
                ->setRoles(['ROLE_USER'])
                ->setPlainPassword('password');

            $users[] = $user;
            $manager->persist($user);
        }

        // Obtenir les noms des images du répertoire public/images
        $images = scandir('public/images/items');

        // Items
        $items = [];

        for ($i = 1; $i < 31; $i++) {
            $item = new Item();

            // Sélectionnez une image aléatoire qui n'existe pas déjà dans la base de données
            do {
                $randomImage = $images[mt_rand(2, count($images) - 1)];
            } while (in_array($randomImage, $items));

            // Obtenez le nom de l'item à partir du nom du fichier image
            $itemName = pathinfo($randomImage, PATHINFO_FILENAME);

            $item->setImage($randomImage);
            $item->setName($itemName); // Utilisez le nom de l'item à partir du nom du fichier image
            $item->setPrice(mt_rand(1000, 4000));
            $item->setUser($users[mt_rand(0, count($users) - 1)]);

            $items[] = $item;
            $manager->persist($item);
        }

        // Builds
        $builds = [];
        for ($j = 1; $j < 10; $j++) {
            $build = new Build();
            $build->setName('Build ' . $j);
            $build->setPrice(mt_rand(10000, 40000));
            $build->setIsPublic(mt_rand(0, 1) == 1 ? true : false);
            $build->setUser($users[mt_rand(0, count($users) - 1)]);

            // Melanger les items
            shuffle($items);

            // Ajouter les 5 premiers éléments mélangés à la panoplie
            for ($k = 0; $k < 5; $k++) {
                $item = $items[$k];
                $build->addItem($item);
            }

            $builds[] = $build;
            $manager->persist($build);
        }

        //Marks
        foreach ($builds as $build) {
            for ($m = 0; $m < mt_rand(0, 4); $m++) {
                $mark = new Mark();
                $mark->setMark(mt_rand(1, 5));
                $mark->setUser($users[mt_rand(0, count($users) - 1)]);
                $mark->setBuild($build);

                $manager->persist($mark);
            }
        }

        $manager->flush();
    }
}

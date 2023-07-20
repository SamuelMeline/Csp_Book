<?php

namespace App\DataFixtures;

use App\Entity\Item;
use App\Entity\Build;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Items
        $items = [];
        for ($i = 1; $i < 31; $i++) {
            $item = new Item();
            $item->setImage('item.png');
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

            // Ajouter 5 items obligatoirement aléatoires à la panoplie
            for ($k = 0; $k < 5; $k++) {
                $item = $items[mt_rand(0, count($items) - 1)];
                $build->addItem($item);
            }            
            $manager->persist($build);
        }

        $manager->flush();
    }
}

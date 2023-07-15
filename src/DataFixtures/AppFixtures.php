<?php

namespace App\DataFixtures;

use App\Entity\Item;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i < 31; $i++) {
            $item = new Item();
            $item->setImage('item.png');
            $item->setName('Item ' . $i);
            $item->setPrice(mt_rand(1000, 4000));

            $manager->persist($item);
        }

        $manager->flush();
    }
}

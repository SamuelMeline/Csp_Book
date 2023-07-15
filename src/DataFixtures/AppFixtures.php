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
            $item->setImage('https://static.wikia.nocookie.net/leagueoflegends/images/9/91/Lame_d%27Infini_Obj.png/revision/latest?cb=20210518192718&path-prefix=fr');
            $item->setName('Item ' . $i);
            $item->setPrice(mt_rand(1000, 4000));

            $manager->persist($item);
        }

        $manager->flush();
    }
}

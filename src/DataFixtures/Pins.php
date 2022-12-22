<?php

namespace App\DataFixtures;

use App\Entity\Pin;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class Pins extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $p1 = new Pin();
        $p1->setTitle('Pin 1');
        $p1->setDescription('Description 1...');
        $manager->persist($p1);

        $p2 = new Pin();
        $p2->setTitle('Pin 2');
        $p2->setDescription('Description 2...');
        $manager->persist($p2);

        $manager->flush();
    }
}

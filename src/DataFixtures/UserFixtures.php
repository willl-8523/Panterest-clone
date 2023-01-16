<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $u1 = new User();
        $u1->setFirstName('Martial');
        $u1->setLastName('Mfoudi');
        $u1->setEmail('martial@example.com');
        $u1->setPassword('$2y$13$pevV0TBXAAou3547b2TXJeNgoENAV3SskOw0dPo4/E.xEtTWnvxOy');
        $manager->persist($u1);

        $u2 = new User();
        $u2->setFirstName('Vincent');
        $u2->setLastName('Abouchou');
        $u2->setEmail('vincent@example.com');
        $u2->setPassword('$2y$13$4QKY.ZM8Kp4K9vHZFF2M.ekkEIOAQwNoo.w51LdI6a9NV0JEc8iS2');
        $manager->persist($u2);

        $manager->flush();
    }
}

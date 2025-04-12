<?php

namespace App\DataFixtures;

use App\Entity\{Car, User};
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user1 = new User();
        $user1->setEmail("user@mail.com");
        $user1->setPassword("123456");
        $user1->setRoles(["ROLE_USER"]);
        $manager->persist($user1);

        $car1 = new Car();
        $car1->setName("Voiture 1");
        $car1->setDriver($user1);
        $manager->persist($car1);


        $manager->flush();
    }
}

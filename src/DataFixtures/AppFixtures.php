<?php

namespace App\DataFixtures;

use App\Factory\BookFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        UserFactory::createOne([
            'email' => 'admin@example.com',
            'password' => '$2y$13$X7NAK5yb3QLcf9z2oalmwutggedQJwdvnyJUcodinQrYVsglPdvCi',
            'roles' => ['ROLE_ADMIN']
        ]);
        UserFactory::createOne([
            'email' => 'normal@example.com',
            'password' => '$2y$13$X7NAK5yb3QLcf9z2oalmwutggedQJwdvnyJUcodinQrYVsglPdvCi',
            'roles' => ['ROLE_NORMAL_USER']
        ]);
        BookFactory::createMany(10);
        $manager->flush();
    }
}

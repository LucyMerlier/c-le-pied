<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $admin = new User();
        $admin->setEmail('lady@godiva.goddess')->setUsername('LadyGodiva')->setRoles(['ROLE_ADMIN'])->setPassword('godiva');
        $manager->persist($admin);
        $this->addReference('admin');

        $user1 = new User();
        $user1->setEmail('user1@user.user')->setUsername('user-1')->setRoles(['ROLE_CONTRIBUTOR'])->setPassword('password');
        $manager->persist($user1);
        $this->addReference('user1');

        $user2 = new User();
        $user2->setEmail('user2@user.user')->setUsername('user-2')->setRoles(['ROLE_CONTRIBUTOR'])->setPassword('password');
        $manager->persist($user2);
        $this->addReference('user2');

        $manager->flush();
    }
}

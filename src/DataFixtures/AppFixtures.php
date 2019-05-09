<?php

namespace App\DataFixtures;

use App\DataFixtures\BaseFixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends BaseFixture
{
    public function loadData(ObjectManager $manager)
    {
        //@TODO
//         $this->createMany(10, 'main_users', function ($i) {
//            $user = new User();
//            $user->setEmail(sprintf('user%d@example.com', $i));
//            $user->setFirstName($this->faker->firstName);
//
//            return $user;
//        });
    }
}

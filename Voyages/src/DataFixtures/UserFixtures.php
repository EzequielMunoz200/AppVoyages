<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        /* $faker = Faker\Factory::create('FR_fr');

        for ($i = 1; $i <= 20; $i++) {
            $user = new User();
            $user->setName($faker->lastName)
                 ->setFirstname($faker->firstname)
                 ->setUsername($faker->firstname.'#'.rand(100, 1000))
                 ->setAvatar('default-avatar.png')
                 ->setEmail($faker->firstname.'-'.$faker->lastname.'@mail.com')
                 ->setBirthdate($faker->dateTimeBetween($startDate = '-90 years', $endDate = '-20 years', $timezone = null))
                 ->setPoints(5)
                 ->setDescription($faker->text($maxNbChars = 200))
                 ->setCreatedAt(new \DateTime())
                 ->setUpdatedAt(null)
                 ->setIsActive(true)
                 ->setIsReported(false)
                 ->setRoles([])
                 ->setPassword($faker->lastname);

             $manager->persist($user);
        } */


        //$manager->flush();
    }
}

<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use App\Repository\Auth\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CustomerFixtures extends Fixture
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 3; $i++)
        {

            $customer = new Customer();
            $customer->setName($faker->name);
            $customer->setPhone("01234567890");

            $manager->persist($customer);
        }

        $manager->flush();
    }
}

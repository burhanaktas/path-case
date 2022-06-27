<?php

namespace App\DataFixtures;

use App\Entity\Auth\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $faker = Factory::create();

        for ($i = 0; $i < 3; $i++)
        {
            $user = new User();
            $user->setUsername($faker->name);
            $encodedPassword = $this->passwordEncoder->encodePassword($user, $faker->password);
            $user->setPassword($encodedPassword);
            $manager->persist($user);
        }

        $manager->flush();
    }
}

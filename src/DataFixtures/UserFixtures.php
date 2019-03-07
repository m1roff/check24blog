<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    const CREATE_USERS_COUNT = 3;

    private $passwordEncoder;

    /**
     * @var \Faker\Generator
     */
    private $faker;

    /** @var ObjectManager  */
    private $manager;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, ObjectManager $manager)
    {
        $this->passwordEncoder = $passwordEncoder;

        $this->faker = Factory::create();

        $this->manager = $manager;
    }

    public function load(ObjectManager $manager)
    {
        for($i = 0; $i < self::CREATE_USERS_COUNT; ++$i) {
            $this->createUser();
        }
    }

    private function createUser()
    {
        $user = new User();

        $user->setUsername($this->faker->email);
        $user->setFirstName($this->faker->firstName);
        $user->setLastName($this->faker->lastName);
        $user->setPassword($this->passwordEncoder->encodePassword($user, '123123123'));

        $this->manager->persist($user);

        $this->manager->flush();
    }
}

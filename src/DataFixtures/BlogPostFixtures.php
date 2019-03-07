<?php

namespace App\DataFixtures;

use App\Entity\BlogPost;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use function random_int;

class BlogPostFixtures extends Fixture implements
    DependentFixtureInterface
{
    const POST_RAND_MIN = 3;
    const POST_RAND_MAX = 5;

    /** @var \Doctrine\Common\Persistence\ObjectRepository|UserRepository */
    private $userRepository;

    /**
     * @var \Faker\Generator
     */
    private $faker;

    /** @var ObjectManager  */
    private $manager;

    /** @var EntityManagerInterface  */
    private $em;

    public function __construct(ObjectManager $manager, EntityManagerInterface $em)
    {
        $this->faker = Factory::create();

        $this->manager = $manager;

        $this->em = $em;

        $this->userRepository = $em->getRepository('App:User');
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->userRepository->findAll() as $user)
        {
            $this->createPost($user);
        }
    }

    private function createPost(User $user)
    {
        $max = random_int(self::POST_RAND_MIN, self::POST_RAND_MAX);

        for ($i=0; $i < $max; ++$i) {
            $post = new BlogPost();
            $post->setUser($user);
            $post->setTitle($this->faker->text(50));
            $post->setText($this->faker->realText());
            $post->setPostDate($this->faker->dateTimeBetween('-10 month', '+ 10 days'));
            $this->manager->persist($post);
        }
        $this->manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }

}

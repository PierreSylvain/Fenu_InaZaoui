<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class UserFixtures extends Fixture implements FixtureGroupInterface
{
    public function __construct(
        private UserPasswordHasherInterface $encoder
    )
    {
    }

    public const USER_1 = 'user 1';
    public const USER_2 = 'user 2';
    public const USER_3 = 'user 3';
    public const USER_4 = 'user 4';
    public const USER_5 = 'user 5';

    public function load(ObjectManager $manager): void
    {        
        $faker = (new \Faker\Factory())::create('fr_FR');

        // Création des utilisateurs
        $user1 = new User();
        $user1
            ->setUsername('Ina')
            ->setEmail('ina@gmail.com')
            ->setDescription($faker->sentence())
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword($this->encoder->hashPassword($user1, 'password'))
            ->setAdmin(true)
            ->setRestricted(false);
        $manager->persist($user1);
        $this->addReference(self::USER_1, $user1);

        $user2 = new User();
        $user2
        ->setUsername('Orden')
        ->setEmail('orden@gmail.com')
        ->setDescription($faker->sentence())
        ->setRoles(['ROLE_ADMIN'])
        ->setPassword($this->encoder->hashPassword($user2, 'Motdepasse'))
        ->setAdmin(true)
        ->setRestricted(false);
    $manager->persist($user2);
        $this->addReference(self::USER_2, $user2);

        $user3 = new User();
        $user3
        ->setUsername($faker->firstName())
        ->setEmail($faker->freeEmail())
        ->setDescription($faker->sentence())
        ->setRoles(['ROLE_USER'])
        ->setPassword($this->encoder->hashPassword($user3, $user3->getUsername()))
        ->setAdmin(false)
        ->setRestricted(false);
        $manager->persist($user3);
        $this->addReference(self::USER_3, $user3);

        $user4 = new User();
        $user4
            ->setUsername($faker->firstName())
            ->setEmail($faker->freeEmail())
            ->setDescription($faker->sentence())
            ->setRoles(['ROLE_USER'])
            ->setPassword($this->encoder->hashPassword($user4, $user4->getUsername()))
            ->setAdmin(false)
            ->setRestricted(true);
        $manager->persist($user4);
        $this->addReference(self::USER_4, $user4);

        $user5 = new User();
        $user5
            ->setUsername($faker->firstName())
            ->setEmail($faker->freeEmail())
            ->setDescription($faker->sentence())
            ->setRoles(['ROLE_USER'])
            ->setPassword($this->encoder->hashPassword($user3, $user3->getUsername()))
            ->setAdmin(false)
            ->setRestricted(false);
        $manager->persist($user5);
        $this->addReference(self::USER_5, $user5);

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['UserFixtures'];
    }
}

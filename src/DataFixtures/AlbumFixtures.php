<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Album;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class AlbumFixtures extends Fixture implements FixtureGroupInterface
{
    public const ALBUM_1 = 'album 1';
    public const ALBUM_2 = 'album 2';
    public const ALBUM_3 = 'album 3';

    public function load(ObjectManager $manager): void
    {        
        // Création des albums
        $album1 = new Album();
        $album1->setName('Paysages');
        $manager->persist($album1);
        $this->addReference(self::ALBUM_1, $album1);

        $album2 = new Album();
        $album2->setName('Villes');
        $manager->persist($album2);
        $this->addReference(self::ALBUM_2, $album2);

        $album3 = new Album();
        $album3->setName('Montagne');
        $manager->persist($album3);
        $this->addReference(self::ALBUM_3, $album3);

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['AlbumFixtures'];
    }
}

<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Media;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\DataFixtures\UserFixtures;
use App\DataFixtures\AlbumFixtures;

class MediaFixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    public const MEDIA_1 = 'media 1';
    public const MEDIA_2 = 'media 2';
    public const MEDIA_3 = 'media 3';
    public const MEDIA_4 = 'media 4';
    public const MEDIA_5 = 'media 5';
    public const MEDIA_6 = 'media 6';
    public const MEDIA_7 = 'media 7';
    public const MEDIA_8 = 'media 8';

    public function load(ObjectManager $manager): void
    {        
        // Création des médias
        $media1 = new Media();
        $media1
            ->setUser($this->getReference(UserFixtures::USER_1))
            ->setAlbum($this->getReference(AlbumFixtures::ALBUM_1))
            ->setPath('uploads/Paysage_1.webp')
            ->setTitle('Paysage miroir');
        $manager->persist($media1);
        $this->addReference(self::MEDIA_1, $media1);


        $media2 = new Media();
        $media2
            ->setUser($this->getReference(UserFixtures::USER_5))
            ->setAlbum($this->getReference(AlbumFixtures::ALBUM_1))
            ->setPath('uploads/Paysage_2.webp')
            ->setTitle('Quai face montagne');
        $manager->persist($media2);
        $this->addReference(self::MEDIA_2, $media2);

        $media3 = new Media();
        $media3
            ->setUser($this->getReference(UserFixtures::USER_2))
            ->setAlbum($this->getReference(AlbumFixtures::ALBUM_1))
            ->setPath('uploads/Paysage_3.webp')
            ->setTitle('Colline verte');
        $manager->persist($media3);
        $this->addReference(self::MEDIA_3, $media3);

        $media4 = new Media();
        $media4
            ->setUser($this->getReference(UserFixtures::USER_5))
            ->setAlbum($this->getReference(AlbumFixtures::ALBUM_3))
            ->setPath('uploads/Montagne_1.webp')
            ->setTitle('Pic vers le ciel');
        $manager->persist($media4);
        $this->addReference(self::MEDIA_4, $media4);

        $media5 = new Media();
        $media5
            ->setUser($this->getReference(UserFixtures::USER_2))
            ->setAlbum($this->getReference(AlbumFixtures::ALBUM_3))
            ->setPath('uploads/Montagne_2.webp')
            ->setTitle('Chalet montagnard');
        $manager->persist($media5);
        $this->addReference(self::MEDIA_5, $media5);

        $media6 = new Media();
        $media6
            ->setUser($this->getReference(UserFixtures::USER_3))
            ->setAlbum($this->getReference(AlbumFixtures::ALBUM_3))
            ->setPath('uploads/Ville_1.webp')
            ->setTitle('Purple city');
        $manager->persist($media5);
        $this->addReference(self::MEDIA_6, $media6);

        $media7 = new Media();
        $media7
            ->setUser($this->getReference(UserFixtures::USER_2))
            ->setAlbum($this->getReference(AlbumFixtures::ALBUM_2))
            ->setPath('uploads/Ville_2.webp')
            ->setTitle('Pise');
        $manager->persist($media7);
        $this->addReference(self::MEDIA_7, $media7);

        $media8 = new Media();
        $media8
            ->setUser($this->getReference(UserFixtures::USER_2))
            ->setAlbum($this->getReference(AlbumFixtures::ALBUM_2))
            ->setPath('uploads/Ville_3.webp')
            ->setTitle('Paris');
        $manager->persist($media8);
        $this->addReference(self::MEDIA_8, $media8);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class, AlbumFixtures::class
        ];
    }

    public static function getGroups(): array
    {
        return ['MediaFixtures'];
    }
}

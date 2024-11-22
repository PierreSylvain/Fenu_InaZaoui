<?php

namespace App\Tests\Controller\Admin;

use App\Entity\Album;
use App\Entity\Media;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MediaControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->entityManager = self::getContainer()->get(EntityManagerInterface::class);
    }

    public function testAdminAccessMedias(): void
    {
        $admin = $this->entityManager->getRepository(User::class)->findOneBy([
            'admin' => true,
        ]);
        self::assertNotNull($admin);

        $this->client->loginUser($admin);

        $this->client->request('GET', '/admin/media');
        self::assertResponseIsSuccessful();

        $medias = $this->entityManager->getRepository(Media::class)->findAll();
        foreach ($medias as $media) {
            $title = $media->getTitle();
            self::assertNotNull($title);
        }
    }

    public function testUserAccessOwnMedias(): void
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy([
            'admin' => false,
        ]);
        self::assertNotNull($user);

        $admin = $this->entityManager->getRepository(User::class)->findOneBy([
            'admin' => true,
        ]);
        self::assertNotNull($admin);

        $this->client->loginUser($user);

        $this->client->request('GET', '/admin/media');
        self::assertResponseIsSuccessful();

        $ownedMedias = $this->entityManager->getRepository(Media::class)->findBy([
            'user' => $user,
        ]);
        foreach ($ownedMedias as $media) {
            $title = $media->getTitle();
            self::assertNotNull($title);
        }

        $adminMedias = $this->entityManager->getRepository(Media::class)->findBy([
            'user' => $admin,
        ]);
        foreach ($adminMedias as $media) {
            $title = $media->getTitle();
            self::assertNotNull($title);
        }

    }

    public function testAddAlbum(): void
    {
        $admin = $this->entityManager->getRepository(User::class)->findOneBy([
            'admin' => true,
        ]);
        self::assertNotNull($admin);

        $album = $this->entityManager->getRepository(Album::class)->findOneBy([]);
        self::assertNotNull($album);

        $this->client->loginUser($admin);

        $crawler = $this->client->request('GET', '/admin/media/add');
        self::assertResponseIsSuccessful();

        $form = $crawler->selectButton('Ajouter')->form([
            'media[user]' => $admin->getId(),
            'media[album]' => $album->getId(),
            'media[title]' => 'testAddMedia',
            // 'media[file]' => 
        ]);

        $this->client->submit($form);
        self::assertResponseRedirects('/admin/media');

        $this->client->followRedirect();

        $media = $this->entityManager->getRepository(Media::class)->findOneBy([
            'title' => 'testAddMedia',
        ]);

        self::assertNotNull($media);
        self::assertNotNull($media->getPath());

    }
}
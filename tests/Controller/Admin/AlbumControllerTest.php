<?php

namespace App\Tests\Controller\Admin;

use App\Entity\Album;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AlbumControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->entityManager = self::getContainer()->get(EntityManagerInterface::class);
    }

    public function testIndexAlbumPage(): void
    {
        $admin = $this->entityManager->getRepository(User::class)->findOneBy([
            'admin' => true,
        ]);
        self::assertNotNull($admin);

        $this->client->loginUser($admin);

        $this->client->request('GET', '/admin/album');
        self::assertResponseIsSuccessful();

        $albums = $this->entityManager->getRepository(Album::class)->findAll();
        foreach ($albums as $album) {
            $albumName = $album->getName();
            self::assertNotNull($albumName);
        }
    }

    public function testAddAlbum(): void
    {
        $admin = $this->entityManager->getRepository(User::class)->findOneBy([
            'admin' => true,
        ]);
        self::assertNotNull($admin);

        $this->client->loginUser($admin);

        $crawler = $this->client->request('GET', '/admin/album/add');
        self::assertResponseIsSuccessful();

        $form = $crawler->selectButton('Ajouter')->form([
            'album[name]' => 'testAddAlbum',
        ]);

        $this->client->submit($form);
        self::assertResponseRedirects('/admin/album');

        $this->client->followRedirect();

        $album = $this->entityManager->getRepository(Album::class)->findOneBy([
            'name' => 'testAddAlbum',
        ]);

        self::assertNotNull($album);
    }
}
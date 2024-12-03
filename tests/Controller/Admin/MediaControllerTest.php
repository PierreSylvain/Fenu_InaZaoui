<?php

namespace App\Tests\Controller\Admin;

use App\Entity\User;
use App\Entity\Album;
use App\Entity\Media;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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
            self::assertNotEmpty($title);
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
            self::assertNotEmpty($title);
        }

        $adminMedias = $this->entityManager->getRepository(Media::class)->findBy([
            'user' => $admin,
        ]);
        foreach ($adminMedias as $media) {
            $title = $media->getTitle();
            self::assertNotEmpty($title);
        }
    }

    public function testAddMedia(): void
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

        $imagePath = self::getContainer()->getParameter('kernel.project_dir') . '/public/uploads/imagesTest/test1.jpg';
        $file = new UploadedFile($imagePath, 'test1', 'image/jpeg');

        $form = $crawler->selectButton('Ajouter')->form([
            'media[user]' => $admin->getId(),
            'media[album]' => $album->getId(),
            'media[title]' => 'testAddMedia',
            'media[file]' => $file,
        ]);

        $this->client->submit($form);
        self::assertResponseRedirects('/admin/media');

        $media = $this->entityManager->getRepository(Media::class)->findOneBy([
            'title' => 'testAddMedia',
        ]);

        self::assertNotNull($media);
        self::assertSame('testAddMedia', $media->getTitle());
        self::assertNotEmpty($media->getPath());
    }
    
    public function testDeleteMedia(): void
    {
        $admin = $this->entityManager->getRepository(User::class)->findOneBy([
            'admin' => true,
        ]);
        self::assertNotNull($admin);

        $this->client->loginUser($admin);

        $album = $this->entityManager->getRepository(Album::class)->findOneBy([]);
        self::assertNotNull($album);

        $media = $this->entityManager->getRepository(Media::class)->findOneBy([
            'title' => 'testAddMedia',
        ]);

        $mediaId = $media->getId();
        $this->client->request('GET', '/admin/media/delete/' . $mediaId);
        self::assertResponseRedirects('/admin/media');

        self::assertNull($this->entityManager->getRepository(Media::class)->find($mediaId));
    }
}
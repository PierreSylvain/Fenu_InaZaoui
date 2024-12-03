<?php

namespace App\Tests\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->entityManager = self::getContainer()->get(EntityManagerInterface::class);
    }

    public function testLoginPage(): void
    {
        $this->client->request('GET', '/login');
        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h1', 'Connexion');
    }

    public function testRestrictedUserCannotLogin(): void
    {
        $restrictedUser = $this->entityManager->getRepository(User::class)->findOneBy([
            'restricted' => true
        ]);
        self::assertNotNull($restrictedUser);

        $this->client->request('GET', '/login');
        self::assertResponseIsSuccessful();
    }

    public function testLogoutRedirectsUser(): void
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy([
            'restricted' => false
        ]);
        self::assertNotNull($user);

        $this->client->loginUser($user);
        $this->client->request('GET', '/logout');
        self::assertResponseRedirects('/');

        $this->client->followRedirect();
        self::assertSelectorExists('a[href="/login"]');
    }
}
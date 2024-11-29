<?php

declare(strict_types=1);

namespace App\Tests\Controller;

// use App\Entity\User;
// use Doctrine\ORM\EntityManagerInterface;
// use Symfony\Bundle\FrameworkBundle\KernelBrowser;
// use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Tests\FunctionalTestCase;


final class SecurityControllerTest extends FunctionalTestCase
{
    // private KernelBrowser $client;
    // private EntityManagerInterface $entityManager;

    // protected function setUp(): void
    // {
    //     $this->client = static::createClient();
    //     $this->entityManager = self::getContainer()->get(EntityManagerInterface::class);
    // }

    public function testLoginPage(): void
    {
        $this->get('/login');
        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h1', 'Connexion');
    }
}
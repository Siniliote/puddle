<?php

declare(strict_types=1);

namespace Tests\Functional\Controller;

use App\Controller\HomepageController;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

#[CoversClass(HomepageController::class)]
#[CoversMethod(HomepageController::class, 'index')]
final class HomepageControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        // 1. Arrange
        $client = static::createClient();

        // 2. Act
        $crawler = $client->request('GET', '/');

        // 3. Assert
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Hello HomepageController!');
    }
}

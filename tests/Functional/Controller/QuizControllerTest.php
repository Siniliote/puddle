<?php

declare(strict_types=1);

namespace Tests\Functional\Controller;

use App\Controller\QuizController;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Foundry\Test\ResetDatabase;

#[CoversClass(QuizController::class)]
#[CoversMethod(QuizController::class, 'index')]
#[CoversMethod(QuizController::class, 'new')]
final class QuizControllerTest extends WebTestCase
{
    use ResetDatabase;

    public function testIndex(): void
    {
        // 1. Arrange
        $client = static::createClient();

        // 2. Act
        $crawler = $client->request('GET', '/quiz');

        // 3. Assert
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Quiz index');
    }

    public function testNew(): void
    {
        // 1. Arrange
        $client = static::createClient();

        // 2. Act
        $crawler = $client->request('GET', '/quiz/new');

        // 3. Assert
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Create new Quiz');
    }
}

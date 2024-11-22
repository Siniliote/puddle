<?php

declare(strict_types=1);

namespace Tests\Functional;

use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Foundry\Test\ResetDatabase;

#[CoversNothing]
final class AvailabilityTest extends WebTestCase
{
    use ResetDatabase;

    #[DataProvider('urlProvider')]
    #[TestDox('Smoke Test your URLs')]
    public function testPageIsSuccessful($url): void
    {
        // 1. Arrange
        $client = self::createClient();

        // 2. Act
        $client->request('GET', $url);

        // 3. Assert
        $this->assertResponseIsSuccessful();
    }

    public static function urlProvider(): \Generator
    {
        yield 'HomePage' => ['/'];
        yield 'Show list Quiz' => ['/quiz'];
        yield 'Create new Quiz' => ['/quiz/new'];
    }
}

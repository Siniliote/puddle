<?php

declare(strict_types=1);

namespace Tests\Functional;

use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Zenstruck\Foundry\Test\ResetDatabase;

#[CoversNothing]
final class AvailabilityTest extends WebTestCase
{
    use ResetDatabase;

    #[DataProvider('getPublicUrls')]
    #[TestDox('Smoke Test your Public URLs')]
    public function testPublicUrls($url): void
    {
        // 1. Arrange
        $client = self::createClient();

        // 2. Act
        $client->request('GET', $url);

        // 3. Assert
        $this->assertResponseIsSuccessful();
    }

    public static function getPublicUrls(): \Generator
    {
        yield 'HomePage' => ['/'];
        yield 'Show list Quiz' => ['/quiz'];
        yield 'Create new Quiz' => ['/quiz/new'];
        yield 'Login' => ['/login'];
        yield 'Register' => ['/register'];
        yield 'Reset Password' => ['/reset-password'];
        yield 'Reset Password check email' => ['/reset-password/check-email'];
    }

    #[DataProvider('getSecureUrls')]
    #[TestDox('Smoke Test your Secure URLs')]
    public function testSecureUrls(string $url): void
    {
        // 1. Arrange
        $client = static::createClient();

        // 2. Act
        $client->request('GET', $url);

        // 3. Assert
        $this->assertResponseRedirects(
            '/login',
            Response::HTTP_FOUND,
            \sprintf('The %s secure URL redirects to the login form.', $url)
        );
    }

    public static function getSecureUrls(): \Generator
    {
        yield 'Admin page' => ['/admin'];
    }

    #[TestDox('Smoke Test Verify Email URLs')]
    public function testPublicVerifyEmailAccessRedirect(): void
    {
        // 1. Arrange
        $client = static::createClient();

        // 2. Act
        $client->request('GET', '/verify/email');

        // 3. Assert
        $this->assertResponseRedirects(
            '/register',
            Response::HTTP_FOUND,
            \sprintf('The %s secure URL redirects to the login form.', '/verify/email')
        );
    }
}

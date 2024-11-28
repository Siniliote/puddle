<?php

declare(strict_types=1);

namespace Tests\Functional\Controller;

use App\Controller\ResetPasswordController;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Zenstruck\Foundry\Test\ResetDatabase;

#[CoversClass(ResetPasswordController::class)]
#[CoversMethod(ResetPasswordController::class, 'request')]
#[CoversMethod(ResetPasswordController::class, 'checkEmail')]
#[CoversMethod(ResetPasswordController::class, 'reset')]
final class ResetPasswordControllerTest extends WebTestCase
{
    use ResetDatabase;

    public function testGetResetRendersCorrectly(): void
    {
        $client = static::createClient();

        $client->request(Request::METHOD_GET, '/reset-password');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('#forgot_password_request_form button[type="submit"]', 'Reset password');
        $this->assertSelectorTextContains('h2', 'Reset your password');
    }

    public function testPostResetFailsIfEmptyPayload(): void
    {
        // 1. Arrange
        $client = static::createClient();

        // 2. Act
        $client->request(Request::METHOD_GET, '/reset-password');
        $client->submitForm('Reset password', [
            'reset_password_request_form[email]' => '',
        ]);

        // 3. Assert
        $response = $client->getResponse();

        $this->assertResponseIsUnprocessable();
        $this->assertStringContainsString('Please enter your email', $response->getContent());
    }

    // Afin de ne pas être victime d'une tentative de data-mining dans notre BDD.
    // En cas d'email inexistant, c'est tout de même un success
    public function testPostResetSuccesfullyIfUserEmailNotExist(): void
    {
        // 1. Arrange
        $client = static::createClient();

        // 2. Act
        $client->request(Request::METHOD_GET, '/reset-password');
        $client->submitForm('Reset password', [
            'reset_password_request_form[email]' => 'casper@example.com',
        ]);

        // 3. Assert
        $this->assertResponseRedirects('http://localhost/reset-password/check-email', Response::HTTP_FOUND);

        $response = $client->getResponse();
        $client->followRedirect();

        $this->assertSelectorTextContains('h2', 'Renouvellement de mot de passe');
    }

    public function testPostResetSuccesfully(): void
    {
        // 1. Arrange
        $client = static::createClient();

        // 2. Act
        $client->request(Request::METHOD_GET, '/reset-password');
        $client->submitForm('Reset password', [
            'reset_password_request_form[email]' => 'john.doe@example.com',
        ]);

        // 3. Assert
        $this->assertResponseRedirects('http://localhost/reset-password/check-email', Response::HTTP_FOUND);

        $this->assertQueuedEmailCount(1);
        $email = $this->getMailerMessage();

        $this->assertEmailHtmlBodyContains($email, 'Hi!');
        $this->assertEmailTextBodyContains($email, 'Hi!');

        $client->followRedirect();
        $this->assertSelectorTextContains('h2', 'Renouvellement de mot de passe');
    }
}

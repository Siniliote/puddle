<?php

declare(strict_types=1);

namespace Tests\Functional\Controller;

use App\Controller\RegistrationController;
use App\Entity\User;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

#[CoversClass(RegistrationController::class)]
#[CoversMethod(RegistrationController::class, 'register')]
final class RegistrationControllerTest extends WebTestCase
{
    public function testGetRegisterRendersCorrectly(): void
    {
        $client = static::createClient();

        $client->request(Request::METHOD_GET, '/register');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('#register_form button[type="submit"]', 'Create an account');
        $this->assertSelectorTextContains('h1', 'Create an account');
    }

    public function testPostRegisterFailsIfEmptyPayload(): void
    {
        // 1. Arrange
        $client = static::createClient();

        // 2. Act
        $client->request('GET', '/register');
        $client->submitForm('Create an account', [
            'registration_form[email]' => '',
            'registration_form[plainPassword][first]' => '',
            'registration_form[plainPassword][second]' => '',
        ]);

        // 3. Assert
        $response = $client->getResponse();

        $this->assertResponseIsUnprocessable();
        $this->assertStringContainsString('Please enter an email!', $response->getContent());
        $this->assertStringContainsString('Please enter a password!', $response->getContent());
        $this->assertStringContainsString('You should agree to our terms.', $response->getContent());
    }

    public function testInvalidPayload(): void
    {
        // 1. Arrange
        $client = static::createClient();

        // 2. Act
        $client->request('GET', '/register');
        $client->submitForm('Create an account', [
            'registration_form[email]' => 'mary.poppins',
            'registration_form[plainPassword][first]' => '0000',
            'registration_form[plainPassword][second]' => '1111',
            'registration_form[agreeTerms]' => false,
        ]);

        // 3. Assert
        $response = $client->getResponse();

        $this->assertResponseIsUnprocessable();
        $this->assertStringContainsString('This value is not a valid email address.', $response->getContent());
        $this->assertStringContainsString('The password fields must match.', $response->getContent());
        $this->assertStringContainsString('You should agree to our terms.', $response->getContent());
    }

    public function testToWeakPasswordPayload(): void
    {
        // 1. Arrange
        $client = static::createClient();

        // 2. Act
        $client->request('GET', '/register');
        $client->submitForm('Create an account', [
            'registration_form[email]' => 'marcus.burnett@example.com',
            'registration_form[plainPassword][first]' => '0000',
            'registration_form[plainPassword][second]' => '0000',
            'registration_form[agreeTerms]' => true,
        ]);

        // 3. Assert
        $response = $client->getResponse();

        $this->assertResponseIsUnprocessable();
        $this->assertStringContainsString('Your password should be at least 6 characters', $response->getContent());
    }

    public function testIsSuccessful(): void
    {
        // 1. Arrange
        $client = static::createClient();

        // 2. Act
        $client->request('GET', '/register');
        $client->submitForm('Create an account', [
            'registration_form[email]' => 'gisele.harabo@example.com',
            'registration_form[plainPassword][first]' => 'IamTheBadGirl',
            'registration_form[plainPassword][second]' => 'IamTheBadGirl',
            'registration_form[agreeTerms]' => true,
        ]);

        // 3. Assert
        $this->assertResponseRedirects('/', 302);

        $client->followRedirect();

        // Check automatically Connect
        $user = $this->getLoggedUser();
        $this->assertNotNull($user);
        $this->assertEquals('gisele.harabo@example.com', $user->getEmail());
    }

    private function getLoggedUser(): ?User
    {
        /** @var TokenStorageInterface */
        $tokenStorage = $this->getContainer()->get('security.token_storage');
        $token = $tokenStorage->getToken();

        if (!$token) {
            return null;
        }

        /** @var User|null $user */
        $user = $token->getUser();

        return $user;
    }
}

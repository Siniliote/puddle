<?php

declare(strict_types=1);

namespace Tests\Functional\Controller;

use App\Config\Role;
use App\Controller\SecurityController;
use App\Entity\User;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\LogoutException;
use Tests\Factory\UserFactory;
use Tests\Utils\SessionHelper;
use Zenstruck\Foundry\Test\ResetDatabase;

#[CoversClass(SecurityController::class)]
#[CoversMethod(SecurityController::class, 'login')]
final class SecurityControllerTest extends WebTestCase
{
    use ResetDatabase;
    use SessionHelper;

    public function testGetLoginRendersCorrectly(): void
    {
        // 1. Arrange
        $client = static::createClient();

        // 2. Act
        $client->request(Request::METHOD_GET, '/login');

        // 3. Assert
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('#login_form button[type="submit"]', 'Sign in');

        $user = $this->getLoggedUser();
        $this->assertNull($user);
    }

    public function testPostLoginFailsIfEmptyPayload(): void
    {
        // 1. Arrange
        $client = static::createClient();

        // 2. Act
        $client->request(Request::METHOD_GET, '/login');
        $client->submitForm('Sign in', [
            '_username' => '',
            '_password' => '',
        ]);

        // 3. Assert
        $this->assertResponseRedirects('http://localhost/login', Response::HTTP_FOUND);
        $client->followRedirect();

        $this->assertStringContainsString('Invalid credentials.', $client->getResponse()->getContent());

        $user = $this->getLoggedUser();
        $this->assertNull($user);
    }

    public function testPostLoginFailsIfUserDoesNotExist(): void
    {
        // 1. Arrange
        $client = static::createClient();
        $identifier = 'mary.poppins@example.com';
        $password = 'secret';

        // 2. Act
        $client->request(Request::METHOD_GET, '/login');
        $client->submitForm('Sign in', [
            '_username' => $identifier,
            '_password' => $password,
        ]);

        // 3. Assert
        $this->assertResponseRedirects('http://localhost/login', Response::HTTP_FOUND);
        $client->followRedirect();

        $this->assertStringContainsString('Invalid credentials.', $client->getResponse()->getContent());

        $user = $this->getLoggedUser();
        $this->assertNull($user);
    }

    public function testPostLoginSuccessful(): void
    {
        // 1. Arrange
        $client = static::createClient();

        $identifier = 'john.wick@example.com';
        $user = UserFactory::find(['email' => $identifier]);

        // 2. Act
        $client->request(Request::METHOD_GET, '/login');
        $client->submitForm('Sign in', [
            '_username' => $identifier,
            '_password' => UserFactory::DEFAULT_PASSWORD,
        ]);

        // 3. Assert
        $this->assertResponseRedirects('http://localhost/', Response::HTTP_FOUND);

        $user = $this->getLoggedUser();
        $this->assertNotNull($user);
        $this->assertEquals([Role::SUPER_ADMIN, Role::USER], $user->getEnumRoles());
    }

    public function testGetLogoutLogsUserOutFailsNoCsrf(): void
    {
        // 1.1. Expect Exception
        $this->expectException(LogoutException::class);

        // 1.2. Arrange
        $client = static::createClient();
        $client->catchExceptions(false);

        $user = UserFactory::createOne();
        $client->loginUser($user->_real());

        // 2. Act
        $client->request(Request::METHOD_GET, '/logout');
    }

    public function testGetLogoutLogsUserOut(): void
    {
        // 1. Arrange
        $client = static::createClient();
        $client->catchExceptions(false);

        $user = UserFactory::createOne();
        $client->loginUser($user->_real());

        // 2. Act
        $client->request(Request::METHOD_GET, '/logout', [
            '_csrf_token' => $this->generateCsrfToken($client, 'logout'),
        ]);

        // 3. Assert
        $this->assertResponseRedirects('http://localhost/', Response::HTTP_FOUND);

        $user = $this->getLoggedUser();
        $this->assertNull($user);
    }

    private function getLoggedUser(): ?User
    {
        /** @var \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface */
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

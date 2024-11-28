<?php

declare(strict_types=1);

namespace Tests\Functional\Controller\Admin;

use App\Config\Role;
use App\Controller\Admin\HomepageController;
use App\Repository\UserRepository;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Zenstruck\Foundry\Test\ResetDatabase;

#[CoversClass(HomepageController::class)]
#[CoversMethod(HomepageController::class, 'index')]
final class HomepageControllerTest extends WebTestCase
{
    use ResetDatabase;
    private KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();

        /** @var UserRepository $userRepository */
        $userRepository = static::getContainer()->get(UserRepository::class);

        /** @var User $user */
        $user = $userRepository->findOneByEmail('bryan.mills@example.com');

        $this->client->loginUser($user);
    }

    #[DataProvider('getUrlsForRegularUsers')]
    public function testAccessDeniedForRegularUsers(string $httpMethod, string $url): void
    {
        // 1.1. Expect Exception
        $this->expectException(AccessDeniedException::class);

        // 1.2. Arrange
        $this->client->getCookieJar()->clear();
        $this->client->catchExceptions(false);

        /** @var UserRepository $userRepository */
        $userRepository = static::getContainer()->get(UserRepository::class);

        /** @var User $user */
        $user = $userRepository->findOneByEmail('john.doe@example.com');
        $this->client->loginUser($user);

        // 1.3. Pre-Assert
        $this->assertEquals([Role::USER], $user->getEnumRoles());

        // 2. Act
        $this->client->request($httpMethod, $url);
    }

    public static function getUrlsForRegularUsers(): \Generator
    {
        yield 'Admin index' => ['GET', '/admin'];
    }

    public function testAdminHomepage(): void
    {
        // 1. Arrange
        // @see self::setUp

        // 2. Act
        $this->client->request('GET', '/admin/');

        // 3. Assert
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Hello Admin/HomepageController!');
    }
}

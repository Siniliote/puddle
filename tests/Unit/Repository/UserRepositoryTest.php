<?php

declare(strict_types=1);

namespace Tests\Unit\Repository;

use App\Entity\User;
use App\Repository\UserRepository;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use Tests\Factory\UserFactory;
use Zenstruck\Foundry\Test\ResetDatabase;
use function Zenstruck\Foundry\Persistence\repository;

#[CoversClass(UserRepository::class)]
#[UsesClass(User::class)]
#[UsesClass(UserFactory::class)]
class UserRepositoryTest extends RepositoryTestCase
{
    use ResetDatabase;

    #[Test]
    public function findByEmail(): void
    {
        // 1. Arrange
        UserFactory::createOne([
            'email' => 'vanessa.doe@example.com',
        ]);

        // 2. Act
        $user = $this->entityManager
            ->getRepository(User::class)
            ->findOneBy(['email' => 'vanessa.doe@example.com'])
        ;

        // 3. Assert
        $this->assertInstanceOf(User::class, $user);
        $this->assertSame('vanessa.doe@example.com', $user->getEmail());
    }

    #[Test]
    public function findAll(): void
    {
        // 0. Pre-Assert by Story
        $this->assertCount(3, repository(User::class));

        // 1. Arrange
        UserFactory::createMany(2);

        // 2. Act
        $users = $this->entityManager
            ->getRepository(User::class)
            ->findAll();

        // 3. Assert
        $this->assertCount(5, $users);
    }
}

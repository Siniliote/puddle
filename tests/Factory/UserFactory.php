<?php

declare(strict_types=1);

namespace Tests\Factory;

use App\Config\Role;
use App\Entity\User;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<User>
 */
final class UserFactory extends PersistentProxyObjectFactory
{
    public const DEFAULT_PASSWORD = '1234'; // the password used to create the pre-encoded version below

    public function __construct()
    {
    }

    public static function class(): string
    {
        return User::class;
    }

    protected function defaults(): array|callable
    {
        return [
            'email' => self::faker()->unique()->safeEmail(),
            'verified' => true,
            'password' => '$2y$13$8uPuOsFIM3KjHboI.ctkeOC6Hb02AQoICwZoZEhpZg8AbbEl5nwh6',
            'roles' => [Role::USER],
        ];
    }
}

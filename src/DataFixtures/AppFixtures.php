<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Config\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Tests\Factory\UserFactory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $this->loadUsers();
    }

    protected function loadUsers(): void
    {
        // Create Admin user
        UserFactory::createOne([
            'email' => 'john.wick@gmail.com',
            'roles' => [Role::SUPER_ADMIN],
        ]);

        UserFactory::createOne([
            'email' => 'bryan.mills@gmail.com',
            'roles' => [Role::ADMIN],
        ]);

        // Create 10 User random with role user
        UserFactory::createMany(10, ['roles' => [Role::USER]]);
    }
}

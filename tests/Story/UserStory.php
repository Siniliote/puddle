<?php

declare(strict_types=1);

namespace Tests\Story;

use App\Config\Role;
use Tests\Factory\UserFactory;
use Zenstruck\Foundry\Story;

final class UserStory extends Story
{
    public function build(): void
    {
        UserFactory::createOne([
            'email' => 'john.wick@example.com',
            'roles' => [Role::SUPER_ADMIN],
        ]);

        UserFactory::createOne([
            'email' => 'bryan.mills@example.com',
            'roles' => [Role::ADMIN],
        ]);

        UserFactory::createOne([
            'email' => 'john.doe@example.com',
            'roles' => [Role::USER],
        ]);
    }
}

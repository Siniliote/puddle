<?php

declare(strict_types=1);

namespace App\Config;

/**
 * @see Symfony\Component\Security\Core\Authorization\Voter\AuthenticatedVoter
 *
 * Roles interne, défini par symfony.
 * @see AuthenticatedVoter::IS_AUTHENTICATED_FULLY
 * @see AuthenticatedVoter::IS_AUTHENTICATED_REMEMBERED
 * @see AuthenticatedVoter::IS_AUTHENTICATED
 * @see AuthenticatedVoter::IS_IMPERSONATOR
 * @see AuthenticatedVoter::IS_REMEMBERED
 * @see AuthenticatedVoter::PUBLIC_ACCESS
 */
enum Role: string
{
    case SUPER_ADMIN = 'ROLE_SUPER_ADMIN';
    case ADMIN = 'ROLE_ADMIN';
    case USER = 'ROLE_USER';
    case GUEST = 'ROLE_GUEST';
    case ALLOWED_TO_SWITCH = 'ROLE_ALLOWED_TO_SWITCH';

    public function getColors(): string
    {
        return match ($this) {
            Role::SUPER_ADMIN => 'red',
            Role::ADMIN => 'orange',
            Role::USER => 'green',
            Role::GUEST => 'blue',
            Role::ALLOWED_TO_SWITCH => 'gray',
        };
    }
}

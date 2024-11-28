<?php

declare(strict_types=1);

namespace App\Config;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

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
enum Role: string implements TranslatableInterface
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

    public function trans(TranslatorInterface $translator, ?string $locale = null): string
    {
        return match ($this) {
            self::SUPER_ADMIN => $translator->trans('Super Administrator', locale: $locale),
            self::ADMIN => $translator->trans('Administrator', locale: $locale),
            self::USER => $translator->trans('User', locale: $locale),
            self::GUEST => $translator->trans('Guest', locale: $locale),
            self::ALLOWED_TO_SWITCH => $translator->trans('Allowed to switch', locale: $locale),
        };
    }
}

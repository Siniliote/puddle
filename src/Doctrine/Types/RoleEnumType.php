<?php

declare(strict_types=1);

namespace App\Doctrine\Types;

use App\Config\Role;

class RoleEnumType extends AbstractEnumType
{
    public const NAME = 'role';

    public static function getEnumsClass(): string
    {
        return Role::class;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}

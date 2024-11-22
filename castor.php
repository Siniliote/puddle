<?php

declare(strict_types=1);

use function Castor\guard_min_version;
use function Castor\import;

guard_min_version('0.21.0');

import(__DIR__.'/.castor');

/**
 * @return array<string, mixed>
 */
function create_default_variables(): array
{
    $projectName = 'starter';
    $serverName = 'localhost';

    return [
        'project_name' => $projectName,
        'server_name' => $serverName,
        'php_version' => 8.3,
    ];
}

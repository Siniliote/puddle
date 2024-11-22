<?php

namespace qa;

use Castor\Attribute\AsTask;

use function Castor\variable;
use function docker\docker_exit_code;

#[AsTask(description: 'Fixes Coding Style', aliases: ['cs'])]
function cs(bool $dryRun = false): int
{
    if (!is_dir(variable('root_dir') . '/tools/php-cs-fixer/vendor')) {
        install();
    }

    $command = [
        'php-cs-fixer',
        'fix',
    ];

    if ($dryRun) {
        $command[] = '--dry-run';
    }

    return docker_exit_code($command, workDir: '/app');
}

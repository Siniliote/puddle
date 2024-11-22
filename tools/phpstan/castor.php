<?php

namespace qa;

use Castor\Attribute\AsTask;

use function Castor\io;
use function docker\docker_compose_run;
use function docker\docker_exit_code;
use function utils\title;

#[AsTask(description: 'Run PHPStan', aliases: ['stan'])]
function phpstan(bool $generateBaseline = false): int
{
    title('qa:phpstan');

    if (!file_exists('var/cache/dev/App_KernelDevDebugContainer.xml')) {
        io()->note('PHPStan needs the dev/debug cache. Generating it...');
        docker_compose_run('bin/console cache:warmup');
    }

    $command = [
        'phpstan',
        '--memory-limit=-1'
    ];

    if ($generateBaseline) {
        $command[] = '-b';
    }

    return docker_exit_code($command);
}

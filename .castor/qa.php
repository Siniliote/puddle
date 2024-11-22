<?php

namespace qa;

use Castor\Attribute\AsTask;

use function Castor\import;
use function Castor\finder;
use function Castor\io;
use function docker\docker_exit_code;

import(__DIR__.'/../tools/php-cs-fixer/castor.php');

#[AsTask(description: 'Install all tools')]
function install(): void
{
    $dir = finder()->depth('== 0')->in('tools/')->exclude('bin')->directories();

    foreach ($dir as $subDir) {
        io()->info("Install $subDir");
        docker_exit_code("composer install", workDir: '/app/'. $subDir);
    }
}

#[AsTask(description: 'Update all tools')]
function update(): void
{
    $dir = finder()->depth('== 0')->in('tools/')->exclude('bin')->directories();

    foreach ($dir as $subDir) {
        io()->info("Update $subDir");
        docker_exit_code("composer update", workDir: '/app/'. $subDir);
    }
}

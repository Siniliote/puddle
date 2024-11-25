<?php

namespace qa;

use Castor\Attribute\AsOption;
use Castor\Attribute\AsTask;

use function Castor\context;
use function Castor\import;
use function Castor\finder;
use function Castor\io;
use function docker\docker_exit_code;
use function utils\title;

import(__DIR__.'/../tools/php-cs-fixer/castor.php');
import(__DIR__.'/../tools/phpstan/castor.php');

#[AsTask(description: 'Runs all QA tasks')]
function all(): int
{
    install();
    $cs = cs();
    $phpstan = phpstan();
    $phpunit = phpunit();

    return max($cs, $phpstan/* , $phpunit */);
}

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


#[AsTask(description: 'Run all PHPUnit tests', aliases: ['test'])]
function phpunit(
    #[AsOption(name: 'cover', description: 'With Coverage')]
    bool $cover = false,
    #[AsOption(name: 'unit', description: 'Only UnitTest')]
    bool $unit = false
): void
{
    title('symfony:test');

    $c = context()
        ->withEnvironment([
            'APP_ENV' => 'test'
        ]);

    $command = [
        'vendor/bin/phpunit',
        '--testdox',
    ];

    if($cover) {
        $command[] = '--coverage-text';
        $command[] = '--testdox-html=build/testdox.html';
        $command[] = '--coverage-html=build/phpunit';
    }

    if($unit) {
        $command[] = '--testsuite=Unit';
    }

    try {
        docker_exit_code($command, $c);
    } catch (\Throwable $th) {
        exit;
    }
}

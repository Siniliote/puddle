<?php

namespace symfony;

use Castor\Attribute\AsOption;
use Castor\Attribute\AsTask;

use function Castor\context;
use function Castor\io;
use function Castor\run;
use function Castor\fs;
use function docker\docker_compose_run;
use function docker\docker_exit_code;
use function utils\aborted;
use function utils\docker_health_check;
use function utils\success;
use function utils\title;

#[AsTask(namespace: 'symfony', description: 'Serve the application with the Symfony binary', aliases: ['start'])]
function start(): void
{
    $c ??= context();
    docker_health_check($c);

    title('symfony:start');

    $c = $c
        ->withQuiet(false)
    ;

    run('symfony serve --daemon', context: $c);
}

#[AsTask(namespace: 'symfony', description: 'Stop the web server', aliases: ['stop'])]
function stop(): void
{
    $c ??= context();
    docker_health_check($c);

    title('symfony:stop');

    $c = $c
        ->withQuiet(false)
    ;


    run('symfony server:stop', context: $c);
}

#[AsTask(namespace: 'symfony', description: 'Display local web server logs', aliases: ['log'])]
function log(): void
{
    $c ??= context();
    docker_health_check($c);

    title('symfony:log');

    $c = $c
        ->withQuiet(false)
    ;

    run('symfony server:log', context: $c);
}

#[AsTask(namespace: 'symfony', description: 'Reload all assets', aliases: ['assets'])]
function assets(bool $watch = false): void
{
    title('symfony:assets');

    $command = [
        'bin/console',
        'tailwind:build',
    ];

    if ($watch) {
        $command[] = '--watch';
    }

    docker_compose_run($command);

    success(0);
    return;
}


#[AsTask(namespace: 'symfony', description: 'Connect to the FrankenPHP container', aliases: ['bash'])]
function bash(): void
{
    title('symfony:bash');

    $c = context()
        ->withTimeout(null)
        ->withTty()
        ->withAllowFailure()
    ;

    docker_compose_run('bash', c: $c);
}

#[AsTask(namespace: 'symfony', description: 'Purge all Symfony cache and logs', aliases: ['purge'])]
function purge(): void
{
    title('symfony:purge');
    fs()->remove('./var/cache/');
    fs()->remove('./var/logs/');
    fs()->remove('./var/coverage/');

    success(0);
    return;
}

#[AsTask(namespace: 'database', description: 'Reload all data in database', aliases: ['reload'])]
function reload(): void
{
    title('database:reload');
    docker_compose_run('bin/console doctrine:database:drop --force --if-exists');
    docker_compose_run('bin/console doctrine:database:create --if-not-exists');
    docker_compose_run('bin/console doctrine:migrations:migrate --no-interaction');

    success(0);
    return;
}

#[AsTask(name: 'twig', namespace: 'lint', description: 'Lint Twig files', aliases: ['lint-twig'])]
function lint_twig(): int
{
    title('lint:twig');

    return docker_exit_code('bin/console lint:twig templates/');
}

#[AsTask(name: 'yaml', namespace: 'lint', description: 'Lint Yaml files', aliases: ['lint-yaml'])]
function lint_yaml(): int
{
    title('lint:yaml');

    return docker_exit_code('bin/console lint:yaml --parse-tags config/');
}

#[AsTask(namespace: 'symfony', description: 'Switch to the production environment', aliases: ['prod'])]
function prod(): void
{
    title('symfony:prod');
    if (io()->confirm('Are you sure you want to switch to the production environment? This will overwrite your .env.local file.', false)) {
        fs()->copy('.env.local.dist', '.env.local');
        docker_compose_run('bin/console tailwind:build --minify');
        docker_compose_run('bin/console asset-map:compile');
        success(0);

        return;
    }

    aborted();
}

#[AsTask(namespace: 'symfony', description: 'Switch to the development environment', aliases: ['dev'])]
function dev(): void
{
    title('symfony:dev');
    if (io()->confirm('Are you sure you want to switch to the development environment? This will delete your .env.local file.', false)) {
        fs()->remove('.env.local');
        fs()->remove('./public/assets/');
        success(0);

        return;
    }

    aborted();
}

#[AsTask(namespace: 'symfony', description: 'Run all PHPUnit tests', aliases: ['test'])]
function test(
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
        docker_compose_run($command, $c);
    } catch (\Throwable $th) {
        exit;
    }
}

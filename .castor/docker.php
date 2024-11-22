<?php

namespace docker;

use Castor\Attribute\AsContext;
use Castor\Attribute\AsTask;
use Castor\Context;
use Symfony\Component\Process\Process;

use function Castor\context;
use function Castor\run;
use function Castor\variable;
use function utils\docker_health_check;
use function utils\title;

#[AsTask(description: 'Build or rebuild services', aliases: ['build'])]
function build(?string $service = null): void {
    docker_health_check();

    title('docker:build');

    $command = [];

    $command = [
        ...$command,
        'build',
        '--no-cache',
        '--build-arg', 'PHP_VERSION=' . variable('php_version'),
        '--build-arg', 'PROJECT_NAME=' . variable('project_name'),
    ];

    if ($service) {
        $command[] = $service;
    }

    docker_compose($command);
}

#[AsContext(default: true)]
function create_default_context(): Context
{
    $data = create_default_variables() + [
        'symfony_version' => "7.1.*",
        'stability' => "stable", 
        'php_version' => '8.3',
        'docker_compose_files' => [
            'compose.yaml',
        ],
        'root_dir' => \dirname(__DIR__),
    ];

    if (file_exists($data['root_dir'] . '/compose.override.yaml')) {
        $data['docker_compose_files'][] = 'compose.override.yaml';
    }

    return new Context(
        $data,
        pty: Process::isPtySupported(),
        environment: [
            'BUILDKIT_PROGRESS' => 'plain',
        ]
    );
}

#[AsContext(name: 'ci')]
function create_ci_context(): Context
{
    $c = create_default_context();

    return $c
        ->withData([
            // override the default context here
        ])
        ->withEnvironment([
            'COMPOSE_ANSI' => 'never',
        ])
    ;
}

/**
 * @param list<string> $subCommand
 */
function docker_compose(array $subCommand, ?Context $c = null): Process {
    docker_health_check($c);

    $c ??= context();

    $c = $c
        ->withTimeout(null)
        ->withQuiet(false)
    ;

    $command = [
        'docker',
        'compose'
    ];

    foreach (variable('docker_compose_files') as $file) {
        $command[] = '-f';
        $command[] = variable('root_dir') . '/' . $file;
    }

    $command = array_merge($command, $subCommand);

    return run($command, context: $c);
}

function docker_compose_run(
    string|array $runCommand,
    ?Context $c = null,
    string $service = 'php',
    bool $noDeps = true,
    ?string $workDir = null,
    bool $portMapping = false
): Process {

    docker_health_check();

    if(is_array($runCommand)) {
        $runCommand = implode(" ", $runCommand);
    }

    $command = [
        'run',
        '--rm',
    ];

    if ($noDeps) {
        $command[] = '--no-deps';
    }

    if ($portMapping) {
        $command[] = '--service-ports';
    }

    if (null !== $workDir) {
        $command[] = '-w';
        $command[] = $workDir;
    }

    $command[] = $service;
    $command[] = '/bin/sh';
    $command[] = '-c';
    $command[] = "exec {$runCommand}";

    return docker_compose($command, c: $c);
}

function docker_exit_code(
    string|array $runCommand,
    ?Context $c = null,
    string $service = 'php',
    bool $noDeps = true,
    ?string $workDir = null
): int {
    $c = ($c ?? context())->withAllowFailure();

    $process = docker_compose_run(
        runCommand: $runCommand,
        c: $c,
        service: $service,
        noDeps: $noDeps,
        workDir: $workDir
    );

    return $process->getExitCode() ?? 0;
}
<?php

namespace utils;

use Castor\Context;

use function Castor\context;
use function Castor\io;
use function Castor\run;
use function Castor\task;

/** Utilities */

function title(string $name): void
{
    $task = task();
    if ($task !== null && $task->getName() === $name) {
        io()->title($task->getDescription());
    }
}

function success(int $exitCode): int
{
    if ($exitCode === 0) {
        io()->success('Done!');
    } else {
        io()->error(sprintf('Failure (exit code %d returned).', $exitCode));
    }

    return $exitCode;
}

function aborted(string $message = 'Aborted'): void
{
    io()->warning($message);
}

function docker_health_check(?Context $c = null) {

    $c ??= context();

    $c = $c
            ->withAllowFailure(true)
            ->withQuiet(true)
    ;

    $process = run('docker --version', context: $c);

    if(!$process->isSuccessful()) {
        io()->error('Docker is not running');
        die;
    }
}
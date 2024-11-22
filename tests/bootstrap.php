<?php

declare(strict_types=1);

use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\ErrorHandler\ErrorHandler;

require dirname(__DIR__).'/vendor/autoload.php';

// /** @see https://github.com/symfony/symfony/issues/53812#issuecomment-1962740145 */
// https://github.com/zenstruck/foundry/issues/711
// set_exception_handler([new ErrorHandler(), 'handleException']);

if (method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->bootEnv(dirname(__DIR__).'/.env');
}

if ($_SERVER['APP_DEBUG']) {
    umask(0000);
}

if (false === (bool) $_SERVER['APP_DEBUG']) {
    // ensure fresh cache
    (new Symfony\Component\Filesystem\Filesystem())->remove(__DIR__.'/../var/cache/test');
}

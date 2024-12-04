<?php

declare(strict_types=1);

namespace App\Config;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum QuizStatus: string implements TranslatableInterface
{
    case DRAFT = 'DRAFT';
    case COMPLETED = 'COMPLETED';
    case REVIEWED = 'REVIEWED';
    case FINISHED = 'FINISHED';
    case FROZEN = 'FROZEN';

    public function trans(TranslatorInterface $translator, ?string $locale = null): string
    {
        return match ($this) {
            self::DRAFT => $translator->trans('Draft', locale: $locale),
            self::COMPLETED => $translator->trans('Completed', locale: $locale),
            self::REVIEWED => $translator->trans('Reviewed', locale: $locale),
            self::FINISHED => $translator->trans('Finished', locale: $locale),
            self::FROZEN => $translator->trans('Frozen', locale: $locale),
        };
    }
}

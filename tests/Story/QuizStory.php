<?php

declare(strict_types=1);

namespace Tests\Story;

use Tests\Factory\QuizFactory;
use Zenstruck\Foundry\Story;

final class QuizStory extends Story
{
    public function build(): void
    {
        QuizFactory::createOne([
            'title' => 'Awesome Title',
        ]);

        QuizFactory::createOne([
            'title' => 'Awesome Title 2',
        ]);
    }
}

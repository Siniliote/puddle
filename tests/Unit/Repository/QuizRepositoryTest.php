<?php

declare(strict_types=1);

namespace Tests\Unit\Repository;

use App\Entity\Quiz;
use App\Repository\QuizRepository;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use Tests\Factory\QuizFactory;
use function Zenstruck\Foundry\Persistence\repository;

#[CoversClass(QuizRepository::class)]
#[UsesClass(Quiz::class)]
#[UsesClass(QuizFactory::class)]
class QuizRepositoryTest extends RepositoryTestCase
{
    #[Test]
    public function findByExactTitle(): void
    {
        // 1. Arrange
        QuizFactory::createOne([
            'title' => 'Best ever title',
        ]);

        // 2. Act
        $quiz = $this->entityManager
            ->getRepository(Quiz::class)
            ->findOneBy(['title' => 'Best ever title'])
        ;

        // 3. Assert
        $this->assertInstanceOf(Quiz::class, $quiz);
        $this->assertSame('Best ever title', $quiz->getTitle());
    }

    #[Test]
    public function findAll(): void
    {
        // 0. Pre-Assert by Story
        $this->assertCount(2, repository(Quiz::class));

        // 1. Arrange
        QuizFactory::createMany(3);

        // 2. Act
        $quizs = $this->entityManager
            ->getRepository(Quiz::class)
            ->findAll();

        // 3. Assert
        $this->assertCount(5, $quizs);
    }
}

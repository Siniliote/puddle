<?php

declare(strict_types=1);

namespace App\Twig\Components\Quiz;

use App\Repository\QuestionRepository;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
class QuestionForm
{
    use DefaultActionTrait;

    #[LiveProp(writable: true)]
    public string $query = '';

    public function __construct(private QuestionRepository $productRepository)
    {
    }

    public function getProducts(): array
    {
        return $this->productRepository->search($this->query);
    }
}

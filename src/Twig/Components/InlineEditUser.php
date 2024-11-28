<?php

declare(strict_types=1);

namespace App\Twig\Components;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\ValidatableComponentTrait;

#[AsLiveComponent]
class InlineEditUser extends AbstractController
{
    use DefaultActionTrait;
    use ValidatableComponentTrait;

    #[LiveProp(writable: ['email'])]
    #[Assert\Valid]
    public User $user;

    #[LiveProp]
    public bool $isEditing = false;

    #[LiveAction]
    public function activateEditing(): void
    {
        $this->isEditing = true;
    }

    #[LiveAction]
    public function save(EntityManagerInterface $entityManager): void
    {
        $this->validate();
        $this->isEditing = false;

        $entityManager->flush();
    }
}

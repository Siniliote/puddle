<?php

declare(strict_types=1);

namespace App\Twig\Components;

use App\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent()]
class RegistrationForm extends AbstractController
{
    use ComponentWithFormTrait;
    use DefaultActionTrait;

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(RegistrationFormType::class);
    }

    public function hasValidationErrors(): bool
    {
        return $this->getForm()->isSubmitted() && !$this->getForm()->isValid();
    }
}

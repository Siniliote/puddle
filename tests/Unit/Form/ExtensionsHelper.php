<?php

declare(strict_types=1);

namespace Tests\Unit\Form;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntityValidator;
use Symfony\Component\Form\AbstractExtension;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Validator\ConstraintValidatorFactory;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\Validation;

trait ExtensionsHelper
{
    /**
     * @return list<AbstractExtension>
     */
    protected function getExtensions(): array
    {
        $validator = Validation::createValidator();
        $factory = new class extends ConstraintValidatorFactory {
            public function addValidator(string $className, ConstraintValidatorInterface $validator): void
            {
                $this->validators[$className] = $validator;
            }
        };

        $factory->addValidator('doctrine.orm.validator.unique', $this->createMock(UniqueEntityValidator::class));

        $validator = Validation::createValidatorBuilder()
            ->setConstraintValidatorFactory($factory)
            ->enableAttributeMapping()
            ->getValidator();

        return [
            new ValidatorExtension($validator),
        ];
    }
}

<?php

declare(strict_types=1);

namespace Tests\Unit\Form\Type;

use App\Entity\Quiz;
use App\Form\QuizFormType;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use Symfony\Component\Form\Test\TypeTestCase;
use Tests\Unit\Form\Extensions;

#[CoversClass(QuizFormType::class)]
#[UsesClass(Quiz::class)]
class QuizFormTypeTest extends TypeTestCase
{
    use Extensions;

    #[Test]
    public function testSubmitValidData(): void
    {
        $formData = [
            'title' => 'Title of best',
        ];

        $model = new Quiz();

        $form = $this->factory->create(QuizFormType::class, $model);

        $expected = new Quiz();
        $expected->setTitle('Title of best');

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isValid());

        $this->assertEquals($expected, $model);
    }
}

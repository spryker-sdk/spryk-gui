<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\SprykGui\Communication\Form\Type;

use Generated\Shared\Transfer\ArgumentTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \SprykerSdk\Zed\SprykGui\Business\SprykGuiFacadeInterface getFacade()
 * @method \SprykerSdk\Zed\SprykGui\SprykGuiConfig getConfig()
 * @method \SprykerSdk\Zed\SprykGui\Communication\SprykGuiCommunicationFactory getFactory()
 */
class ArgumentType extends AbstractType
{
    /**
     * @var string
     */
    public const ARGUMENT_CHOICES = 'argumentChoices';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired([
            static::ARGUMENT_CHOICES,
        ]);

        $resolver->setDefaults([
            'data_class' => ArgumentTransfer::class,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $argumentCollectionTransfer = $options[static::ARGUMENT_CHOICES];

        $builder->add('innerArgument', ChoiceType::class, [
            'choices' => $argumentCollectionTransfer,
            'choice_label' => function (ArgumentTransfer $argumentTransfer) {
                return $argumentTransfer->getName();
            },
            'choice_attr' => function (ArgumentTransfer $argumentTransfer) {
                return ['data-proposal' => $argumentTransfer->getVariable()];
            },
            'placeholder' => '',
            'label' => 'Type',
            'attr' => [
                'class' => 'type-selector',
            ],
        ]);

        $builder->add('variable', TextType::class);
        $builder->add('defaultValue', TextType::class);
        $builder->add('isOptional', CheckboxType::class, [
            'required' => false,
        ]);
    }
}

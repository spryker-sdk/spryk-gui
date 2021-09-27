<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\SprykGui\Communication\Form\Type;

use Generated\Shared\Transfer\ArgumentCollectionTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \SprykerSdk\Zed\SprykGui\Business\SprykGuiFacadeInterface getFacade()
 * @method \SprykerSdk\Zed\SprykGui\SprykGuiConfig getConfig()
 * @method \SprykerSdk\Zed\SprykGui\Communication\SprykGuiCommunicationFactory getFactory()
 */
class ArgumentCollectionType extends AbstractType
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
            'entry_type' => ArgumentType::class,
            'entry_options' => function (Options $options) {
                return [
                    'label' => false,
                    static::ARGUMENT_CHOICES => $options[static::ARGUMENT_CHOICES],
                ];
            },
            'allow_add' => true,
            'label' => 'Arguments',
            'required' => false,
            'attr' => [
                'class' => 'prototype',
            ],
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addEventListener(
            FormEvents::SUBMIT,
            function (FormEvent $event): void {
                $argumentCollectionTransfer = new ArgumentCollectionTransfer();
                foreach ($this->getArgumentsFromEvent($event) as $argumentTransfer) {
                    $innerArgumentTransfer = $argumentTransfer->getInnerArgument();

                    if ($innerArgumentTransfer !== null) {
                        $argumentTransfer->setType($innerArgumentTransfer->getType());
                        $argumentTransfer->setArgumentMeta($innerArgumentTransfer->getArgumentMeta());
                    }

                    $argumentTransfer->setInnerArgument(null);

                    $argumentCollectionTransfer->addArgument($argumentTransfer);
                }
                $event->setData($argumentCollectionTransfer);
            }
        );
    }

    /**
     * @param \Symfony\Component\Form\FormEvent $event
     *
     * @return \Generated\Shared\Transfer\ArgumentTransfer[]
     */
    protected function getArgumentsFromEvent(FormEvent $event): array
    {
        return $event->getData();
    }

    /**
     * @return string
     */
    public function getParent(): string
    {
        return CollectionType::class;
    }
}

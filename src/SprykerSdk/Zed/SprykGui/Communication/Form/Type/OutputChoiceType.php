<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\SprykGui\Communication\Form\Type;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \SprykerSdk\Zed\SprykGui\Business\SprykGuiFacadeInterface getFacade()
 * @method \SprykerSdk\Zed\SprykGui\SprykGuiConfig getConfig()
 * @method \SprykerSdk\Zed\SprykGui\Communication\SprykGuiCommunicationFactory getFactory()
 */
class OutputChoiceType extends AbstractType
{
    /**
     * @var string
     */
    protected const OUTPUT_CHOICES = 'outputChoices';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired([
            static::OUTPUT_CHOICES,
        ]);

        $resolver->setDefaults([
            'choices' => function (Options $options) {
                return $options[static::OUTPUT_CHOICES];
            },
            'choice_label' => 'name',
            'choice_value' => 'type',
        ]);
    }

    /**
     * @return string
     */
    public function getParent(): string
    {
        return ChoiceType::class;
    }
}

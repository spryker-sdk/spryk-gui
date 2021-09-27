<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\SprykGui\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \SprykerSdk\Zed\SprykGui\Business\SprykGuiFacadeInterface getFacade()
 * @method \SprykerSdk\Zed\SprykGui\Communication\SprykGuiCommunicationFactory getFactory()
 * @method \SprykerSdk\Zed\SprykGui\SprykGuiConfig getConfig()
 */
class PreBuildForm extends AbstractType
{
    /**
     * @var string
     */
    public const FIELD_ENTER_MODULE_MANUALLY = 'enterModuleManually';
    /**
     * @var string
     */
    public const FIELD_MODE = 'mode';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addModeField($builder);
        $this->addEnterModuleManuallyField($builder);

        $builder->add('next', SubmitType::class, [
            'label' => 'Next step',
            'attr' => [
                'class' => 'btn btn-primary safe-submit',
            ],
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return void
     */
    protected function addModeField(FormBuilderInterface $builder): void
    {
        $fieldModeValue = $builder->getData()[static::FIELD_MODE];

        if ($fieldModeValue === 'both') {
            $builder->add('mode', ChoiceType::class, [
                'choices' => [
                    'Project' => 'project',
                    'Core' => 'core',
                ],
            ]);

            return;
        }

        $builder->add(static::FIELD_MODE, HiddenType::class, [
            'empty_data' => $fieldModeValue,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return void
     */
    protected function addEnterModuleManuallyField(FormBuilderInterface $builder): void
    {
        $builder->add(static::FIELD_ENTER_MODULE_MANUALLY, ChoiceType::class, [
            'choices' => [
                'Choose existing' => false,
                'Enter name manually' => true,
            ],
            'label' => 'How do you want to enter the name of the module?',
        ]);
    }
}

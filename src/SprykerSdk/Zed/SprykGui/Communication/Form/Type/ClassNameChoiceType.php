<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\SprykGui\Communication\Form\Type;

use Generated\Shared\Transfer\ModuleTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use SprykerSdk\Zed\SprykGui\Communication\Form\SprykDetailsForm;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * This Type can be used to select a class which is needed by a spryk.
 *
 * To use this for a spryk you need to define in the spryk yml a choiceLoader and type.
 * The `choiceLoader` class needs to be added to the ChoiceLoaderComposite in the SprykGuiBusinessFactory.
 * The `type` has to be `ClassNameChoice`.
 *
 * Example (*.yml):
 *
 * controller:
 *   choiceLoader: ZedCommunicationControllerChoiceLoader
 *   type: ClassNameChoice
 *
 * When the spryk has this configuration this type will be used and the choices will be loaded from the defined choiceLoader.
 *
 * @method \SprykerSdk\Zed\SprykGui\Business\SprykGuiFacadeInterface getFacade()
 * @method \SprykerSdk\Zed\SprykGui\SprykGuiConfig getConfig()
 * @method \SprykerSdk\Zed\SprykGui\Communication\SprykGuiCommunicationFactory getFactory()
 */
class ClassNameChoiceType extends AbstractType
{
    protected const OPTION_MODULE = SprykDetailsForm::OPTION_MODULE;

    protected const OPTION_EXISTING_MODULE = SprykDetailsForm::OPTION_EXISTING_MODULE;

    /**
     * @var string
     */
    protected const SPRYK = 'spryk';

    /**
     * @var string
     */
    protected const CHOICE_LOADER = 'choiceLoader';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired([
            static::OPTION_MODULE,
            static::OPTION_EXISTING_MODULE,
            static::SPRYK,
            static::CHOICE_LOADER,
        ]);

        $resolver->setDefaults([
            'choices' => function (Options $options) {
                $moduleTransfer = $this->getModuleTransfer($options);
                $classInformationTransferCollection = $this->getFacade()
                    ->loadChoices($options[static::CHOICE_LOADER], $moduleTransfer);

                if (count($classInformationTransferCollection) === 0) {
                    return [];
                }

                return $classInformationTransferCollection;
            },
            'choice_label' => 'className',
            'placeholder' => 'Select',
        ]);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\Options $options
     *
     * @return \Generated\Shared\Transfer\ModuleTransfer
     */
    protected function getModuleTransfer(Options $options): ModuleTransfer
    {
        $moduleTransfer = $options[static::OPTION_MODULE];
        $existingModuleTransfer = $options[static::OPTION_EXISTING_MODULE];

        if ($existingModuleTransfer) {
            $moduleTransfer->setPath($existingModuleTransfer->getPath());
        }

        return $moduleTransfer;
    }

    /**
     * @return string
     */
    public function getParent(): string
    {
        return ChoiceType::class;
    }
}

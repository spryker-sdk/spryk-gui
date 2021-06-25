<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\SprykGui\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use SprykerSdk\Zed\SprykGui\Communication\Form\Type\ArgumentCollectionType;
use SprykerSdk\Zed\SprykGui\Communication\Form\Type\MethodNameChoiceType;
use SprykerSdk\Zed\SprykGui\Communication\Form\Type\OutputChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \SprykerSdk\Zed\SprykGui\Business\SprykGuiFacadeInterface getFacade()
 * @method \SprykerSdk\Zed\SprykGui\SprykGuiConfig getConfig()
 * @method \SprykerSdk\Zed\SprykGui\Communication\SprykGuiCommunicationFactory getFactory()
 */
class SprykDetailsForm extends AbstractType
{
    public const OPTION_MODULE = 'module';
    public const OPTION_EXISTING_MODULE = 'existingModule';

    protected const OPTION_SPRYK = 'spryk';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired([
            static::OPTION_SPRYK,
            static::OPTION_MODULE,
            static::OPTION_EXISTING_MODULE,
        ]);

        $resolver->setDefaults([
            'classNameChoices' => [],
            'outputChoices' => [],
            'argumentChoices' => [],
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
        $mode = $builder->getData()['mode'] ?? null;
        $sprykDefinition = $this->getFacade()->getSprykDefinition($options[static::OPTION_SPRYK], $mode);

        $filteredArguments = $this->getRelevantArguments($sprykDefinition['arguments']);

        $this->addArgumentsToForm($builder, $filteredArguments, $options);
    }

    /**
     * @param mixed[] $arguments
     *
     * @return mixed[]
     */
    protected function getRelevantArguments(array $arguments): array
    {
        $filteredArguments = [];

        foreach ($arguments as $argumentName => $argumentDefinition) {
            if (
                in_array($argumentName, ['module', 'dependentModule', 'organization'], true)
                || !$this->isUserInputRequired($argumentDefinition)
            ) {
                continue;
            }

            $filteredArguments[$argumentName] = $argumentDefinition;
        }

        return $filteredArguments;
    }

    /**
     * @param mixed[]|null $argumentDefinition
     *
     * @return bool
     */
    protected function isUserInputRequired(?array $argumentDefinition): bool
    {
        return (!isset($argumentDefinition['value']) && !isset($argumentDefinition['callbackOnly']));
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param mixed[] $arguments
     * @param mixed[] $options
     *
     * @return void
     */
    protected function addArgumentsToForm(FormBuilderInterface $builder, array $arguments, array $options): void
    {
        foreach ($arguments as $argumentName => $argumentDefinition) {
            $options = $this->addArgumentToForm($builder, $argumentName, $options, $argumentDefinition);
        }
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param string $argumentName
     * @param mixed[] $options
     * @param mixed[]|null $argumentDefinition
     *
     * @return mixed[]
     */
    protected function addArgumentToForm(
        FormBuilderInterface $builder,
        string $argumentName,
        array $options,
        ?array $argumentDefinition = null
    ): array {
        if (isset($argumentDefinition['type'])) {
            $this->buildTypedArgumentForm($builder, $argumentName, $options, $argumentDefinition);

            return $options;
        }

        if ($argumentName === 'input' || $argumentName === 'constructorArguments') {
            $argumentCollectionTypeOptions = ['argumentChoices' => $options['argumentChoices']];
            unset($options['argumentChoices']);
            $builder->add($argumentName, ArgumentCollectionType::class, $argumentCollectionTypeOptions);

            return $options;
        }

        if ($argumentName === 'output') {
            $outputChoiceTypeOptions = ['outputChoices' => $options['outputChoices']];
            unset($options['outputChoices']);
            $builder->add($argumentName, OutputChoiceType::class, $outputChoiceTypeOptions);

            return $options;
        }

        $type = $this->getType($argumentDefinition);
        $typeOptions = $this->getTypeOptions($argumentName, $argumentDefinition);
        $builder->add($argumentName, $type, $typeOptions);

        return $options;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param string $argumentName
     * @param mixed[] $options
     * @param mixed[] $argumentDefinition
     *
     * @return void
     */
    protected function buildTypedArgumentForm(
        FormBuilderInterface $builder,
        string $argumentName,
        array $options,
        array $argumentDefinition
    ): void {
        $typeOptions = $this->getFormTypeOptions($options, $argumentDefinition);
        $generalOptions = [];

        if (isset($argumentDefinition['isOptional'])) {
            $generalOptions['required'] = false;
        }

        $formTypeName = sprintf(
            '%s%s%s',
            'SprykerSdk\\Zed\\SprykGui\\Communication\\Form\\Type\\',
            $argumentDefinition['type'],
            'Type'
        );

        if (!$this->getIsFormTypeCanBeRendered($formTypeName, $options)) {
            $builder->add($argumentName, TextType::class, $generalOptions);

            return;
        }

        $builder->add($argumentName, $formTypeName, $generalOptions + $typeOptions);
    }

    /**
     * @param string $formTypeClassName
     * @param array $options
     *
     * @return bool
     */
    protected function getIsFormTypeCanBeRendered(string $formTypeClassName, array $options): bool
    {
        if ($options[static::OPTION_EXISTING_MODULE]) {
            return true;
        }

        if (in_array($formTypeClassName, [MethodNameChoiceType::class], true)) {
            return true;
        }

        return false;
    }

    /**
     * @param mixed[] $options
     * @param mixed[] $argumentDefinition
     *
     * @return mixed[]
     */
    protected function getFormTypeOptions(array $options, array $argumentDefinition): array
    {
        if (!isset($argumentDefinition['typeOptions'])) {
            return $options;
        }

        $typeOptions = [];

        foreach ($argumentDefinition['typeOptions'] as $typeOptionName) {
            if (isset($argumentDefinition[$typeOptionName])) {
                $typeOptions[$typeOptionName] = $argumentDefinition[$typeOptionName];
            }

            if (isset($options[$typeOptionName])) {
                $typeOptions[$typeOptionName] = $options[$typeOptionName];
            }
        }

        return $typeOptions;
    }

    /**
     * @param mixed[]|null $argumentDefinition
     *
     * @return string
     */
    protected function getType(?array $argumentDefinition = null): string
    {
        if (isset($argumentDefinition['multiline'])) {
            return TextareaType::class;
        }

        return TextType::class;
    }

    /**
     * @param string $argumentName
     * @param mixed[]|null $argumentDefinition
     *
     * @return mixed[]
     */
    protected function getTypeOptions(string $argumentName, ?array $argumentDefinition = null): array
    {
        $typeOptions = [
            'attr' => ['class' => $argumentName],
        ];

        if (isset($argumentDefinition['isOptional'])) {
            $typeOptions['required'] = false;
        }

        if (isset($argumentDefinition['default'])) {
            $typeOptions['empty_data'] = $argumentDefinition['default'];
        }

        return $typeOptions;
    }
}

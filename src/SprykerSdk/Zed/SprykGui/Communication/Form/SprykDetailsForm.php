<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\SprykGui\Communication\Form;

use Generated\Shared\Transfer\OrganizationTransfer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \SprykerSdk\Zed\SprykGui\Business\SprykGuiFacadeInterface getFacade()
 * @method \SprykerSdk\Zed\SprykGui\SprykGuiConfig getConfig()
 * @method \SprykerSdk\Zed\SprykGui\Communication\SprykGuiCommunicationFactory getFactory()
 */
class SprykDetailsForm extends BaseSprykForm
{
    protected const SPRYK = 'spryk';
    protected const MODULE = 'module';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired([
            static::SPRYK,
            static::MODULE,
        ]);

        $resolver->setDefaults([
            'classNameChoices' => [],
            'outputChoices' => [],
            'argumentChoices' => [],
            'organizationChoices' => [],
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $mode = $builder->getData()['mode'] ?? null;
        $sprykDefinition = $this->getFacade()->getSprykDefinition($options[static::SPRYK], $mode);

        $filteredArguments = $this->getRelevantArguments($sprykDefinition['arguments']);

        $this->addOrganizationChoice($builder, $sprykDefinition);
        $this->addArgumentsToForm($builder, $filteredArguments, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $sprykDefinition
     *
     * @return void
     */
    protected function addOrganizationChoice(FormBuilderInterface $builder, array $sprykDefinition): void
    {
        $organizationCollection = isset($sprykDefinition['mode'])
            ? $this->getFacade()->getOrganizationsByMode($sprykDefinition['mode'])->getOrganizations()
            : $this->getFacade()->getOrganizations()->getOrganizations();

        $builder->add('organization', ChoiceType::class, [
            'choices' => $organizationCollection,
            'choice_label' => function (OrganizationTransfer $organizationTransfer) {
                return $organizationTransfer->getName();
            },
            'choice_value' => 'name',
            'placeholder' => '-select-',
        ]);
    }
}

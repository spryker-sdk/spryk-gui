<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\SprykGui\Communication\Form\Type;

use Generated\Shared\Transfer\ModuleTransfer;
use Generated\Shared\Transfer\OrganizationTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \SprykerSdk\Zed\SprykGui\Business\SprykGuiFacadeInterface getFacade()
 * @method \SprykerSdk\Zed\SprykGui\SprykGuiConfig getConfig()
 * @method \SprykerSdk\Zed\SprykGui\Communication\SprykGuiCommunicationFactory getFactory()
 */
class NewModuleType extends AbstractType
{
    protected const SPRYK_DEFINITION = 'sprykDefinition';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired([
            static::SPRYK_DEFINITION,
        ]);

        $resolver->setDefaults([
            'data_class' => ModuleTransfer::class,
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
        $organizationCollection = isset($options['sprykDefinition']['mode'])
            ? $this->getFacade()->getOrganizationsByMode($options['sprykDefinition']['mode'])->getOrganizations()
            : $this->getFacade()->getOrganizations()->getOrganizations();

        $builder->add('name', TextType::class);
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

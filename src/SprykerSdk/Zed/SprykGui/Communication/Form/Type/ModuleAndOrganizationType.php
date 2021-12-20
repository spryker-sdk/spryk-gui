<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\SprykGui\Communication\Form\Type;

use ArrayObject;
use Generated\Shared\Transfer\ApplicationTransfer;
use Generated\Shared\Transfer\ModuleFilterTransfer;
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
class ModuleAndOrganizationType extends AbstractType
{
    /**
     * @var string
     */
    public const OPTION_MODE_FILTER = 'modeFilter';

    /**
     * @var string
     */
    public const OPTION_MODULE_FILTER = 'moduleFilter';

    /**
     * @var string
     */
    public const OPTION_ENTER_MODULE_MANUALLY = 'enterModuleManually';

    /**
     * @var string
     */
    protected const MODULE_FILTER_KEY_ORGANIZATION = 'organization';

    /**
     * @var string
     */
    protected const MODULE_FILTER_KEY_APPLICATION = 'application';

    /**
     * @var string
     */
    protected const MODULE_FILTER_KEY_MODULE = 'module';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault(static::OPTION_MODE_FILTER, null);
        $resolver->setDefault(static::OPTION_MODULE_FILTER, null);
        $resolver->setDefault(static::OPTION_ENTER_MODULE_MANUALLY, true);

        $resolver->setDefaults([
            'data_class' => ModuleTransfer::class,
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
        $this->addModuleNameField($builder, $options);

        $builder->add('organization', ChoiceType::class, [
            'choices' => $this->getOrganizationCollection($options),
            'choice_label' => 'name',
            'choice_value' => 'name',
            'placeholder' => 'Select an organization',
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    protected function addModuleNameField(FormBuilderInterface $builder, array $options): void
    {
        if ($options[static::OPTION_ENTER_MODULE_MANUALLY] === true) {
            $builder->add('name', TextType::class);

            return;
        }

        $modules = $this->getFilteredModules($options);

        $choices = [];
        $organizationToModuleMap = [];

        foreach ($modules as $module) {
            $choices[] = $module->getName();
            $organizationToModuleMap[$module->getName()] = $module->getOrganizationOrFail()->getName();
        }

        $builder->add('name', ChoiceType::class, [
            'choices' => $choices,
            'choice_label' => function ($choice) {
                return $choice;
            },
            'choice_value' => function ($choice) {
                return $choice;
            },
            'group_by' => function ($moduleName) use ($organizationToModuleMap) {
                return $organizationToModuleMap[$moduleName];
            },
            'placeholder' => 'Select a module',
        ]);
    }

    /**
     * @param array<string, mixed> $options
     *
     * @return \Generated\Shared\Transfer\OrganizationTransfer[]|\ArrayObject
     */
    protected function getOrganizationCollection(array $options): ArrayObject
    {
        $modeFilterOption = $options[static::OPTION_MODE_FILTER];

        if ($modeFilterOption) {
            return $this->getFacade()
                ->getOrganizationsByMode($modeFilterOption)
                ->getOrganizations();
        }

        return $this->getFacade()->getOrganizations()->getOrganizations();
    }

    /**
     * @param array<string, mixed> $moduleFilter
     *
     * @return array<\Generated\Shared\Transfer\ModuleTransfer>
     */
    protected function getFilteredModules(array $moduleFilter): array
    {
        $moduleFilterTransfer = new ModuleFilterTransfer();
        if (isset($moduleFilter[static::MODULE_FILTER_KEY_ORGANIZATION])) {
            $organizationTransfer = new OrganizationTransfer();
            $organizationTransfer->setName($moduleFilter[static::MODULE_FILTER_KEY_ORGANIZATION]);
            $moduleFilterTransfer->setOrganization($organizationTransfer);
        }

        if (isset($moduleFilter[static::MODULE_FILTER_KEY_APPLICATION])) {
            $applicationTransfer = new ApplicationTransfer();
            $applicationTransfer->setName($moduleFilter[static::MODULE_FILTER_KEY_APPLICATION]);
            $moduleFilterTransfer->setApplication($applicationTransfer);
        }

        if (isset($moduleFilter[static::MODULE_FILTER_KEY_MODULE])) {
            $moduleTransfer = new ModuleTransfer();
            $moduleTransfer->setName($moduleFilter[static::MODULE_FILTER_KEY_MODULE]);
            $moduleFilterTransfer->setModule($moduleTransfer);
        }

        return $this->getFacade()->getModules($moduleFilterTransfer);
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\SprykGui\Communication\Form;

use Generated\Shared\Transfer\ModuleTransfer;
use Generated\Shared\Transfer\SprykDefinitionTransfer;
use SprykerSdk\Zed\SprykGui\Communication\Form\Type\ModuleChoiceType;
use SprykerSdk\Zed\SprykGui\Communication\Form\Type\NewModuleType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \SprykerSdk\Zed\SprykGui\Business\SprykGuiFacadeInterface getFacade()
 * @method \SprykerSdk\Zed\SprykGui\Communication\SprykGuiCommunicationFactory getFactory()
 * @method \SprykerSdk\Zed\SprykGui\SprykGuiConfig getConfig()
 */
class SprykMainForm extends BaseSprykForm
{
    protected const SPRYK = 'spryk';
    protected const MODULE = 'module';
    protected const DEPENDENT_MODULE = 'dependentModule';
    protected const ARGUMENTS = 'arguments';
    protected const TYPE = 'type';
    protected const MODULE_FILTER = 'moduleFilter';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired([
            static::SPRYK,
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
        $typeToAddListenerTo = static::MODULE;

        $spryk = $options[static::SPRYK];

        $mode = $builder->getData()['mode'] ?? null;
        $sprykDefinition = $this->getFacade()->getSprykDefinition($spryk, $mode);

        if (isset($sprykDefinition[static::ARGUMENTS][static::MODULE][static::TYPE])) {
            $filteredArguments = $this->getRelevantArguments($sprykDefinition['arguments']);

            $builder->add(static::MODULE, NewModuleType::class, ['sprykDefinition' => $sprykDefinition]);
            $this->addArgumentsToForm($builder, $filteredArguments, $options);

            $this->addRunSprykButton($builder);
            $this->addCreateTemplateButton($builder);

            return;
        }

        $moduleOptions = [];
        if (isset($sprykDefinition[static::ARGUMENTS][static::MODULE][static::MODULE_FILTER])) {
            $moduleOptions[static::MODULE_FILTER] = $sprykDefinition[static::ARGUMENTS][static::MODULE][static::MODULE_FILTER];
        }

        $builder->add(static::MODULE, ModuleChoiceType::class, $moduleOptions);

        if (array_key_exists(static::DEPENDENT_MODULE, $sprykDefinition[static::ARGUMENTS])) {
            $dependentModuleOptions = [];
            if (isset($sprykDefinition[static::ARGUMENTS][static::DEPENDENT_MODULE][static::MODULE_FILTER])) {
                $dependentModuleOptions[static::MODULE_FILTER] = $sprykDefinition[static::ARGUMENTS][static::DEPENDENT_MODULE][static::MODULE_FILTER];
            }
            $builder->add(static::DEPENDENT_MODULE, ModuleChoiceType::class, $dependentModuleOptions);

            $typeToAddListenerTo = static::DEPENDENT_MODULE;
        }

        $this->addNextButton($builder);

        $builder->get($typeToAddListenerTo)->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) use ($options, $builder, $sprykDefinition) {
            $form = $event->getForm()->getParent();
            $moduleTransfer = $this->getModuleTransferFromForm($form);

            if ($moduleTransfer->getName() && ($moduleTransfer->getOrganization() && $moduleTransfer->getOrganization()->getName())) {
                $form->remove('next');

                if ($form->has(static::DEPENDENT_MODULE)) {
                    $dependentModuleForm = $form->get(static::DEPENDENT_MODULE);
                    $dependentModuleTransfer = $dependentModuleForm->getData();

                    $moduleTransfer->setDependentModule($dependentModuleTransfer);
                }

                $sprykDefinitionTransfer = new SprykDefinitionTransfer();
                $sprykDefinitionTransfer->setName($options[static::SPRYK])
                    ->setMode($sprykDefinition['mode']);

                $sprykDataProvider = $this->getFactory()->createSprykFormDataProvider();
                $sprykDetailsForm = $builder->getFormFactory()
                    ->createNamedBuilder(
                        'sprykDetails',
                        SprykDetailsForm::class,
                        $sprykDataProvider->getData($sprykDefinitionTransfer, $moduleTransfer),
                        $sprykDataProvider->getOptions($sprykDefinitionTransfer, $moduleTransfer)
                    )->getForm();

                $form->add($sprykDetailsForm);
                $this->addRunSprykButton($form);
                $this->addCreateTemplateButton($form);
            }
        });
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return \Generated\Shared\Transfer\ModuleTransfer
     */
    protected function getModuleTransferFromForm(FormInterface $form): ModuleTransfer
    {
        return $form->get(static::MODULE)->getData();
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface|\Symfony\Component\Form\FormInterface $builder
     *
     * @return $this
     */
    protected function addCreateTemplateButton($builder)
    {
        $builder->add('create', SubmitType::class, [
            'label' => 'Create Template',
            'attr' => [
                'class' => 'btn btn-primary safe-submit',
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface|\Symfony\Component\Form\FormInterface $builder
     *
     * @return $this
     */
    protected function addRunSprykButton($builder)
    {
        $builder->add('run', SubmitType::class, [
            'label' => 'Run Spryk',
            'attr' => [
                'class' => 'btn btn-primary safe-submit',
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface|\Symfony\Component\Form\FormInterface $builder
     *
     * @return void
     */
    protected function addNextButton($builder): void
    {
        $builder->add('next', SubmitType::class, [
            'label' => 'Next step',
            'attr' => [
                'class' => 'btn btn-primary safe-submit',
            ],
        ]);
    }
}

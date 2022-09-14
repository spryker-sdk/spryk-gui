<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\SprykGui\Communication\Form\Type;

use ArrayObject;
use Generated\Shared\Transfer\ArgumentCollectionTransfer;
use Generated\Shared\Transfer\ArgumentMetaTransfer;
use Generated\Shared\Transfer\ArgumentTransfer;
use Generated\Shared\Transfer\MethodInformationTransfer;
use Generated\Shared\Transfer\ModuleTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \SprykerSdk\Zed\SprykGui\Business\SprykGuiFacadeInterface getFacade()
 * @method \SprykerSdk\Zed\SprykGui\SprykGuiConfig getConfig()
 * @method \SprykerSdk\Zed\SprykGui\Communication\SprykGuiCommunicationFactory getFactory()
 */
abstract class AbstractFactoryConstructorType extends AbstractType
{
    /**
     * @var string
     */
    protected const MODULE = 'module';

    /**
     * @var string
     */
    protected const SPRYK = 'spryk';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired([
            static::MODULE,
            static::SPRYK,
        ]);

        $resolver->setDefaults([
            'data_class' => ArgumentCollectionTransfer::class,
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
        $moduleTransfer = $this->getModuleTransfer($options);
        $className = $this->getClassName($moduleTransfer);

        $factoryInformation = $this->getFacade()->getFactoryInformation($className);

        if ($factoryInformation->getMethods()->count() === 0) {
            return;
        }

        $methods = $factoryInformation->getMethods();

        $argumentCollectionTransfer = $this->buildArguments($methods, $moduleTransfer);
        $argumentOptions = [
            'entry_type' => ArgumentType::class,
            'entry_options' => ['label' => false, ArgumentType::ARGUMENT_CHOICES => $argumentCollectionTransfer->getArguments()],
            'allow_add' => true,
            'label' => false,
            'required' => false,
            'attr' => [
                'class' => 'prototype',
            ],
        ];

        $builder->add('arguments', CollectionType::class, $argumentOptions);

        $builder->get('arguments')->addEventListener(
            FormEvents::SUBMIT,
            function (FormEvent $event): void {
                $argumentCollectionTransfer = new ArgumentCollectionTransfer();

                foreach ($event->getData() as $argumentInformation) {
                    $argumentTransfer = $argumentInformation['innerArgument'];
                    if ($argumentTransfer instanceof ArgumentTransfer) {
                        $argumentTransfer
                            ->setVariable($argumentInformation['variable'])
                            ->setIsOptional($argumentInformation['isOptional']);
                    }
                    $argumentCollectionTransfer->addArgument($argumentTransfer);
                }
                $event->setData($argumentCollectionTransfer->getArguments());
            },
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ModuleTransfer $moduleTransfer
     *
     * @return string
     */
    abstract protected function getClassName(ModuleTransfer $moduleTransfer): string;

    /**
     * @return string
     */
    abstract protected function getFactoryNamePostfix(): string;

    /**
     * @param \ArrayObject<\Generated\Shared\Transfer\MethodInformationTransfer> $methodCollection
     * @param \Generated\Shared\Transfer\ModuleTransfer $moduleTransfer
     *
     * @return \Generated\Shared\Transfer\ArgumentCollectionTransfer
     */
    protected function buildArguments(ArrayObject $methodCollection, ModuleTransfer $moduleTransfer): ArgumentCollectionTransfer
    {
        $argumentCollectionTransfer = new ArgumentCollectionTransfer();
        foreach ($methodCollection as $methodTransfer) {
            $argumentMetaTransfer = new ArgumentMetaTransfer();
            $argumentMetaTransfer->setMethod($methodTransfer->getName());

            $argumentTransfer = new ArgumentTransfer();
            $argumentTransfer->setName(sprintf(
                '%s (%s%s::%s())',
                $methodTransfer->getReturnTypeOrFail()->getName(),
                $moduleTransfer->getName(),
                $this->getFactoryNamePostfix(),
                $methodTransfer->getName(),
            ));
            $argumentTransfer->setType($methodTransfer->getReturnTypeOrFail()->getType());
            $argumentTransfer->setVariable($this->getVariableProposal($methodTransfer));

            $argumentTransfer->setArgumentMeta($argumentMetaTransfer);
            $argumentCollectionTransfer->addArgument($argumentTransfer);
        }

        return $argumentCollectionTransfer;
    }

    /**
     * @param array<string, mixed> $options
     *
     * @return \Generated\Shared\Transfer\ModuleTransfer
     */
    protected function getModuleTransfer(array $options): ModuleTransfer
    {
        return $options[static::MODULE];
    }

    /**
     * @param \Generated\Shared\Transfer\MethodInformationTransfer $methodTransfer
     *
     * @return string
     */
    protected function getVariableProposal(MethodInformationTransfer $methodTransfer): string
    {
        $typeFragments = explode('\\', $methodTransfer->getReturnTypeOrFail()->getNameOrFail());
        $classOrInterfaceName = array_pop($typeFragments);
        $classOrInterfaceName = str_replace('Interface', '', $classOrInterfaceName);

        return '$' . lcfirst($classOrInterfaceName);
    }
}

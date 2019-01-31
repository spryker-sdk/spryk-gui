<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\SprykGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\ApplicationTransfer;
use Generated\Shared\Transfer\LayerTransfer;
use Generated\Shared\Transfer\ModuleTransfer;
use Generated\Shared\Transfer\SprykDefinitionTransfer;
use Generated\Shared\Transfer\SprykRequestTransfer;
use SprykerSdk\Zed\SprykGui\Business\Spryk\Spryk;
use SprykerSdk\Zed\SprykGui\Business\SprykGuiFacadeInterface;

class SprykDataProvider
{
    /**
     * @var \SprykerSdk\Zed\SprykGui\Business\SprykGuiFacadeInterface
     */
    protected $sprykGuiFacade;

    /**
     * @param \SprykerSdk\Zed\SprykGui\Business\SprykGuiFacadeInterface $sprykGuiFacade
     */
    public function __construct(SprykGuiFacadeInterface $sprykGuiFacade)
    {
        $this->sprykGuiFacade = $sprykGuiFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\SprykDefinitionTransfer $sprykDefinitionTransfer
     * @param \Generated\Shared\Transfer\ModuleTransfer|null $moduleTransfer
     *
     * @return array
     */
    public function getOptions(SprykDefinitionTransfer $sprykDefinitionTransfer, ?ModuleTransfer $moduleTransfer = null): array
    {
        $options = [];
        $options['allow_extra_fields'] = true;
        $options['auto_initialize'] = false;

        if ($sprykDefinitionTransfer->getName()) {
            $options['spryk'] = $sprykDefinitionTransfer->getName();
        }

        if ($sprykDefinitionTransfer->getName() && $moduleTransfer) {
            $options['module'] = $moduleTransfer;
            $options += $this->getOptionsBySprykDefinition($sprykDefinitionTransfer, $moduleTransfer);
        }

        return $options;
    }

    /**
     * @param \Generated\Shared\Transfer\SprykDefinitionTransfer $sprykDefinitionTransfer
     * @param \Generated\Shared\Transfer\ModuleTransfer $moduleTransfer
     *
     * @return array
     */
    protected function getOptionsBySprykDefinition(SprykDefinitionTransfer $sprykDefinitionTransfer, ModuleTransfer $moduleTransfer): array
    {
        $sprykDefinition = $this->sprykGuiFacade->getSprykDefinition(
            $sprykDefinitionTransfer->getName(),
            $sprykDefinitionTransfer->getMode()
        );

        if (isset($sprykDefinition[ModuleTransfer::APPLICATION])) {
            $applicationTransfer = new ApplicationTransfer();
            $applicationTransfer->setName($sprykDefinition[ModuleTransfer::APPLICATION]);
            $moduleTransfer->setApplication($applicationTransfer);
        }
        if (isset($sprykDefinition[ModuleTransfer::LAYER])) {
            $layerTransfer = new LayerTransfer();
            $layerTransfer->setName($sprykDefinition[ModuleTransfer::LAYER]);
            $moduleTransfer->setLayer($layerTransfer);
        }

        $moduleTransfer = $this->sprykGuiFacade->buildOptions($moduleTransfer);
        $optionTransfer = $moduleTransfer->requireOptions()->getOptions();

        $sprykOptions = [];

        if (array_key_exists('input', $sprykDefinition['arguments']) || array_key_exists('constructorArguments', $sprykDefinition['arguments'])) {
            $argumentCollectionTransfer = $optionTransfer->getArgumentCollection();
            $sprykOptions['argumentChoices'] = $argumentCollectionTransfer->getArguments();
        }

        if (array_key_exists('output', $sprykDefinition['arguments'])) {
            $returnTypeCollectionTransfer = $optionTransfer->getReturnTypeCollection();
            $sprykOptions['outputChoices'] = $returnTypeCollectionTransfer->getReturnTypes();
        }

        return $sprykOptions;
    }

    /**
     * @param \Generated\Shared\Transfer\SprykDefinitionTransfer $sprykDefinitionTransfer
     * @param \Generated\Shared\Transfer\ModuleTransfer|null $moduleTransfer
     *
     * @return array
     */
    public function getData(SprykDefinitionTransfer $sprykDefinitionTransfer, ?ModuleTransfer $moduleTransfer = null): array
    {
        if (!$moduleTransfer) {
            $moduleTransfer = new ModuleTransfer();
        }

        $formData = [
            'spryk' => $sprykDefinitionTransfer->getName(),
            'module' => $moduleTransfer,
            'dependentModule' => $moduleTransfer,
        ];

        return $this->addSprykDefinitionDefaultData($formData, $sprykDefinitionTransfer);
    }

    /**
     * @param array $formData
     * @param \Generated\Shared\Transfer\SprykDefinitionTransfer $sprykDefinitionTransfer
     *
     * @return array
     */
    protected function addSprykDefinitionDefaultData(array $formData, SprykDefinitionTransfer $sprykDefinitionTransfer): array
    {
        $sprykDefinition = $this->sprykGuiFacade->getSprykDefinition(
            $sprykDefinitionTransfer->getName(),
            $sprykDefinitionTransfer->getMode()
        );

        $formData['mode'] = $sprykDefinition['mode'];

        foreach ($sprykDefinition['arguments'] as $argumentName => $argumentDefinition) {
            if (isset($argumentDefinition['default'])) {
                $formData[$argumentName] = $argumentDefinition['default'];
            }
        }

        return $formData;
    }
}

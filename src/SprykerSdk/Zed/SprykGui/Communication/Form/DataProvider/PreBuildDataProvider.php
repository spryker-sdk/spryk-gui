<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\SprykGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\SprykDefinitionTransfer;
use SprykerSdk\Zed\SprykGui\Business\SprykGuiFacadeInterface;
use SprykerSdk\Zed\SprykGui\Communication\Form\PreBuildForm;

class PreBuildDataProvider
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
     *
     * @return array
     */
    public function getData(SprykDefinitionTransfer $sprykDefinitionTransfer): array
    {
        $sprykDefinition = $this->sprykGuiFacade->getSprykDefinition(
            $sprykDefinitionTransfer->getName(),
            $sprykDefinitionTransfer->getMode()
        );

        $formData[PreBuildForm::FIELD_MODE] = $sprykDefinition[PreBuildForm::FIELD_MODE];
        $formData[PreBuildForm::FIELD_ENTER_MODULE_MANUALLY] = isset($sprykDefinition['arguments']['module']['type'])
            && $sprykDefinition['arguments']['module']['type'] === 'NewModuleType';

        return $formData;
    }
}

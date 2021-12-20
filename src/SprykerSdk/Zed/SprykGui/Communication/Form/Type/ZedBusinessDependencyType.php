<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\SprykGui\Communication\Form\Type;

use Generated\Shared\Transfer\ModuleTransfer;

/**
 * @method \SprykerSdk\Zed\SprykGui\Business\SprykGuiFacadeInterface getFacade()
 * @method \SprykerSdk\Zed\SprykGui\SprykGuiConfig getConfig()
 * @method \SprykerSdk\Zed\SprykGui\Communication\SprykGuiCommunicationFactory getFactory()
 */
class ZedBusinessDependencyType extends AbstractFactoryConstructorType
{
    /**
     * @param \Generated\Shared\Transfer\ModuleTransfer $moduleTransfer
     *
     * @return string
     */
    protected function getClassName(ModuleTransfer $moduleTransfer): string
    {
        return sprintf(
            '\%1$s\Zed\%2$s\Business\%2$s%3$s',
            $moduleTransfer->getOrganizationOrFail()->getName(),
            $moduleTransfer->getName(),
            $this->getFactoryNamePostfix(),
        );
    }

    /**
     * @return string
     */
    protected function getFactoryNamePostfix(): string
    {
        return 'BusinessFactory';
    }
}

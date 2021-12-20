<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\SprykGui\Business\ChoiceLoader\Service;

use Generated\Shared\Transfer\ModuleTransfer;
use ReflectionMethod;
use SprykerSdk\Zed\SprykGui\Business\ChoiceLoader\Common\AbstractMethodChoiceLoader;

class ServiceMethodChoiceLoader extends AbstractMethodChoiceLoader
{
    /**
     * @param \Generated\Shared\Transfer\ModuleTransfer $moduleTransfer
     *
     * @return string
     */
    protected function getClassName(ModuleTransfer $moduleTransfer): string
    {
        $dependentModule = $moduleTransfer->getDependentModuleOrFail();

        return sprintf(
            '%1$s\\Service\\%2$s\\%2$sService',
            $dependentModule->getOrganizationOrFail()->getNameOrFail(),
            $dependentModule->getNameOrFail(),
        );
    }

    /**
     * @param \ReflectionMethod $reflectionMethod
     *
     * @return bool
     */
    protected function acceptMethod(ReflectionMethod $reflectionMethod): bool
    {
        return $reflectionMethod->isPublic();
    }

    /**
     * @param \Generated\Shared\Transfer\ModuleTransfer $moduleTransfer
     * @param \ReflectionMethod $reflectionMethod
     *
     * @return string
     */
    protected function buildChoiceLabel(ModuleTransfer $moduleTransfer, ReflectionMethod $reflectionMethod): string
    {
        return sprintf('%sService::%s()', $moduleTransfer->getDependentModuleOrFail()->getName(), $reflectionMethod->getName());
    }
}

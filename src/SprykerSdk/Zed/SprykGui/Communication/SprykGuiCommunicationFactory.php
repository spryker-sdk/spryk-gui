<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\SprykGui\Communication;

use Generated\Shared\Transfer\ModuleTransfer;
use Generated\Shared\Transfer\SprykDefinitionTransfer;
use Generated\Shared\Transfer\SprykRequestTransfer;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use SprykerSdk\Zed\SprykGui\Communication\Form\DataProvider\SprykDataProvider;
use SprykerSdk\Zed\SprykGui\Communication\Form\SprykDetailsForm;
use SprykerSdk\Zed\SprykGui\Communication\Form\SprykMainForm;
use SprykerSdk\Zed\SprykGui\Dependency\Facade\SprykGuiToSprykFacadeInterface;
use SprykerSdk\Zed\SprykGui\SprykGuiDependencyProvider;
use Symfony\Component\Form\FormInterface;

/**
 * @method \SprykerSdk\Zed\SprykGui\Business\SprykGuiFacadeInterface getFacade()
 * @method \SprykerSdk\Zed\SprykGui\SprykGuiConfig getConfig()
 */
class SprykGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \SprykerSdk\Zed\SprykGui\Communication\Form\DataProvider\SprykDataProvider
     */
    public function createSprykFormDataProvider(): SprykDataProvider
    {
        return new SprykDataProvider(
            $this->getFacade()
        );
    }

    /**
     * @return \SprykerSdk\Zed\SprykGui\Dependency\Facade\SprykGuiToSprykFacadeInterface
     */
    public function getSprykFacade(): SprykGuiToSprykFacadeInterface
    {
        return $this->getProvidedDependency(SprykGuiDependencyProvider::SPRYK_FACADE);
    }

    /**
     * @param \Generated\Shared\Transfer\SprykDefinitionTransfer $sprykDefinitionTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getSprykMainForm(SprykDefinitionTransfer $sprykDefinitionTransfer): FormInterface
    {
        return $this->getFormFactory()->create(
            SprykMainForm::class,
            $this->createSprykFormDataProvider()->getData($sprykDefinitionTransfer),
            $this->createSprykFormDataProvider()->getOptions($sprykDefinitionTransfer)
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ModuleTransfer $moduleTransfer
     * @param string $spryk
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getSprykDetailsForm(ModuleTransfer $moduleTransfer, string $spryk): FormInterface
    {
        return $this->getFormFactory()->create(
            SprykDetailsForm::class,
            $this->createSprykFormDataProvider()->getData($spryk, $moduleTransfer),
            [
            //                'module' => $moduleTransfer,
                'spryk' => $spryk,
            ]
        );
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SprykGui\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\SprykGui\Communication\Form\DataProvider\SprykDataProvider;
use Spryker\Zed\SprykGui\Communication\Form\SprykForm;
use Spryker\Zed\SprykGui\Dependency\Facade\SprykGuiToSprykFacadeInterface;
use Spryker\Zed\SprykGui\SprykGuiDependencyProvider;
use Symfony\Component\Form\FormInterface;

/**
 * @method \Spryker\Zed\SprykGui\SprykGuiConfig getConfig()
 */
class SprykGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\SprykGui\Communication\Form\DataProvider\SprykDataProvider
     */
    public function createSprykFormDataProvider(): SprykDataProvider
    {
        return new SprykDataProvider(
            $this->getSprykFacade()
        );
    }

    /**
     * @return \Spryker\Zed\SprykGui\Dependency\Facade\SprykGuiToSprykFacadeInterface
     */
    public function getSprykFacade(): SprykGuiToSprykFacadeInterface
    {
        return $this->getProvidedDependency(SprykGuiDependencyProvider::SPRYK_FACADE);
    }

    /**
     * @param string $spryk
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getSprykForm(string $spryk): FormInterface
    {
        return $this->getFormFactory()->create(
            SprykForm::class,
            $this->createSprykFormDataProvider()->getData(),
            $this->createSprykFormDataProvider()->getOptions($spryk)
        );
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\SprykGui\Business;

use Generated\Shared\Transfer\AccessibleTransferCollectionTransfer;
use Generated\Shared\Transfer\ClassInformationTransfer;
use Generated\Shared\Transfer\ModuleFilterTransfer;
use Generated\Shared\Transfer\ModuleTransfer;
use Generated\Shared\Transfer\OrganizationCollectionTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \SprykerSdk\Zed\SprykGui\Business\SprykGuiBusinessFactory getFactory()
 */
class SprykGuiFacade extends AbstractFacade implements SprykGuiFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $sprykName
     * @param array $sprykArguments
     *
     * @return array
     */
    public function buildSprykView(string $sprykName, array $sprykArguments): array
    {
        return $this->getFactory()->createSpryk()->buildSprykView($sprykName, $sprykArguments);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return array
     */
    public function getSprykDefinitions(): array
    {
        return $this->getFactory()->createSpryk()->getSprykDefinitions();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $sprykName
     *
     * @return string
     */
    public function drawSpryk(string $sprykName): string
    {
        return $this->getFactory()->createSpryk()->drawSpryk($sprykName);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $sprykName
     * @param array $sprykArguments
     *
     * @return string
     */
    public function runSpryk(string $sprykName, array $sprykArguments): string
    {
        return $this->getFactory()->createSpryk()->runSpryk($sprykName, $sprykArguments);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ModuleFilterTransfer|null $moduleFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ModuleTransfer[]
     */
    public function getModules(?ModuleFilterTransfer $moduleFilterTransfer = null): array
    {
        return $this->getFactory()->createModuleFinder()->findModules($moduleFilterTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\OrganizationCollectionTransfer
     */
    public function getOrganizations(): OrganizationCollectionTransfer
    {
        return $this->getFactory()->createOrganizationFinder()->findOrganizations();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $module
     *
     * @return \Generated\Shared\Transfer\AccessibleTransferCollectionTransfer
     */
    public function getAccessibleTransfers(string $module): AccessibleTransferCollectionTransfer
    {
        return $this->getFactory()->createAccessibleTransferFinder()->findAccessibleTransfers($module);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $className
     *
     * @return \Generated\Shared\Transfer\ClassInformationTransfer
     */
    public function getFactoryInformation(string $className): ClassInformationTransfer
    {
        return $this->getFactory()->createFactoryInformationFinder()->findFactoryInformation($className);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $spryk
     *
     * @return array
     */
    public function getSprykDefinitionByName(string $spryk): array
    {
        return $this->getFactory()->createSpryk()->getSprykDefinitionByName($spryk);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ModuleTransfer $moduleTransfer
     *
     * @return \Generated\Shared\Transfer\ModuleTransfer
     */
    public function buildOptions(ModuleTransfer $moduleTransfer): ModuleTransfer
    {
        return $this->getFactory()->createOptionBuilder()->build($moduleTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $choiceLoaderName
     * @param \Generated\Shared\Transfer\ModuleTransfer $moduleTransfer
     *
     * @return array
     */
    public function loadChoices(string $choiceLoaderName, ModuleTransfer $moduleTransfer): array
    {
        return $this->getFactory()->createChoiceLoader()->loadChoices($choiceLoaderName, $moduleTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $sprykName
     * @param string|null $sprykMode
     *
     * @return array
     */
    public function getSprykDefinition(string $sprykName, ?string $sprykMode = null): array
    {
        return $this->getFactory()->getSprykFacade()->getSprykDefinition($sprykName, $sprykMode);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $developmentMode
     *
     * @return \Generated\Shared\Transfer\OrganizationCollectionTransfer
     */
    public function getOrganizationsByMode(string $developmentMode): OrganizationCollectionTransfer
    {
        return $this->getFactory()
            ->createOrganizationFinder()
            ->findOrganizationsByMode($developmentMode);
    }
}

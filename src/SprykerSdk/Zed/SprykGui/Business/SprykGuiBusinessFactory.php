<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\SprykGui\Business;

use Spryker\Zed\Graph\Communication\Plugin\GraphPlugin;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use SprykerSdk\Zed\SprykGui\Business\ChoiceLoader\ChoiceLoaderComposite;
use SprykerSdk\Zed\SprykGui\Business\ChoiceLoader\ChoiceLoaderCompositeInterface;
use SprykerSdk\Zed\SprykGui\Business\ChoiceLoader\ChoiceLoaderInterface;
use SprykerSdk\Zed\SprykGui\Business\ChoiceLoader\Client\ClientMethodChoiceLoader;
use SprykerSdk\Zed\SprykGui\Business\ChoiceLoader\Service\ServiceMethodChoiceLoader;
use SprykerSdk\Zed\SprykGui\Business\ChoiceLoader\Zed\Business\Model\ZedBusinessModelChoiceLoader;
use SprykerSdk\Zed\SprykGui\Business\ChoiceLoader\Zed\Business\ZedFacadeMethodChoiceLoader;
use SprykerSdk\Zed\SprykGui\Business\ChoiceLoader\Zed\Communication\Controller\ZedCommunicationControllerChoiceLoader;
use SprykerSdk\Zed\SprykGui\Business\Finder\AccessibleTransfer\AccessibleTransferFinder;
use SprykerSdk\Zed\SprykGui\Business\Finder\AccessibleTransfer\AccessibleTransferFinderInterface;
use SprykerSdk\Zed\SprykGui\Business\Finder\Factory\FactoryInfoFinder;
use SprykerSdk\Zed\SprykGui\Business\Finder\Factory\FactoryInfoFinderInterface;
use SprykerSdk\Zed\SprykGui\Business\Finder\Module\ModuleFinder;
use SprykerSdk\Zed\SprykGui\Business\Finder\Module\ModuleFinderInterface;
use SprykerSdk\Zed\SprykGui\Business\Finder\Organization\OrganizationFinder;
use SprykerSdk\Zed\SprykGui\Business\Finder\Organization\OrganizationFinderInterface;
use SprykerSdk\Zed\SprykGui\Business\Graph\GraphBuilder;
use SprykerSdk\Zed\SprykGui\Business\Graph\GraphBuilderInterface;
use SprykerSdk\Zed\SprykGui\Business\Option\Argument\ArgumentOptionBuilder;
use SprykerSdk\Zed\SprykGui\Business\Option\ClassName\ClassNameOptionBuilder;
use SprykerSdk\Zed\SprykGui\Business\Option\OptionBuilder;
use SprykerSdk\Zed\SprykGui\Business\Option\OptionBuilderInterface;
use SprykerSdk\Zed\SprykGui\Business\Option\Output\ModuleOutputOptionBuilder;
use SprykerSdk\Zed\SprykGui\Business\PhpInternal\Type\Type;
use SprykerSdk\Zed\SprykGui\Business\PhpInternal\Type\TypeInterface;
use SprykerSdk\Zed\SprykGui\Business\Spryk\Spryk;
use SprykerSdk\Zed\SprykGui\Business\Spryk\SprykInterface;
use SprykerSdk\Zed\SprykGui\Dependency\Facade\SprykGuiToDevelopmentFacadeInterface;
use SprykerSdk\Zed\SprykGui\Dependency\Facade\SprykGuiToSprykFacadeInterface;
use SprykerSdk\Zed\SprykGui\SprykGuiDependencyProvider;

/**
 * @method \SprykerSdk\Zed\SprykGui\SprykGuiConfig getConfig()
 * @method \SprykerSdk\Zed\SprykGui\Business\SprykGuiBusinessFactory getFactory()
 */
class SprykGuiBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \SprykerSdk\Zed\SprykGui\Business\Spryk\SprykInterface
     */
    public function createSpryk(): SprykInterface
    {
        return new Spryk(
            $this->getSprykFacade(),
            $this->createGraphBuilder()
        );
    }

    /**
     * @return \SprykerSdk\Zed\SprykGui\Business\Graph\GraphBuilderInterface
     */
    public function createGraphBuilder(): GraphBuilderInterface
    {
        return new GraphBuilder(
            $this->getSprykFacade(),
            $this->getGraphPlugin()
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
     * @return \Spryker\Zed\Graph\Communication\Plugin\GraphPlugin
     */
    public function getGraphPlugin(): GraphPlugin
    {
        return $this->getProvidedDependency(SprykGuiDependencyProvider::PLUGIN_GRAPH);
    }

    /**
     * @return \SprykerSdk\Zed\SprykGui\Business\Finder\Module\ModuleFinderInterface
     */
    public function createModuleFinder(): ModuleFinderInterface
    {
        return new ModuleFinder($this->getDevelopmentFacade());
    }

    /**
     * @return \SprykerSdk\Zed\SprykGui\Dependency\Facade\SprykGuiToDevelopmentFacadeInterface
     */
    public function getDevelopmentFacade(): SprykGuiToDevelopmentFacadeInterface
    {
        return $this->getProvidedDependency(SprykGuiDependencyProvider::DEVELOPMENT_FACADE);
    }

    /**
     * @return \SprykerSdk\Zed\SprykGui\Business\Finder\Organization\OrganizationFinderInterface
     */
    public function createOrganizationFinder(): OrganizationFinderInterface
    {
        return new OrganizationFinder(
            $this->getConfig()->getCoreNamespaces(),
            $this->getConfig()->getProjectNamespaces()
        );
    }

    /**
     * @return \SprykerSdk\Zed\SprykGui\Business\Finder\AccessibleTransfer\AccessibleTransferFinderInterface
     */
    public function createAccessibleTransferFinder(): AccessibleTransferFinderInterface
    {
        return new AccessibleTransferFinder();
    }

    /**
     * @return \SprykerSdk\Zed\SprykGui\Business\Finder\Factory\FactoryInfoFinderInterface
     */
    public function createFactoryInformationFinder(): FactoryInfoFinderInterface
    {
        return new FactoryInfoFinder();
    }

    /**
     * @return \SprykerSdk\Zed\SprykGui\Business\Option\OptionBuilderInterface
     */
    public function createOptionBuilder(): OptionBuilderInterface
    {
        return new OptionBuilder([
            $this->createClassNameOptionBuilder(),
            $this->createOutputOptionBuilder(),
            $this->createArgumentOptionBuilder(),
        ]);
    }

    /**
     * @return \SprykerSdk\Zed\SprykGui\Business\Option\OptionBuilderInterface
     */
    public function createClassNameOptionBuilder(): OptionBuilderInterface
    {
        return new ClassNameOptionBuilder();
    }

    /**
     * @return \SprykerSdk\Zed\SprykGui\Business\Option\OptionBuilderInterface
     */
    public function createOutputOptionBuilder(): OptionBuilderInterface
    {
        return new ModuleOutputOptionBuilder(
            $this->createAccessibleTransferFinder(),
            $this->createPhpInternalTypes()
        );
    }

    /**
     * @return \SprykerSdk\Zed\SprykGui\Business\Option\OptionBuilderInterface
     */
    public function createArgumentOptionBuilder(): OptionBuilderInterface
    {
        return new ArgumentOptionBuilder(
            $this->createAccessibleTransferFinder(),
            $this->createPhpInternalTypes()
        );
    }

    /**
     * @return \SprykerSdk\Zed\SprykGui\Business\PhpInternal\Type\TypeInterface
     */
    public function createPhpInternalTypes(): TypeInterface
    {
        return new Type();
    }

    /**
     * @return \SprykerSdk\Zed\SprykGui\Business\ChoiceLoader\ChoiceLoaderCompositeInterface
     */
    public function createChoiceLoader(): ChoiceLoaderCompositeInterface
    {
        return new ChoiceLoaderComposite([
            $this->createZedBusinessModelLoader(),
            $this->createZedControllerChoiceLoader(),
            $this->createZedFacadeMethodChoiceLoader(),
            $this->createServiceMethodChoiceLoader(),
            $this->createClientMethodChoiceLoader(),
        ]);
    }

    /**
     * @return \SprykerSdk\Zed\SprykGui\Business\ChoiceLoader\ChoiceLoaderInterface
     */
    public function createZedBusinessModelLoader(): ChoiceLoaderInterface
    {
        return new ZedBusinessModelChoiceLoader();
    }

    /**
     * @return \SprykerSdk\Zed\SprykGui\Business\ChoiceLoader\ChoiceLoaderInterface
     */
    public function createZedControllerChoiceLoader(): ChoiceLoaderInterface
    {
        return new ZedCommunicationControllerChoiceLoader();
    }

    /**
     * @return \SprykerSdk\Zed\SprykGui\Business\ChoiceLoader\ChoiceLoaderInterface
     */
    public function createZedFacadeMethodChoiceLoader(): ChoiceLoaderInterface
    {
        return new ZedFacadeMethodChoiceLoader();
    }

    /**
     * @return \SprykerSdk\Zed\SprykGui\Business\ChoiceLoader\ChoiceLoaderInterface
     */
    public function createServiceMethodChoiceLoader(): ChoiceLoaderInterface
    {
        return new ServiceMethodChoiceLoader();
    }

    /**
     * @return \SprykerSdk\Zed\SprykGui\Business\ChoiceLoader\ChoiceLoaderInterface
     */
    public function createClientMethodChoiceLoader(): ChoiceLoaderInterface
    {
        return new ClientMethodChoiceLoader();
    }
}

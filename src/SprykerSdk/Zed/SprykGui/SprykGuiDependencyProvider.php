<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\SprykGui;

use Spryker\Zed\Graph\Communication\Plugin\GraphPlugin;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use SprykerSdk\Spryk\SprykFacade;
use SprykerSdk\Zed\SprykGui\Dependency\Facade\SprykGuiToDevelopmentFacadeBridge;
use SprykerSdk\Zed\SprykGui\Dependency\Facade\SprykGuiToSprykFacadeBridge;

/**
 * @method \SprykerSdk\Zed\SprykGui\SprykGuiConfig getConfig()
 */
class SprykGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const SPRYK_FACADE = 'SPRYK_FACADE';
    /**
     * @var string
     */
    public const DEVELOPMENT_FACADE = 'DEVELOPMENT_FACADE';
    /**
     * @var string
     */
    public const PLUGIN_GRAPH = 'PLUGIN_GRAPH';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = $this->addSprykFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = $this->addSprykFacade($container);
        $container = $this->addDevelopmentFacade($container);
        $container = $this->addGraphPlugin($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSprykFacade(Container $container): Container
    {
        $container->set(static::SPRYK_FACADE, function () {
            return new SprykGuiToSprykFacadeBridge(
                new SprykFacade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addDevelopmentFacade(Container $container): Container
    {
        $container->set(static::DEVELOPMENT_FACADE, function () use ($container) {
            return new SprykGuiToDevelopmentFacadeBridge(
                $container->getLocator()->development()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addGraphPlugin(Container $container): Container
    {
        $container->set(static::PLUGIN_GRAPH, function () {
            return new GraphPlugin();
        });

        return $container;
    }
}

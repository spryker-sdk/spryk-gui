<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\SprykGui;

use Spryker\Shared\Config\Config;
use Spryker\Shared\Kernel\KernelConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;
use SprykerSdk\Shared\SprykGui\SprykGuiConstants;

class SprykGuiConfig extends AbstractBundleConfig
{
    /**
     * @return array
     */
    public function getCoreNamespaces(): array
    {
        return Config::get(KernelConstants::CORE_NAMESPACES, []);
    }

    /**
     * @return array
     */
    public function getProjectNamespaces(): array
    {
        return Config::get(KernelConstants::PROJECT_NAMESPACES, []);
    }

    /**
     * @return bool
     */
    public function isSprykGuiEnabled(): bool
    {
        return $this->get(SprykGuiConstants::ENABLE_GUI, APPLICATION_ENV === 'production');
    }
}

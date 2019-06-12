<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\SprykGui;

use Spryker\Shared\Kernel\KernelConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class SprykGuiConfig extends AbstractBundleConfig
{
    /**
     * @return array
     */
    public function getCoreNamespaces(): array
    {
        return $this->get(KernelConstants::CORE_NAMESPACES, []);
    }

    /**
     * @return array
     */
    public function getProjectNamespaces(): array
    {
        return $this->get(KernelConstants::PROJECT_NAMESPACES, []);
    }

    /**
     * @deprecated Method will be removed without replacement.
     *
     * @return bool
     */
    public function isSprykWebInterfaceEnabled(): bool
    {
        return APPLICATION_ENV !== 'production';
    }
}

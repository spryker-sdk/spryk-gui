<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\SprykGui\Business\Finder\Module;

use Generated\Shared\Transfer\ModuleFilterTransfer;
use Generated\Shared\Transfer\ModuleTransfer;

interface ModuleFinderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ModuleFilterTransfer|null $moduleFilterTransfer
     *
     * @return array<\Generated\Shared\Transfer\ModuleTransfer>
     */
    public function findModules(?ModuleFilterTransfer $moduleFilterTransfer = null): array;

    /**
     * @param \Generated\Shared\Transfer\ModuleTransfer $moduleTransfer
     *
     * @return \Generated\Shared\Transfer\ModuleTransfer|null
     */
    public function findModule(ModuleTransfer $moduleTransfer): ?ModuleTransfer;
}

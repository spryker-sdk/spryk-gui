<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\SprykGui\Dependency\Facade;

interface SprykGuiToSprykFacadeInterface
{
    /**
     * @param int|null $level
     *
     * @return array
     */
    public function getSprykDefinitions(?int $level = null): array;

    /**
     * @param string $sprykName
     * @param string|null $sprykMode
     *
     * @return array
     */
    public function getSprykDefinition(string $sprykName, ?string $sprykMode = null): array;
}

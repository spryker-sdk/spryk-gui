<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\SprykGui\Dependency\Facade;

class SprykGuiToSprykFacadeBridge implements SprykGuiToSprykFacadeInterface
{
    /**
     * @var \SprykerSdk\Spryk\SprykFacadeInterface
     */
    protected $sprykFacade;

    /**
     * @param \SprykerSdk\Spryk\SprykFacadeInterface $sprykFacade
     */
    public function __construct($sprykFacade)
    {
        $this->sprykFacade = $sprykFacade;
    }

    /**
     * @return array
     */
    public function getSprykDefinitions(): array
    {
        return $this->sprykFacade->getSprykDefinitions();
    }
}

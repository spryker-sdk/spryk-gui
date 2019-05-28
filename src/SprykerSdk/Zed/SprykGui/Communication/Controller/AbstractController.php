<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\SprykGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController as SprykerAbstractController;

/**
 * @method \SprykerSdk\Zed\SprykGui\Communication\SprykGuiCommunicationFactory getFactory()
 * @method \SprykerSdk\Zed\SprykGui\Business\SprykGuiFacadeInterface getFacade()
 */
class AbstractController extends SprykerAbstractController
{
    /**
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();

        $this->getFactory()->createRequestValidator()->assertNonProductionEnvironment();
    }
}

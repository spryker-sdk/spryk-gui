<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\SprykGui\Communication\Controller;

use Spryker\Shared\Config\Environment;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController as SprykerAbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

        $this->assertNonProductionEnvironment();
    }

    /**
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return void
     */
    protected function assertNonProductionEnvironment(): void
    {
        $isProductionEnvironment = Environment::isProduction();
        $isCli = PHP_SAPI === 'cli';

        if (!$isProductionEnvironment || $isCli) {
            return;
        }

        throw new NotFoundHttpException(
            'Spryk available only on Development environment. Include this module as require-dev dependency in your composer file for security reasons.'
        );
    }
}

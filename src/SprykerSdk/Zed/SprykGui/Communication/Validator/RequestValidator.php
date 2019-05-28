<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\SprykGui\Communication\Validator;

use SprykerSdk\Zed\SprykGui\SprykGuiConfig;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RequestValidator implements RequestValidatorInterface
{
    /**
     * @var \SprykerSdk\Zed\SprykGui\SprykGuiConfig
     */
    protected $spryGuiConfig;

    /**
     * @param \SprykerSdk\Zed\SprykGui\SprykGuiConfig $spryGuiConfig
     */
    public function __construct(SprykGuiConfig $spryGuiConfig)
    {
        $this->spryGuiConfig = $spryGuiConfig;
    }

    /**
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return void
     */
    public function assertNonProductionEnvironment(): void
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

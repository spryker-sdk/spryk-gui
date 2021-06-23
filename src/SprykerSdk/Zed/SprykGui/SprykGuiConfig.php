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
    protected const SPRYK_CATEGORY_NAME_TOP_LEVEL = 'Top Level Spryks';
    protected const SPRYK_CATEGORY_NAME_COMMON = 'Common';

    /**
     * @api
     *
     * @return array
     */
    public function getCoreNamespaces(): array
    {
        return $this->get(KernelConstants::CORE_NAMESPACES, []);
    }

    /**
     * @api
     *
     * @return array
     */
    public function getProjectNamespaces(): array
    {
        return $this->get(KernelConstants::PROJECT_NAMESPACES, []);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getTopLevelSprykCategoryName(): string
    {
        return static::SPRYK_CATEGORY_NAME_TOP_LEVEL;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getCommonSprykCategoryName(): string
    {
        return static::SPRYK_CATEGORY_NAME_COMMON;
    }

    /**
     * @api
     *
     * @return string[]
     */
    public function getSprykCategories(): array
    {
        return [
            static::SPRYK_CATEGORY_NAME_TOP_LEVEL,
            'Zed',
            'Client',
            'Yves',
            'Shared',
            'Service',
            'Glue',
            static::SPRYK_CATEGORY_NAME_COMMON,
        ];
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerGuiTest;

class FactoryStub
{
    /**
     * @return void
     */
    public function methodWithOneDocBlockReturnType()
    {
    }

    /**
     * @return int|string
     */
    public function methodWithPipedDocBlockReturnTypes()
    {
        return '';
    }

    /**
     * @return void
     */
    public function methodWithReturnType(): void
    {
    }
}

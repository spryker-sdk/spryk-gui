<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\SprykGui\Business\Finder\AccessibleTransfer;

use Generated\Shared\Transfer\AccessibleClassNameCollectionTransfer;

interface AccessibleTransferFinderInterface
{
    /**
     * @param string $module
     *
     * @return \Generated\Shared\Transfer\AccessibleClassNameCollectionTransfer
     */
    public function findAccessibleTransfers(string $module): AccessibleClassNameCollectionTransfer;
}

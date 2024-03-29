<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\SprykGui\Business\Finder\AccessibleTransfer;

use Generated\Shared\Transfer\AccessibleClassNameCollectionTransfer;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class AccessibleTransferFinder implements AccessibleTransferFinderInterface
{
    /**
     * @param string $module
     *
     * @return \Generated\Shared\Transfer\AccessibleClassNameCollectionTransfer
     */
    public function findAccessibleTransfers(string $module): AccessibleClassNameCollectionTransfer
    {
        $transferClassNameCollection = new AccessibleClassNameCollectionTransfer();

        $finder = new Finder();

        $finder->in(APPLICATION_ROOT_DIR . DIRECTORY_SEPARATOR . 'src/Generated/Shared/Transfer/')->contains('/@module(?:[\sA-Za-z|]*)(' . $module . ')/');
        foreach ($finder as $fileInfo) {
            $transferClassName = $this->getTransferClassName($fileInfo);
            $transferClassNameCollection->addTransferClassName($transferClassName);
        }

        return $transferClassNameCollection;
    }

    /**
     * @param \Symfony\Component\Finder\SplFileInfo $fileInfo
     *
     * @return string
     */
    private function getTransferClassName(SplFileInfo $fileInfo): string
    {
        return sprintf('\\Generated\\Shared\Transfer\\%s', str_replace('.php', '', $fileInfo->getFilename()));
    }
}

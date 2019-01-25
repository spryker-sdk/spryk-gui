<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\SprykGui\Business\Option\Argument;

use Generated\Shared\Transfer\ArgumentCollectionTransfer;
use Generated\Shared\Transfer\ArgumentTransfer;
use Generated\Shared\Transfer\ModuleTransfer;
use SprykerSdk\Zed\SprykGui\Business\Finder\AccessibleTransfer\AccessibleTransferFinderInterface;
use SprykerSdk\Zed\SprykGui\Business\Option\OptionBuilderInterface;
use SprykerSdk\Zed\SprykGui\Business\PhpInternal\Type\TypeInterface;

class ArgumentOptionBuilder implements OptionBuilderInterface
{
    /**
     * @var \SprykerSdk\Zed\SprykGui\Business\Finder\AccessibleTransfer\AccessibleTransferFinderInterface
     */
    protected $accessibleTransferFinder;

    /**
     * @var \SprykerSdk\Zed\SprykGui\Business\PhpInternal\Type\TypeInterface
     */
    protected $types;

    /**
     * @param \SprykerSdk\Zed\SprykGui\Business\Finder\AccessibleTransfer\AccessibleTransferFinderInterface $accessibleTransferFinder
     * @param \SprykerSdk\Zed\SprykGui\Business\PhpInternal\Type\TypeInterface $types
     */
    public function __construct(AccessibleTransferFinderInterface $accessibleTransferFinder, TypeInterface $types)
    {
        $this->accessibleTransferFinder = $accessibleTransferFinder;
        $this->types = $types;
    }

    /**
     * @param \Generated\Shared\Transfer\ModuleTransfer $moduleTransfer
     *
     * @return \Generated\Shared\Transfer\ModuleTransfer
     */
    public function build(ModuleTransfer $moduleTransfer): ModuleTransfer
    {
        $optionsTransfer = $moduleTransfer->requireOptions()->getOptions();
        $argumentCollectionTransfer = $optionsTransfer->getArgumentCollection();
        if (!$argumentCollectionTransfer) {
            $argumentCollectionTransfer = new ArgumentCollectionTransfer();
        }

        $accessibleTransferCollection = $this->accessibleTransferFinder->findAccessibleTransfers($moduleTransfer->getName());
        foreach ($accessibleTransferCollection->getTransferClassNames() as $transferClassName) {
            $classNameFragments = explode('\\', $transferClassName);
            $classNameShort = array_pop($classNameFragments);
            $argumentTransfer = new ArgumentTransfer();
            $argumentTransfer
                ->setName($classNameShort)
                ->setType($transferClassName);

            $argumentCollectionTransfer->addArgument($argumentTransfer);
        }

        foreach ($this->types->getTypes() as $type) {
            $argumentTransfer = new ArgumentTransfer();
            $argumentTransfer
                ->setName($type)
                ->setType($type);

            $argumentCollectionTransfer->addArgument($argumentTransfer);
        }

        $optionsTransfer->setArgumentCollection($argumentCollectionTransfer);
        $moduleTransfer->setOptions($optionsTransfer);

        return $moduleTransfer;
    }
}

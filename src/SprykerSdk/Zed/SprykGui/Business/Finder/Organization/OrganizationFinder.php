<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\SprykGui\Business\Finder\Organization;

use Generated\Shared\Transfer\OrganizationCollectionTransfer;
use Generated\Shared\Transfer\OrganizationTransfer;

class OrganizationFinder implements OrganizationFinderInterface
{
    protected const NAME_DEVELOPMENT_LAYER_CORE = 'core';
    protected const NAME_DEVELOPMENT_LAYER_PROJECT = 'project';

    /**
     * @var array
     */
    protected $coreOrganizationList;

    /**
     * @var array
     */
    protected $projectOrganizationList;

    /**
     * @param array $coreOrganizationList
     * @param array $projectOrganizationList
     */
    public function __construct(array $coreOrganizationList, array $projectOrganizationList)
    {
        $this->coreOrganizationList = $coreOrganizationList;
        $this->projectOrganizationList = $projectOrganizationList;
    }

    /**
     * @var array
     */
    protected $organizationDefinition = [
        'Spryker' => 'spryker/spryker/',
        'SprykerEco' => 'spryker-eco/',
        'SprykerShop' => 'spryker/spryker-shop/',
    ];

    /**
     * @return \Generated\Shared\Transfer\OrganizationCollectionTransfer
     */
    public function findOrganizations(): OrganizationCollectionTransfer
    {
        $organizationCollectionTransfer = new OrganizationCollectionTransfer();

        foreach ($this->organizationDefinition as $organizationName => $subDirectory) {
            $organizationTransfer = new OrganizationTransfer();
            $organizationTransfer->setName($organizationName)
                ->setRootPath(APPLICATION_ROOT_DIR . '/vendor/' . $subDirectory);

            $organizationCollectionTransfer->addOrganization($organizationTransfer);
        }

        return $organizationCollectionTransfer;
    }

    /**
     * @param string $mode
     *
     * @return \Generated\Shared\Transfer\OrganizationCollectionTransfer
     */
    public function findOrganizationsByMode(string $mode): OrganizationCollectionTransfer
    {
        $organizationNamespaces = $mode === static::NAME_DEVELOPMENT_LAYER_PROJECT
            ? $this->projectOrganizationList
            : $this->coreOrganizationList;

        $organizationCollectionTransfer = new OrganizationCollectionTransfer();

        foreach ($organizationNamespaces as $organizationName ) {
            $organizationTransfer = new OrganizationTransfer();
            $organizationTransfer->setName($organizationName);

            $organizationCollectionTransfer->addOrganization($organizationTransfer);
        }

        return $organizationCollectionTransfer;
    }
}

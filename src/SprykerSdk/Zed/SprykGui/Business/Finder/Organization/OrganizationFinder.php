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
    /**
     * @var string
     */
    protected const NAME_DEVELOPMENT_LAYER_CORE = 'core';

    /**
     * @var string
     */
    protected const NAME_DEVELOPMENT_LAYER_PROJECT = 'project';

    /**
     * @var array<string>
     */
    protected $coreOrganizationList;

    /**
     * @var array<string>
     */
    protected $projectOrganizationList;

    /**
     * @param array<string> $coreOrganizationList
     * @param array<string> $projectOrganizationList
     */
    public function __construct(array $coreOrganizationList, array $projectOrganizationList)
    {
        $this->coreOrganizationList = $coreOrganizationList;
        $this->projectOrganizationList = $projectOrganizationList;
    }

    /**
     * @return \Generated\Shared\Transfer\OrganizationCollectionTransfer
     */
    public function findOrganizations(): OrganizationCollectionTransfer
    {
        return $this->getOrganizationCollectionTransfer($this->coreOrganizationList, false);
    }

    /**
     * @param string $mode
     *
     * @return \Generated\Shared\Transfer\OrganizationCollectionTransfer
     */
    public function findOrganizationsByMode(string $mode): OrganizationCollectionTransfer
    {
        $isProjectDevelopmentLayer = $mode === static::NAME_DEVELOPMENT_LAYER_PROJECT;
        $organizationNamespaces = $isProjectDevelopmentLayer
            ? $this->projectOrganizationList
            : $this->coreOrganizationList;

        return $this->getOrganizationCollectionTransfer($organizationNamespaces, $isProjectDevelopmentLayer);
    }

    /**
     * @param array<string> $organizationNamespaces
     * @param bool $isProjectDevelopmentLayer
     *
     * @return \Generated\Shared\Transfer\OrganizationCollectionTransfer
     */
    protected function getOrganizationCollectionTransfer(
        array $organizationNamespaces,
        bool $isProjectDevelopmentLayer
    ): OrganizationCollectionTransfer {
        $organizationCollectionTransfer = new OrganizationCollectionTransfer();

        foreach ($organizationNamespaces as $organizationName) {
            $organizationTransfer = (new OrganizationTransfer())
                ->setIsProject($isProjectDevelopmentLayer)
                ->setName($organizationName);

            $organizationCollectionTransfer->addOrganization($organizationTransfer);
        }

        return $organizationCollectionTransfer;
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Zed\SprykGui\Communication\Controller;

use SprykerSdkTest\Zed\SprykGui\SprykGuiCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerSdkTest
 * @group Zed
 * @group SprykGui
 * @group Communication
 * @group Controller
 * @group ListControllerCest
 * Add your own group annotations below this line
 */
class ListControllerCest
{
    /**
     * @param \SprykerSdkTest\Zed\SprykGui\SprykGuiCommunicationTester $i
     *
     * @return void
     */
    public function listSpryks(SprykGuiCommunicationTester $i)
    {
        $i->amOnPage('/spryk-gui/list');
        $i->seeResponseCodeIs(200);
        $i->seeBreadcrumbNavigation('Maintenance / SprykGui');
        $i->see('Spryk', 'h2');
    }
}

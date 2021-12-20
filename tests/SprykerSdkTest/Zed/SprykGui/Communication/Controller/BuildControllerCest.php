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
 * @group BuildControllerCest
 * Add your own group annotations below this line
 */
class BuildControllerCest
{
    /**
     * @param \SprykerSdkTest\Zed\SprykGui\SprykGuiCommunicationTester $i
     *
     * @return void
     */
    public function openBuildSpryk(SprykGuiCommunicationTester $i): void
    {
        $i->amOnPage('/spryk-gui/build?spryk=AddZedBusinessFacadeMethod');
        $i->seeResponseCodeIs(200);
        $i->seeBreadcrumbNavigation('Maintenance / SprykGui / Build Spryk');
        $i->see('Spryk', 'h2');
    }

    /**
     * @param \SprykerSdkTest\Zed\SprykGui\SprykGuiCommunicationTester $i
     *
     * @return void
     */
    public function createSpryk(SprykGuiCommunicationTester $i): void
    {
        $i->amOnPage('/spryk-gui/build?spryk=AddZedBusinessFacadeMethod');
        $i->seeResponseCodeIs(200);
        $i->seeBreadcrumbNavigation('Maintenance / SprykGui / Build Spryk');
        $i->see('Spryk', 'h2');

        $input = [];
        $input[] = [
            'innerArgument' => 0,
            'variable' => '$fooBar',
            'defaultValue' => '',
        ];

        $firstSprykerModuleName = $i->grabTextFrom(
            'select#spryk_main_form_module optgroup[label=\'Spryker\'] option:nth-child(1)',
        );

        $formData = [
            'spryk_main_form' => [
                'module' => $firstSprykerModuleName,
                'organization' => 'Spryker',
                'sprykDetails' => [
                    'comment' => "Line one.\r\nLine two.",
                    'method' => 'addFooBar',
                    'input' => $input,
                    'output' => 'bool',
                ],
                'create' => true,
            ],
        ];
        $i->submitForm(['name' => 'spryk_main_form'], $formData);

        $i->seeResponseCodeIs(200);
        $i->see('Jira Template', 'h3');
    }
}

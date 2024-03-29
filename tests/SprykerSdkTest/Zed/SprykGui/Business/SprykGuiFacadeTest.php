<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Zed\SprykGui\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ModuleTransfer;
use Generated\Shared\Transfer\OrganizationTransfer;
use SprykerGuiTest\FactoryStub;

/**
 * Auto-generated group annotations
 *
 * @group SprykerSdkTest
 * @group Zed
 * @group SprykGui
 * @group Business
 * @group Facade
 * @group SprykGuiFacadeTest
 * Add your own group annotations below this line
 */
class SprykGuiFacadeTest extends Unit
{
    /**
     * @var \SprykerSdkTest\Zed\SprykGui\SprykGuiBusinessTester
     */
    protected $tester;

    /**
     * @skip Skipped for further investigation prioritisation and fix.
     *
     * @return void
     */
    public function testGetSprykDefinitionsReturnsListOfSpryks(): void
    {
        $sprykDefinitions = $this->tester->getSprykGuiFacade()->getSprykDefinitions();
        $this->assertIsArray($sprykDefinitions);
    }

    /**
     * @skip Skipped for further investigation prioritisation and fix.
     *
     * @return void
     */
    public function testBuildSprykViewReturnsCommandAndJiraTemplate(): void
    {
        $organizationTransfer = new OrganizationTransfer();
        $organizationTransfer->setName('Spryker')
            ->setRootPath(APPLICATION_ROOT_DIR . '/data/%module%/');

        $moduleTransfer = new ModuleTransfer();
        $moduleTransfer
            ->setName('FooBar')
            ->setOrganization($organizationTransfer);

        $userInput = [
            'module' => $moduleTransfer,
            'facadeMethod' => 'addFooBar',
            'input' => 'string $fooBar',
            'output' => 'bool',
            'comment' => "Specification:\r\n- Line one.\r\n- Line two.",
        ];
        $sprykView = $this->tester->getSprykGuiFacade()->buildSprykView('AddZedBusinessFacadeMethod', $userInput);
        $this->tester->assertCommandLine($sprykView);
        $this->tester->assertJiraTemplate($sprykView);
    }

    /**
     * @return void
     */
    public function testGetFactoryInformationFindsSingleReturnTypeFromDocblock(): void
    {
        // Act
        $classInformationTransfer = $this->tester->getSprykGuiFacade()->getFactoryInformation(FactoryStub::class);

        // Assert
        $this->tester->classInformationHasMethodWithReturnType('methodWithOneDocBlockReturnType', 'void', $classInformationTransfer);
    }

    /**
     * @return void
     */
    public function testGetFactoryInformationFindsPipedReturnTypeFromDocblock(): void
    {
        // Act
        $classInformationTransfer = $this->tester->getSprykGuiFacade()->getFactoryInformation(FactoryStub::class);

        // Assert
        $this->tester->classInformationHasMethodWithReturnType('methodWithPipedDocBlockReturnTypes', 'int|string', $classInformationTransfer);
    }

    /**
     * @return void
     */
    public function testGetFactoryInformationFindsReturnType(): void
    {
        // Act
        $classInformationTransfer = $this->tester->getSprykGuiFacade()->getFactoryInformation(FactoryStub::class);

        // Assert
        $this->tester->classInformationHasMethodWithReturnType('methodWithReturnType', 'void', $classInformationTransfer);
    }
}

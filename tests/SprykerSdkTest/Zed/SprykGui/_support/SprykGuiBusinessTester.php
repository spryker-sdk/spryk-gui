<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Zed\SprykGui;

use Codeception\Actor;
use Generated\Shared\Transfer\ClassInformationTransfer;
use Generated\Shared\Transfer\MethodInformationTransfer;
use SprykerSdk\Zed\SprykGui\Business\SprykGuiFacadeInterface;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */
class SprykGuiBusinessTester extends Actor
{
    use _generated\SprykGuiBusinessTesterActions;

    /**
     * @return \SprykerSdk\Zed\SprykGui\Business\SprykGuiFacadeInterface
     */
    public function getSprykGuiFacade(): SprykGuiFacadeInterface
    {
        return $this->getFacade();
    }

    /**
     * @param array $sprykView
     *
     * @return void
     */
    public function assertCommandLine(array $sprykView): void
    {
        $this->assertArrayHasKey('commandLine', $sprykView);

        $expectedCommandLine = 'vendor/bin/console spryk:run AddZedBusinessFacadeMethod  --organization=\'Spryker\' --module=\'FooBar\' --comment=\'Specification:\' --comment=\'- Line one.\' --comment=\'- Line two.\' --facadeMethod=\'addFooBar\' --input=\'string $fooBar\' --output=\'bool\' -n';
        $this->assertSame($expectedCommandLine, $sprykView['commandLine']);
    }

    /**
     * @param array $sprykView
     *
     * @return void
     */
    public function assertJiraTemplate(array $sprykView): void
    {
        $this->assertArrayHasKey('jiraTemplate', $sprykView);

        $expectedJiraTemplate = '
{code:title=AddZedBusinessFacadeMethod|theme=Midnight|linenumbers=true|collapse=true}
vendor/bin/console spryk:run AddZedBusinessFacadeMethod  --organization=\'Spryker\' --module=\'FooBar\' --comment=\'Specification:\' --comment=\'- Line one.\' --comment=\'- Line two.\' --facadeMethod=\'addFooBar\' --input=\'string $fooBar\' --output=\'bool\' -n

"organization"
// Spryker

"module"
// FooBar

"comment"
// Specification:
// - Line one.
// - Line two.

"facadeMethod"
// addFooBar

"input"
// string $fooBar

"output"
// bool

{code}';
        $this->assertSame($expectedJiraTemplate, $sprykView['jiraTemplate']);
    }

    /**
     * @param string $expectedMethodName
     * @param string $expectedReturnType
     * @param \Generated\Shared\Transfer\ClassInformationTransfer $classInformationTransfer
     *
     * @return void
     */
    public function classInformationHasMethodWithReturnType(
        string $expectedMethodName,
        string $expectedReturnType,
        ClassInformationTransfer $classInformationTransfer
    ): void {
        $methodInformationTransfer = $this->getMethodInformationTransferByMethodName($expectedMethodName, $classInformationTransfer);
        $this->assertInstanceOf(MethodInformationTransfer::class, $methodInformationTransfer, sprintf('Method with name "%s" not found.', $expectedMethodName));

        $this->assertSame($methodInformationTransfer->getReturnType()->getType(), $expectedReturnType, sprintf(
            'Method "%s" does not have the expected return type "%s" found "%s"',
            $expectedMethodName,
            $expectedReturnType,
            $methodInformationTransfer->getReturnType()->getType(),
        ));
    }

    /**
     * @param string $methodName
     * @param \Generated\Shared\Transfer\ClassInformationTransfer $classInformationTransfer
     *
     * @return \Generated\Shared\Transfer\MethodInformationTransfer|null
     */
    protected function getMethodInformationTransferByMethodName(
        string $methodName,
        ClassInformationTransfer $classInformationTransfer
    ): ?MethodInformationTransfer {
        foreach ($classInformationTransfer->getMethods() as $methodInformationTransfer) {
            if ($methodInformationTransfer->getName() !== $methodName) {
                continue;
            }

            return $methodInformationTransfer;
        }

        return null;
    }
}

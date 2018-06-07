<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SprykGui\Business\Finder\Factory;

use Generated\Shared\Transfer\ClassInformationTransfer;
use Generated\Shared\Transfer\MethodInformationTransfer;
use Generated\Shared\Transfer\ReturnTypeTransfer;
use Roave\BetterReflection\BetterReflection;
use Roave\BetterReflection\Reflection\ReflectionMethod;

class FactoryInfoFinder implements FactoryInfoFinderInterface
{
    /**
     * @var string[]
     */
    protected $methodsToFilter = [
        'provideExternalDependencies',
        'injectExternalDependencies',
        'setConfig',
        'resolveBundleConfig',
        'setContainer',
        'getProvidedDependency',
        'resolveDependencyProvider',
        'createDependencyProviderResolver',
        'createContainer',
        'createContainerGlobals',
        'resolveDependencyInjectorCollection',
        'createDependencyInjectorResolver',
        'overwriteForTesting',
        'setQueryContainer',
        'resolveQueryContainer',
        'getQueryContainerResolver',
        'getQueryContainer',
        'setRepository',
        'resolveRepository',
        'getRepositoryResolver',
        'getRepositoryResolver',
        'resolveEntityManager',
        'setEntityManager',
        'getEntityManagerResolver',
        'createContainerWithProvidedDependencies',
        'createDependencyInjector',
    ];

    /**
     * @param string $className
     *
     * @return \Generated\Shared\Transfer\ClassInformationTransfer
     */
    public function findFactoryInformation(string $className): ClassInformationTransfer
    {
        $betterReflection = new BetterReflection();
        $reflectedClass = $betterReflection->classReflector()->reflect($className);

        $classInformationTransfer = new ClassInformationTransfer();
        $classInformationTransfer->setName($className);

        foreach ($reflectedClass->getMethods() as $method) {
            if ($this->shouldIgnore($method)) {
                continue;
            }

            $methodInformationTransfer = new MethodInformationTransfer();
            $methodInformationTransfer
                ->setName($method->getName())
                ->setReturnType($this->getReturnType($method));

            $classInformationTransfer->addMethod($methodInformationTransfer);
        }

        return $classInformationTransfer;
    }

    /**
     * @param \Roave\BetterReflection\Reflection\ReflectionMethod $method
     *
     * @return bool
     */
    protected function shouldIgnore(ReflectionMethod $method)
    {
        if (in_array($method->getName(), $this->methodsToFilter)) {
            return true;
        }

        return false;
    }

    /**
     * @param \Roave\BetterReflection\Reflection\ReflectionMethod $method
     *
     * @return \Generated\Shared\Transfer\ReturnTypeTransfer
     */
    protected function getReturnType(ReflectionMethod $method): ReturnTypeTransfer
    {
        $returnTypeTransfer = new ReturnTypeTransfer();
        $returnTypeTransfer->setIsPhpSeven($method->hasReturnType());

        if ($method->hasReturnType()) {
            $returnTypeTransfer->setType($method->getReturnType());

            return $returnTypeTransfer;
        }

        $returnTypes = $method->getDocBlockReturnTypes();
        $returnStrings = [];
        foreach ($returnTypes as $returnType) {
            $returnStrings[] = $returnType->getFqsen()->__toString();
        }

        $returnTypeTransfer->setType(implode('|', $returnStrings));

        return $returnTypeTransfer;
    }
}

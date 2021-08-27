<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\SprykGui\Business\Finder\Factory;

use Generated\Shared\Transfer\ClassInformationTransfer;
use Generated\Shared\Transfer\MethodInformationTransfer;
use Generated\Shared\Transfer\ReturnTypeTransfer;
use PHPStan\BetterReflection\BetterReflection;
use PHPStan\BetterReflection\Reflection\ReflectionClass;
use PHPStan\BetterReflection\Reflection\ReflectionMethod;
use PHPStan\BetterReflection\Reflector\Exception\IdentifierNotFound;

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
        $classInformationTransfer = new ClassInformationTransfer();
        if (!$this->canReflect($className)) {
            return $classInformationTransfer;
        }

        $reflectedClass = $this->getReflectedClass($className);

        if (!($reflectedClass instanceof  ReflectionClass)) {
            return $classInformationTransfer;
        }

        $classInformationTransfer->setFullyQualifiedClassName($className);

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
     * @param string $className
     *
     * @return bool
     */
    protected function canReflect(string $className): bool
    {
        try {
            $betterReflection = new BetterReflection();
            $betterReflection->classReflector()->reflect($className);

            return true;
        } catch (IdentifierNotFound $exception) {
            return false;
        }
    }

    /**
     * @param \PHPStan\BetterReflection\Reflection\ReflectionMethod $method
     *
     * @return bool
     */
    protected function shouldIgnore(ReflectionMethod $method): bool
    {
        if (in_array($method->getName(), $this->methodsToFilter)) {
            return true;
        }

        return false;
    }

    /**
     * @param \PHPStan\BetterReflection\Reflection\ReflectionMethod $method
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
            $returnStrings[] = $returnType->__toString();
        }

        $returnTypeTransfer->setType(implode('|', $returnStrings));

        return $returnTypeTransfer;
    }

    /**
     * @param string $className
     *
     * @return \PHPStan\BetterReflection\Reflection\Reflection|\PHPStan\BetterReflection\Reflection\ReflectionClass
     */
    protected function getReflectedClass(string $className)
    {
        $betterReflection = new BetterReflection();
        $reflectedClass = $betterReflection->classReflector()->reflect($className);

        return $reflectedClass;
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\SprykGui\Business\Finder\Factory;

use Exception;
use Generated\Shared\Transfer\ClassInformationTransfer;
use Generated\Shared\Transfer\MethodInformationTransfer;
use Generated\Shared\Transfer\ReturnTypeTransfer;
use PHPStan\BetterReflection\BetterReflection;
use PHPStan\BetterReflection\Reflection\ReflectionClass;
use PHPStan\BetterReflection\Reflection\ReflectionMethod;

class FactoryInfoFinder implements FactoryInfoFinderInterface
{
    /**
     * @var array<string>
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

        if (!($reflectedClass instanceof ReflectionClass)) {
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
            $betterReflection->reflector()->reflectClass($className);

            return true;
        } catch (Exception $exception) {
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
            $returnTypeTransfer->setType((string)$method->getReturnType());

            return $returnTypeTransfer;
        }

        $returnTypeFromDocBlock = $this->getReturnTypeFromDocBlock($method);

        if ($returnTypeFromDocBlock) {
            $returnTypeTransfer->setType($returnTypeFromDocBlock);
        }

        return $returnTypeTransfer;
    }

    /**
     * @param \PHPStan\BetterReflection\Reflection\ReflectionMethod $method
     *
     * @return string|null
     */
    protected function getReturnTypeFromDocBlock(ReflectionMethod $method): ?string
    {
        $docBlock = $method->getDocComment();

        if (!$docBlock) {
            return null;
        }

        preg_match('/(@return\s+)(\S+)/', $docBlock, $matches);

        if (!$matches) {
            return null;
        }

        $returnTypes = $matches[2];

        return trim($returnTypes);
    }

    /**
     * @param string $className
     *
     * @return \PHPStan\BetterReflection\Reflection\Reflection|\PHPStan\BetterReflection\Reflection\ReflectionClass
     */
    protected function getReflectedClass(string $className)
    {
        $betterReflection = new BetterReflection();
        $reflectedClass = $betterReflection->reflector()->reflectClass($className);

        return $reflectedClass;
    }
}

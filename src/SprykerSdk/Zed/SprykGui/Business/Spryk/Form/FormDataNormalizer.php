<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\SprykGui\Business\Spryk\Form;

use Generated\Shared\Transfer\ArgumentCollectionTransfer;
use Generated\Shared\Transfer\ArgumentTransfer;
use Generated\Shared\Transfer\ClassInformationTransfer;
use Generated\Shared\Transfer\ModuleTransfer;
use Generated\Shared\Transfer\OrganizationTransfer;
use Generated\Shared\Transfer\ReturnTypeTransfer;

class FormDataNormalizer implements FormDataNormalizerInterface
{
    /**
     * @param array<string, mixed> $formData
     *
     * @return array<string, mixed>
     */
    public function normalizeFormData(array $formData): array
    {
        return $this->normalizeFormDataRecursive($formData, []);
    }

    /**
     * @param array<string, mixed> $data
     * @param array<string, mixed> $normalizedData
     *
     * @return array
     */
    protected function normalizeFormDataRecursive(array $data, array $normalizedData): array
    {
        $organizationTransfer = $this->findOrganizationTransfer($data);

        if ($organizationTransfer) {
            $normalizedData['organization'] = $organizationTransfer->getName();
            $normalizedData['rootPath'] = $organizationTransfer->getRootPath();
        }

        foreach ($data as $key => $value) {
            if ($key === 'spryk' || isset($normalizedData[$key])) {
                continue;
            }

            if ($key === 'sprykDetails') {
                $normalizedData = $this->normalizeFormDataRecursive($value, $normalizedData);

                continue;
            }

            if ($key === 'dependentModule' && $value instanceof ModuleTransfer) {
                $normalizedData['dependentModule'] = $value->getName();

                continue;
            }

            if ($value instanceof ModuleTransfer) {
                $normalizedData['module'] = $value->getName();

                continue;
            }

            if ($value instanceof ReturnTypeTransfer) {
                $value = $value->getType();
            }

            if ($value instanceof ClassInformationTransfer) {
                $value = $value->getFullyQualifiedClassName();
            }

            if ($value instanceof ArgumentCollectionTransfer) {
                $normalizedData = $this->normalizeArgumentCollection($key, $value, $normalizedData);

                continue;
            }

            $normalizedData[$key] = $value;
        }

        return $normalizedData;
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return \Generated\Shared\Transfer\OrganizationTransfer|null
     */
    protected function findOrganizationTransfer(array $data): ?OrganizationTransfer
    {
        if (isset($data['organization']) && $data['organization'] instanceof OrganizationTransfer) {
            return $data['organization'];
        }

        if (
            isset($data['module'])
            && $data['module'] instanceof ModuleTransfer
            && $data['module']->getOrganization() instanceof OrganizationTransfer
        ) {
            return $data['module']->getOrganization();
        }

        return null;
    }

    /**
     * @param string $argumentName
     * @param \Generated\Shared\Transfer\ArgumentCollectionTransfer $argumentCollectionTransfer
     * @param array<string, mixed> $normalizedData
     *
     * @return array<string, mixed>
     */
    protected function normalizeArgumentCollection(string $argumentName, ArgumentCollectionTransfer $argumentCollectionTransfer, array $normalizedData): array
    {
        $arguments = [];
        $methods = [];

        foreach ($argumentCollectionTransfer->getArguments() as $argumentTransfer) {
            $arguments[] = $this->buildFromArgument($argumentTransfer);
            if ($argumentTransfer->getArgumentMeta() && $argumentTransfer->getArgumentMetaOrFail()->getMethod()) {
                $methods[] = $argumentTransfer->getArgumentMetaOrFail()->getMethod();
            }
        }

        $normalizedData[$argumentName] = $arguments;
        $normalizedData['dependencyMethods'] = $methods;

        return $normalizedData;
    }

    /**
     * @param \Generated\Shared\Transfer\ArgumentTransfer $argumentTransfer
     *
     * @return string
     */
    protected function buildFromArgument(ArgumentTransfer $argumentTransfer): string
    {
        $pattern = '%s %s';
        if ($argumentTransfer->getIsOptional()) {
            $pattern = '?%s %s';
        }
        if ($argumentTransfer->getDefaultValue()) {
            $pattern .= sprintf(' = %s', $argumentTransfer->getDefaultValue());
        }

        return sprintf($pattern, $argumentTransfer->getType(), $argumentTransfer->getVariable());
    }
}

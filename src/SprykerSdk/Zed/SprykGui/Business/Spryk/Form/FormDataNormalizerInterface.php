<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\SprykGui\Business\Spryk\Form;

interface FormDataNormalizerInterface
{
    /**
     * @param array<string, mixed> $formData
     *
     * @return array<string, mixed>
     */
    public function normalizeFormData(array $formData): array;
}

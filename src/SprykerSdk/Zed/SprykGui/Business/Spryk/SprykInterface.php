<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\SprykGui\Business\Spryk;

interface SprykInterface
{
    /**
     * @return array
     */
    public function getSprykDefinitions(): array;

    /**
     * @param string $sprykName
     * @param array $sprykArguments
     *
     * @return array<string, mixed>
     */
    public function buildSprykView(string $sprykName, array $sprykArguments): array;

    /**
     * @param string $sprykName
     * @param array $sprykArguments
     *
     * @return string
     */
    public function runSpryk(string $sprykName, array $sprykArguments): string;

    /**
     * @param string $sprykName
     *
     * @return string
     */
    public function drawSpryk(string $sprykName): string;
}

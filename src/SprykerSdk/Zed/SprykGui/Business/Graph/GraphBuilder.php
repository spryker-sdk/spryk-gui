<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\SprykGui\Business\Graph;

use Spryker\Zed\Graph\Communication\Plugin\GraphPlugin;
use SprykerSdk\Zed\SprykGui\Dependency\Facade\SprykGuiToSprykFacadeInterface;

class GraphBuilder implements GraphBuilderInterface
{
    /**
     * @var \SprykerSdk\Zed\SprykGui\Dependency\Facade\SprykGuiToSprykFacadeInterface
     */
    protected $sprykFacade;

    /**
     * @var \Spryker\Zed\Graph\Communication\Plugin\GraphPlugin
     */
    protected $graphPlugin;

    /**
     * @param \SprykerSdk\Zed\SprykGui\Dependency\Facade\SprykGuiToSprykFacadeInterface $sprykFacade
     * @param \Spryker\Zed\Graph\Communication\Plugin\GraphPlugin $graphPlugin
     */
    public function __construct(SprykGuiToSprykFacadeInterface $sprykFacade, GraphPlugin $graphPlugin)
    {
        $this->sprykFacade = $sprykFacade;
        $this->graphPlugin = $graphPlugin;
    }

    /**
     * @param string $sprykName
     *
     * @return string
     */
    public function drawSpryk(string $sprykName): string
    {
        $sprykDefinitions = $this->sprykFacade->getSprykDefinitions();

        $this->graphPlugin->init('spryks');
        $this->graphPlugin->addNode($sprykName);

        $this->addEdge($sprykDefinitions, $sprykName);

        return $this->graphPlugin->render('svg');
    }

    /**
     * @param array<string, array> $sprykDefinitions
     * @param string $sprykName
     * @param array<string> $existingSpryks
     *
     * @return void
     */
    protected function addEdge(array $sprykDefinitions, string $sprykName, array $existingSpryks = []): void
    {
        $existingSpryks[] = $sprykName;
        $sprykDefinition = $sprykDefinitions[$sprykName];

        $subSprykTypeColors = ['preSpryks' => 'blue', 'postSpryks' => 'red'];
        foreach ($subSprykTypeColors as $subSprykType => $color) {
            if (!isset($sprykDefinition[$subSprykType])) {
                continue;
            }
            foreach ($sprykDefinition[$subSprykType] as $subSprykName) {
                if (is_array($subSprykName)) {
                    $subSprykName = current(array_keys($subSprykName));
                }
                $this->graphPlugin->addNode($subSprykName);
                $this->graphPlugin->addEdge($sprykName, $subSprykName, ['color' => $color]);

                if (!in_array($subSprykName, $existingSpryks)) {
                    $this->addEdge($sprykDefinitions, $subSprykName, $existingSpryks);
                }
            }
        }
    }
}

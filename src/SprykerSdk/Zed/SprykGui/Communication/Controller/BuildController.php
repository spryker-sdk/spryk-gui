<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\SprykGui\Communication\Controller;

use Generated\Shared\Transfer\SprykDefinitionTransfer;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerSdk\Zed\SprykGui\Communication\SprykGuiCommunicationFactory getFactory()
 * @method \SprykerSdk\Zed\SprykGui\Business\SprykGuiFacadeInterface getFacade()
 */
class BuildController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function indexAction(Request $request): array
    {
        $sprykDefinitionTransfer = $this->createSprykDefinitionTransfer($request);

        $sprykForm = $this->getFactory()
            ->getSprykMainForm($sprykDefinitionTransfer)
            ->handleRequest($request);

        $canRunBuild = $this->canRunBuild($sprykForm);
        if ($sprykForm->isSubmitted() && $canRunBuild && $sprykForm->isValid()) {
            $formData = $sprykForm->getData();

            if ($this->getClickableByName($sprykForm, 'create')->isClicked()) {
                return $this->viewResponse(
                    $this->getFacade()->buildSprykView($request->query->get('spryk'), $formData)
                );
            }

            $runResult = $this->getFacade()->runSpryk($request->query->get('spryk'), $formData);
            if ($runResult) {
                $this->addSuccessMessage(sprintf('Spryk "%s" executed successfully.', $request->query->get('spryk')));
                $messages = explode("\n", rtrim($runResult, "\n"));
            }
        }

        return $this->viewResponse([
            'form' => $sprykForm->createView(),
            'messages' => (isset($messages)) ? $messages : [],
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $sprykForm
     * @param string $buttonName
     *
     * @return \Symfony\Component\Form\ClickableInterface|\Symfony\Component\Form\FormInterface
     */
    protected function getClickableByName(FormInterface $sprykForm, string $buttonName)
    {
        return $sprykForm->get($buttonName);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $sprykForm
     *
     * @return bool
     */
    protected function canRunBuild(FormInterface $sprykForm): bool
    {
        if ($sprykForm->has('run') && $this->getClickableByName($sprykForm, 'run')->isClicked()) {
            return true;
        }
        if ($sprykForm->has('create') && $this->getClickableByName($sprykForm, 'create')->isClicked()) {
            return true;
        }

        return false;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\SprykDefinitionTransfer
     */
    protected function createSprykDefinitionTransfer(Request $request): SprykDefinitionTransfer
    {
        $spryk = $request->query->get('spryk');
        $mode = $request->query->get('mode');

        $sprykDefinitionTransfer = new SprykDefinitionTransfer();

        return $sprykDefinitionTransfer->setName($spryk)->setMode($mode);
    }
}

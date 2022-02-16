<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\SprykGui\Communication\Controller;

use Generated\Shared\Transfer\SprykDefinitionTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\Form\ClickableInterface;
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
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|mixed[]
     */
    public function indexAction(Request $request)
    {
        $sprykDefinitionTransfer = $this->createSprykDefinitionTransfer($request);

        $preBuildForm = $this->getFactory()
            ->createPreBuildForm($sprykDefinitionTransfer)
            ->handleRequest();

        if ($preBuildForm->isSubmitted() && $preBuildForm->isValid()) {
            return $this->redirectResponse(
                sprintf(
                    '/spryk-gui/build/build?spryk=%s&mode=%s&enterModuleManually=%s',
                    $sprykDefinitionTransfer->getName(),
                    $preBuildForm->get('mode')->getData(),
                    $preBuildForm->get('enterModuleManually')->getData(),
                ),
            );
        }

        return $this->viewResponse([
            'sprykName' => $sprykDefinitionTransfer->getName(),
            'form' => $preBuildForm->createView(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|mixed[]
     */
    public function buildAction(Request $request)
    {
        $sprykDefinitionTransfer = $this->createSprykDefinitionTransfer($request);

        if ($sprykDefinitionTransfer->getEnterModuleManually() === null || $sprykDefinitionTransfer->getMode() === null) {
            return $this->redirectResponse(
                sprintf(
                    '/spryk-gui/build?spryk=%s',
                    $sprykDefinitionTransfer->getName(),
                ),
            );
        }

        $sprykForm = $this->getFactory()
            ->getSprykMainForm($sprykDefinitionTransfer)
            ->handleRequest($request);

        $canRunBuild = $this->canRunBuild($sprykForm);
        if ($sprykForm->isSubmitted() && $canRunBuild && $sprykForm->isValid()) {
            $formData = $sprykForm->getData();
            $sprykName = (string)$request->query->get('spryk');

            if ($this->getClickableByName($sprykForm, 'create')->isClicked()) {
                return $this->viewResponse(
                    $this->getFacade()->buildSprykView($sprykName, $formData),
                );
            }

            $runResult = $this->getFacade()->runSpryk($sprykName, $formData);
            if ($runResult) {
                $this->addSuccessMessage(sprintf('Spryk "%s" executed successfully.', $request->query->get('spryk')));
                $messages = explode("\n", rtrim($runResult, "\n"));
            }
        }

        return $this->viewResponse([
            'sprykName' => $sprykDefinitionTransfer->getName(),
            'form' => $sprykForm->createView(),
            'messages' => isset($messages) ? $messages : [],
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $sprykForm
     * @param string $buttonName
     *
     * @return \Symfony\Component\Form\ClickableInterface
     */
    protected function getClickableByName(FormInterface $sprykForm, string $buttonName): ClickableInterface
    {
        /** @var \Symfony\Component\Form\ClickableInterface $button */
        $button = $sprykForm->get($buttonName);

        return $button;
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
        $enterModuleManually = (bool)$request->query->get('enterModuleManually');
        /** @var string|null $mode */
        $mode = $request->query->get('mode');

        return (new SprykDefinitionTransfer())
            ->setName((string)$request->query->get('spryk'))
            ->setMode($mode)
            ->setEnterModuleManually($enterModuleManually);
    }
}

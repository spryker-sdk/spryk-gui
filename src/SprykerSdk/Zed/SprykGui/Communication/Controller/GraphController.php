<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\SprykGui\Communication\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * @method \SprykerSdk\Zed\SprykGui\Communication\SprykGuiCommunicationFactory getFactory()
 * @method \SprykerSdk\Zed\SprykGui\Business\SprykGuiFacadeInterface getFacade()
 * @method \SprykerSdk\Zed\SprykGui\Persistence\SprykGuiQueryContainerInterface getQueryContainer()
 */
class GraphController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function indexAction(Request $request): StreamedResponse
    {
        $spryk = $request->query->get('spryk');

        $response = $this->getFacade()->drawSpryk($spryk);

        $callback = function () use ($response) {
            echo $response;
        };

        return $this->streamedResponse(
            $callback,
            Response::HTTP_OK,
            $this->getStreamedResponseHeaders('svg')
        );
    }

    /**
     * @param string $format
     *
     * @return array
     */
    protected function getStreamedResponseHeaders(string $format): array
    {
        $headers = [];

        $formatContentTypes = [
            'jpg' => 'image/jpeg',
            'svg' => 'image/svg+xml',
        ];
        if (isset($formatContentTypes[$format])) {
            $headers['content-type'] = $formatContentTypes[$format];
        }

        return $headers;
    }
}

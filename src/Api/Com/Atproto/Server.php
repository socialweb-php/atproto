<?php

/**
 * This file is part of socialweb/atproto
 *
 * socialweb/atproto is free software: you can redistribute it
 * and/or modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either version
 * 3 of the License, or (at your option) any later version.
 *
 * socialweb/atproto is distributed in the hope that it will be
 * useful, but WITHOUT ANY WARRANTY; without even the implied warranty
 * of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with socialweb/atproto.
 * If not, see <https://www.gnu.org/licenses/>.
 *
 * @copyright Copyright (c) Ben Ramsey <ben@benramsey.com>
 * @license https://opensource.org/license/lgpl-3-0/ GNU Lesser General Public License version 3 or later
 */

declare(strict_types=1);

namespace SocialWeb\Atproto\Api\Com\Atproto;

use Com\Atproto\Server as ServerLexicon;
use Psr\Http\Message\UriInterface;
use SocialWeb\Atproto\Api\Options;
use SocialWeb\Atproto\Api\XrpcResponseError;

use function json_decode;
use function json_encode;

/**
 * Maybe this class should be auto-generated from the sources in
 * `resources/bluesky-social/atproto/lexicons`?
 */
class Server implements ServerLexicon
{
    public function __construct(
        private readonly UriInterface $service,
        private readonly Options $options,
    ) {
    }

    public function createSession(string $identifier, string $password): object
    {
        $url = $this->service->withPath('/xrpc/com.atproto.server.createSession');

        $request = $this->options->requestFactory->createRequest('POST', $url);
        $request = $request->withBody($this->options->streamFactory->createStream((string) json_encode([
            'identifier' => $identifier,
            'password' => $password,
        ])));

        // Much of the following should be abstracted out of here.
        $request = $request->withHeader('content-type', 'application/json');
        $response = $this->options->httpClient->sendRequest($request);

        $body = (object) [];
        $rawBody = $response->getBody()->getContents();
        if ($rawBody !== '') {
            $body = (object) json_decode($rawBody);
        }

        if ($response->getStatusCode() < 200 || $response->getStatusCode() > 299) {
            throw new XrpcResponseError(
                (string) ($body->message ?? $body->error ?? $response->getReasonPhrase()),
                $response->getStatusCode(),
                (string) ($body->error ?? ''),
            );
        }

        /** @var object{accessJwt: string, refreshJwt: string, handle: string, did: string, email?: string} */
        return $body;
    }

    public function getSession(): object
    {
        $url = $this->service->withPath('/xrpc/com.atproto.server.getSession');

        // Much of the following should be abstracted out of here.
        if ($this->options->session === null) {
            throw new XrpcResponseError('Invalid session');
        }

        $request = $this->options->requestFactory->createRequest('GET', $url);

        $request = $request->withHeader('authorization', 'Bearer ' . $this->options->session->accessJwt);
        $response = $this->options->httpClient->sendRequest($request);

        $body = (object) [];
        $rawBody = $response->getBody()->getContents();
        if ($rawBody !== '') {
            $body = (object) json_decode($rawBody);
        }

        if ($response->getStatusCode() < 200 || $response->getStatusCode() > 299) {
            throw new XrpcResponseError(
                (string) ($body->message ?? $body->error ?? $response->getReasonPhrase()),
                $response->getStatusCode(),
                (string) ($body->error ?? ''),
            );
        }

        /** @var object{accessJwt: string, refreshJwt: string, handle: string, did: string, email?: string} */
        return $body;
    }
}

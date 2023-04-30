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

use Com\Atproto\Identity as IdentityLexicon;
use Psr\Http\Message\UriInterface;
use SocialWeb\Atproto\Api\Options;
use SocialWeb\Atproto\Api\XrpcResponseError;

use function http_build_query;
use function json_decode;

/**
 * Maybe this class should be auto-generated from the sources in
 * `resources/bluesky-social/atproto/lexicons`?
 */
class Identity implements IdentityLexicon
{
    public function __construct(
        private readonly UriInterface $service,
        private readonly Options $options,
    ) {
    }

    public function resolveHandle(?string $handle): object
    {
        $url = $this->service->withPath('/xrpc/com.atproto.identity.resolveHandle');

        if ($handle !== null) {
            $url = $url->withQuery(http_build_query(['handle' => $handle]));
        }

        // Much of the following should be abstracted out of here.
        $request = $this->options->requestFactory->createRequest('GET', $url);

        if ($this->options->session !== null) {
            $request = $request->withHeader('authorization', 'Bearer ' . $this->options->session->accessJwt);
        }

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

        /** @var object{did: string} */
        return $body;
    }
}
